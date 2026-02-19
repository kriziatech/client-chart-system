<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use \App\Traits\BelongsToTenant;

    protected $fillable = ['company_id', 'name', 'description', 'type'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}