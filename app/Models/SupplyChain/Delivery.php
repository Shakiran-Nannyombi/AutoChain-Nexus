<?php

namespace App\Models\SupplyChain;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Inventory\RawMaterial;
use App\Models\SupplyChain\Manufacturer;

class Delivery extends Model
{
    use HasFactory;

    public function rawMaterial()
    {
        return $this->belongsTo(RawMaterial::class);
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }
}
