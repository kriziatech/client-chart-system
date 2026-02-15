<?php

namespace App\Models\Pitch;

use Illuminate\Database\Eloquent\Model;

class PitchLeadSite extends Model
{
    protected $table = 'pitch_lead_sites';

    protected $fillable = [
        'lead_id',
        'address',
        'plot_size',
        'location_coordinates',
        'notes',
    ];

    public function lead()
    {
        return $this->belongsTo(PitchLead::class , 'lead_id');
    }
}