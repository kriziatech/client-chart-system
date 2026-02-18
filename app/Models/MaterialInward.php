<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialInward extends Model
{
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'client_id',
        'supplier_name',
        'item_name',
        'unit',
        'quantity',
        'rate',
        'total_amount',
        'bill_number',
        'inward_date',
        'deletion_remark',
    ];

    protected $casts = [
        'inward_date' => 'date',
        'quantity' => 'decimal:2',
        'rate' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function payments()
    {
        return $this->hasMany(MaterialPayment::class , 'material_inward_id');
    }

    // Accessor for pending amount on this specific bill
    public function getPendingAmountAttribute()
    {
        return $this->total_amount - $this->payments()->sum('amount_paid');
    }
}