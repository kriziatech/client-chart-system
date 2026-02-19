<?php

namespace App\Models\Pitch;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Client;

class PitchLead extends Model
{
    use \App\Traits\Auditable, \App\Traits\BelongsToTenant;

    protected $table = 'pitch_leads';

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone',
        'status',
        'source',
        'work_description',
        'assigned_to_id',
        'is_converted',
        'converted_at',
        'converted_client_id',
    ];

    protected $casts = [
        'is_converted' => 'boolean',
        'converted_at' => 'datetime',
    ];

    public function assignedTo()
    {
        return $this->belongsTo(User::class , 'assigned_to_id');
    }

    public function sites()
    {
        return $this->hasMany(PitchLeadSite::class , 'lead_id');
    }

    public function activities()
    {
        return $this->hasMany(PitchLeadActivity::class , 'lead_id');
    }

    public function visits()
    {
        return $this->hasMany(PitchLeadVisit::class , 'lead_id');
    }

    public function convertedClient()
    {
        return $this->belongsTo(Client::class , 'converted_client_id');
    }

    public function concepts()
    {
        return $this->hasMany(PitchDesignConcept::class , 'lead_id');
    }
}