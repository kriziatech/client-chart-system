<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_system' => 'boolean',
        'is_pinned' => 'boolean',
        'is_decision' => 'boolean',
        'reactions' => 'array',
        'metadata' => 'array',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function parent()
    {
        return $this->belongsTo(ChatMessage::class , 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(ChatMessage::class , 'parent_id');
    }

    public function linkedTask()
    {
        return $this->belongsTo(Task::class , 'linked_task_id');
    }
}