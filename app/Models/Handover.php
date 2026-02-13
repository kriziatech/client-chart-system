<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Handover extends Model
{
    use HasFactory, \App\Traits\Auditable;

    protected $fillable = [
        'client_id',
        'handover_date',
        'warranty_years',
        'warranty_expiry',
        'client_signature',
        'status',
    ];

    protected $casts = [
        'handover_date' => 'date',
        'warranty_expiry' => 'date',
    ];

    public function checklistItems()
    {
        return $this->hasMany(HandoverChecklistItem::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}