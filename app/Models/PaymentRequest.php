<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    use \App\Traits\Auditable;

    protected $fillable = [
        'client_id', 'quotation_id', 'amount', 'title', 'description', 'due_date', 'status', 'last_reminder_sent_at'
    ];

    protected $casts = [
        'due_date' => 'date',
        'last_reminder_sent_at' => 'datetime'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}