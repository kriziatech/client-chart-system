<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class ChecklistItem extends Model
{
    use HasFactory, Auditable;

    protected $guarded = [];

    protected $casts = [
        'is_checked' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}