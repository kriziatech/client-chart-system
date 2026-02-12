<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Client extends Model
{
    use Auditable;

    protected $guarded = [];

    public function checklistItems()
    {
        return $this->hasMany(ChecklistItem::class);
    }

    public function siteInfo()
    {
        return $this->hasOne(SiteInfo::class);
    }

    public function permission()
    {
        return $this->hasOne(Permission::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}