<?php

namespace App\Models\Pitch;

use Illuminate\Database\Eloquent\Model;

class PitchDesignConcept extends Model
{
    protected $fillable = ['lead_id', 'version', 'status', 'notes'];

    public function lead()
    {
        return $this->belongsTo(PitchLead::class , 'lead_id');
    }

    public function assets()
    {
        return $this->hasMany(PitchDesignAsset::class , 'concept_id');
    }
}