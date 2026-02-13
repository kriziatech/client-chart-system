<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectFeedback extends Model
{
    use HasFactory, \App\Traits\Auditable;

    protected $fillable = [
        'client_id',
        'rating',
        'comment',
        'is_testimonial',
        'status',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}