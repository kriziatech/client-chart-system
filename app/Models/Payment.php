<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Payment extends Model
{
    use Auditable;

    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}