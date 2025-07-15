<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorOrder extends Model
{
    use HasFactory;

    protected $table = 'vendor_orders';

    protected $fillable = [
        'manufacturer_id',
        'vendor_id',
        'product',
        'quantity',
        'status',
        'ordered_at',
    ];

    protected $dates = [
        'ordered_at',
    ];
} 