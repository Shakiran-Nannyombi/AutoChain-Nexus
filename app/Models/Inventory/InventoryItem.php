<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Warehouse\Car;
use App\Models\Warehouse\Warehouse;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'current_stock',
        'unit_price',
        'min_stock_threshold',
        'critical_stock_threshold',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
