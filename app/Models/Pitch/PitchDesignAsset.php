<?php

namespace App\Models\Pitch;

use Illuminate\Database\Eloquent\Model;

class PitchDesignAsset extends Model
{
    protected $fillable = ['concept_id', 'type', 'file_path', 'title', 'description'];

    public function concept()
    {
        return $this->belongsTo(PitchDesignConcept::class , 'concept_id');
    }

    public function feedback()
    {
        return $this->hasMany(PitchDesignFeedback::class , 'asset_id');
    }
}