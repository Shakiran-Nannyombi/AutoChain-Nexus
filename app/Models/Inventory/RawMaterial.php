<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\SupplyChain\Suppliers;
use App\Models\SupplyChain\Delivery;


class RawMaterial extends Model
{
    use HasFactory;

    public function supplier()
    {
        return $this->belongsTo(Suppliers::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }
}
