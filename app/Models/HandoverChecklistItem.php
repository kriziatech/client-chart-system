<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandoverChecklistItem extends Model
{
    use HasFactory;

    protected $fillable = ['handover_id', 'item_name', 'is_completed'];

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    public function handover()
    {
        return $this->belongsTo(Handover::class);
    }
}