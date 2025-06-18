<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryItem extends Model
{
    use HasFactory;

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
