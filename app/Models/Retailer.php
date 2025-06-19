<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
