<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Task extends Model
{
    use HasFactory, Auditable;

    protected $guarded = [];

    protected $casts = [
        'deadline' => 'date',
        'start_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}