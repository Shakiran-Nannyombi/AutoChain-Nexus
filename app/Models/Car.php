<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'model',
        'vin',
        'manufacturer_id',
        'retailer_id',
        'condition',
        'received_date',
        'sold',
    ];

    public function productionBatch()
    {
        return $this->belongsTo(ProductionBatch::class);
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }

    public function productionStage()
    {
        return $this->hasOne(ProductionStage::class);
    }

    public function inventoryItem()
    {
        return $this->hasOne(InventoryItem::class);
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }
}
