<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory, \App\Traits\BelongsToTenant;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone',
        'category',
        'contact_person',
        'status',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }
}