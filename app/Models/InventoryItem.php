<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use \App\Traits\Auditable;

    protected $fillable = ['name', 'category', 'unit', 'unit_price', 'stock_alert_level'];

    public function projectMaterials()
    {
        return $this->hasMany(ProjectMaterial::class);
    }
}