<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Purchase;
use App\Models\Customer;

class Product extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'name',
        'category',
        'price',
        'stock',
        'vendor_id',
        'manufacturer_id',
        'image_url',
    ];
    public function vendor()
    {
        return $this->belongsTo(\App\Models\User::class, 'vendor_id');
    }
    public function manufacturer()
    {
        return $this->belongsTo(\App\Models\User::class, 'manufacturer_id');
    }
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
