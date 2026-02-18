<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    public $_oldAuditValues = [];

    public static function bootAuditable()
    {
        // Log creation
        static::created(function ($model) {
            static::logAudit($model, 'created', null, $model->getAttributes());
        });

        // Log update — capture old and new values
        static::updating(function ($model) {
            $model->_oldAuditValues = $model->getOriginal();
        });

        static::updated(function ($model) {
            $changed = $model->getChanges();
            $oldValues = [];
            foreach (array_keys($changed) as $key) {
                if ($key === 'updated_at') continue;
                $oldValues[$key] = $model->_oldAuditValues[$key] ?? null;
            }
            // Remove updated_at from changes
            unset($changed['updated_at']);
            if (!empty($changed)) {
                static::logAudit($model, 'updated', $oldValues, $changed);
            }
        });

        // Log deletion
        static::deleted(function ($model) {
            static::logAudit($model, 'deleted', $model->getAttributes(), null);
        });
    }

    protected static function logAudit($model, string $action, ?array $oldValues, ?array $newValues)
    {
        $user = auth()->user();
        $request = request();
        
        // Basic Context Parsing
        $ua = $request ? $request->userAgent() : 'System';
        $ip = $request ? $request->ip() : '127.0.0.1';
        
        // Simple User Agent Parsing (Can be replaced by a library later)
        $browser = 'Unknown';
        if (str_contains($ua, 'Chrome')) $browser = 'Chrome';
        elseif (str_contains($ua, 'Firefox')) $browser = 'Firefox';
        elseif (str_contains($ua, 'Safari')) $browser = 'Safari';
        elseif (str_contains($ua, 'Edge')) $browser = 'Edge';
        
        $os = 'Unknown';
        if (str_contains($ua, 'Windows')) $os = 'Windows';
        elseif (str_contains($ua, 'Mac')) $os = 'MacOS';
        elseif (str_contains($ua, 'Linux')) $os = 'Linux';
        elseif (str_contains($ua, 'Android')) $os = 'Android';
        elseif (str_contains($ua, 'iPhone')) $os = 'iOS';
        
        $device = (str_contains($ua, 'Mobile') || str_contains($ua, 'Android') || str_contains($ua, 'iPhone')) 
            ? 'Mobile' : 'Desktop';

        // Build a human-readable description
        $modelName = class_basename($model);
        $identifier = $model->name ?? $model->title ?? $model->first_name ?? $model->quotation_number ?? "#{$model->id}";

        $friendlyAction = [
            'created' => 'generated a new',
            'updated' => 'modified the',
            'deleted' => 'permanently removed',
        ];

        $detail = "";
        if ($action === 'updated' && !empty($newValues)) {
            $changedFields = array_keys($newValues);
            $fieldList = implode(', ', array_map(fn($f) => str_replace('_', ' ', $f), $changedFields));
            $detail = " (Changes in: {$fieldList})";
        }

        $actionLabel = $friendlyAction[$action] ?? $action;
        $description = "{$actionLabel} {$modelName} \"{$identifier}\"{$detail}";

        AuditLog::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'System',
            'user_role' => $user?->role?->name ?? 'Personnel',
            'action' => ucfirst($action),
            'module' => $modelName,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'status' => 'success',
            'ip_address' => $ip,
            'user_agent' => $ua,
            'browser' => $browser,
            'os' => $os,
            'device_type' => $device,
            'source' => ($request && $request->wantsJson()) ? 'api' : 'web',
            'created_at' => now(),
        ]);
    }
}