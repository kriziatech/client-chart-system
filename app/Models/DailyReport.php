<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'report_date', 'content'];

    protected $casts = [
        'report_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function images()
    {
        return $this->hasMany(DailyReportImage::class);
    }
}