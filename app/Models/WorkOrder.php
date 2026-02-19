<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class WorkOrder extends Model
{
    use HasFactory, Auditable, \App\Traits\BelongsToTenant;

    protected $guarded = [];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'items' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->work_order_number)) {
                $latest = self::latest()->first();
                $id = $latest ? $latest->id + 1 : 1;
                $model->work_order_number = 'WO-' . date('Ymd') . '-' . str_pad($id, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}