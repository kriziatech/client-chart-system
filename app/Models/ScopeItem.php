<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScopeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'scope_of_work_id',
        'area_name',
        'description',
        'specifications'
    ];
}