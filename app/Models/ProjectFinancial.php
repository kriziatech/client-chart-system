<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectFinancial extends Model
{
    use HasFactory, \App\Traits\BelongsToTenant;

    protected $fillable = [
        'company_id',
        'client_id',
        'budget_locked_amount',
        'is_locked',
        'expected_profit_margin',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
        'budget_locked_amount' => 'decimal:2',
        'expected_profit_margin' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}