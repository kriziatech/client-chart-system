<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'material_inward_id',
        'supplier_name',
        'paid_to',
        'amount_paid',
        'payment_date',
        'payment_mode',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount_paid' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function inward()
    {
        return $this->belongsTo(MaterialInward::class , 'material_inward_id');
    }
}