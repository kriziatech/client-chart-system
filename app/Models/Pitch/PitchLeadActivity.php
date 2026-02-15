<?php

namespace App\Models\Pitch;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PitchLeadActivity extends Model
{
    protected $table = 'pitch_lead_activities';

    protected $fillable = [
        'lead_id',
        'user_id',
        'action',
        'old_status',
        'new_status',
        'notes',
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