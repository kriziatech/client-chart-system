<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Payment extends Model
{
    use Auditable, \App\Traits\BelongsToTenant;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}