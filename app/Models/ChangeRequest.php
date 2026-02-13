<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeRequest extends Model
{
    use HasFactory, \App\Traits\Auditable;

    protected $fillable = ['client_id', 'title', 'description', 'cost_impact', 'status', 'approved_at', 'admin_notes'];

    protected $casts = [
        'approved_at' => 'datetime',
        'cost_impact' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}