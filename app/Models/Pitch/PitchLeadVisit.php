<?php

namespace App\Models\Pitch;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PitchLeadVisit extends Model
{
    protected $table = 'pitch_lead_visits';

    protected $fillable = [
        'lead_id',
        'user_id',
        'visited_at',
        'purpose',
        'outcome',
        'observations',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    public function lead()
    {
        return $this->belongsTo(PitchLead::class , 'lead_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}