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

        // Build a human-readable description
        $modelName = class_basename($model);
        $identifier = $model->name ?? $model->first_name ?? $model->title ?? "#{$model->id}";

        $descriptions = [
            'created' => "{$modelName} \"{$identifier}\" was created",
            'updated' => "{$modelName} \"{$identifier}\" was updated",
            'deleted' => "{$modelName} \"{$identifier}\" was deleted",
        ];

        AuditLog::create([
            'user_id' => $user?->id,
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'description' => $descriptions[$action] ?? "{$action} {$modelName}",
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()?->ip(),
            'created_at' => now(),
        ]);
    }
}