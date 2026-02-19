<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPayment extends Model
{
    use HasFactory, \Illuminate\Database\Eloquent\SoftDeletes, \App\Traits\BelongsToTenant;

    protected $fillable = [
        'company_id',
        'client_id',
        'vendor_id',
        'amount',
        'work_type',
        'payment_date',
        'payment_mode',
        'reference_number',
        'quotation_image_path',
        'notes',
        'deletion_remark',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}