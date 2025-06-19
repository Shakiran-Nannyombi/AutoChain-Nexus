<?php

namespace App\Models\SupplyChain;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Inventory\RawMaterial;

class Suppliers extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'rating',
        'orders',
        'on_time_delivery',
        'contact_name',
        'contact_email',
        'contact_phone',
        'address',
        'status',
        'logo_url',
    ];

    public function rawMaterials()
    {
        return $this->hasMany(RawMaterial::class);
    }
}
