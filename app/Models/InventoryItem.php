<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use \App\Traits\Auditable, \App\Traits\BelongsToTenant;

    protected $fillable = ['company_id', 'name', 'category', 'unit', 'unit_price', 'total_stock', 'stock_alert_level'];

    public function projectMaterials()
    {
        return $this->hasMany(ProjectMaterial::class);
    }
}