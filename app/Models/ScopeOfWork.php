<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScopeOfWork extends Model
{
    use HasFactory, \App\Traits\Auditable;

    protected $fillable = [
        'client_id',
        'version_name',
        'exclusions'
    ];

    public function items()
    {
        return $this->hasMany(ScopeItem::class);
    }
}