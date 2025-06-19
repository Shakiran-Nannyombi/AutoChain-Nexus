<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Inventory\InventoryItem;
use App\Models\SupplyChain\Shipment;

class Warehouse extends Model
{
    use HasFactory;

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }
}
