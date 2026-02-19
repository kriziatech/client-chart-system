<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use \App\Traits\BelongsToTenant;

    public $timestamps = false; // Only created_at, managed by DB default

    protected $fillable = [
        'company_id',
        'user_id', 'user_name', 'user_role',
        'action', 'module', 'model_type', 'model_id',
        'description', 'old_values', 'new_values',
        'status', 'failure_reason',
        'ip_address', 'user_agent', 'browser', 'os', 'device_type',
        'source', 'is_system_action', 'is_immutable',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    // Immutable — prevent updates and deletes
    public static function boot()
    {
        parent::boot();

        static::updating(function () {
            throw new \RuntimeException('Audit logs cannot be modified.');
        });

        static::deleting(function () {
            throw new \RuntimeException('Audit logs cannot be deleted.');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get short model name (e.g. "Client" instead of "App\Models\Client")
     */
    public function getModelNameAttribute(): string
    {
        return class_basename($this->model_type);
    }
}