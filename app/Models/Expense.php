<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory, \App\Traits\Auditable;

    protected $fillable = [
        'client_id',
        'vendor_id',
        'description',
        'category',
        'amount',
        'date',
        'payment_mode',
        'paid_through',
        'paid_to',
        'attachment',
        'comments'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }


    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function dailyReport()
    {
        return $this->belongsTo(DailyReport::class);
    }
}