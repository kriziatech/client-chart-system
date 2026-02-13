<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectGallery extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'image_path', 'caption', 'type'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}