<?php

namespace App\Models\SupplyChain;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\SupplyChain\Shipment;
use App\Models\Order\Purchase;

class Retailer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
    ];

    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
