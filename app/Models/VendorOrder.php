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
        'product_name',
        'product_category',
        'quantity',
        'unit_price',
        'total_amount',
        'status',
        'ordered_at',
        'accepted_at',
        'rejected_at',
        'rejection_reason',
        'notes',
        'delivery_address',
        'expected_delivery_date',
    ];

    protected $casts = [
        'ordered_at' => 'datetime',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
        'expected_delivery_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(\App\Models\User::class, 'vendor_id');
    }
    public function manufacturer()
    {
        return $this->belongsTo(\App\Models\User::class, 'manufacturer_id');
    }
} 