<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\UserManagement\Customer;
use App\Models\SupplyChain\Retailer;
use App\Models\Warehouse\Car;

class Purchase extends Model
{
    use HasFactory;

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
