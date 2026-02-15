<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use Auditable;

    protected $guarded = [];

    protected $casts = [
        'date' => 'date',
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Helper calculate hours worked
    public function getDurationAttribute()
    {
        if ($this->check_in_time && $this->check_out_time) {
            return $this->check_in_time->diffInHours($this->check_out_time) . ' hours';
        }
        return '-';
    }
}