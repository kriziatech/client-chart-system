<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectMaterial extends Model
{
    use \App\Traits\Auditable;

    protected $fillable = [
        'client_id', 'inventory_item_id', 'quantity_dispatched', 'status', 'delivery_date', 'notes'
    ];

    protected $casts = [
        'delivery_date' => 'date'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }
}