<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false; // Only created_at, managed by DB default

    protected $fillable = [
        'user_id', 'action', 'model_type', 'model_id',
        'description', 'old_values', 'new_values', 'ip_address', 'created_at',
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