<?php

namespace App\Models\Pitch;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PitchDesignFeedback extends Model
{
    protected $table = 'pitch_design_feedback';
    protected $fillable = ['asset_id', 'user_id', 'comment'];

    public function asset()
    {
        return $this->belongsTo(PitchDesignAsset::class , 'asset_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}