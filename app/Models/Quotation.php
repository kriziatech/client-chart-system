<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory, SoftDeletes, \App\Traits\Auditable, \App\Traits\BelongsToTenant;

    protected $fillable = [
        'company_id',
        'client_id',
        'lead_id',
        'quotation_number',
        'project_type',
        'date',
        'valid_until',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'gst_percentage',
        'total_amount',
        'status',
        'version',
        'parent_id',
        'notes',
        'signature_path',
        'signed_at',
        'signature_data',
        'deletion_remark'
    ];

    protected $casts = [
        'date' => 'date',
        'valid_until' => 'date',
        'signed_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function parent()
    {
        return $this->belongsTo(Quotation::class , 'parent_id');
    }

    public function versions()
    {
        return $this->hasMany(Quotation::class , 'parent_id');
    }
}