<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RetailerOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'vendor_id', 
        'customer_name', 
        'car_model', 
        'quantity',
        'status',
        'total_amount',
        'ordered_at',
        'confirmed_at',
        'shipped_at',
        'delivered_at',
        'notes'
    ];

    protected $casts = [
        'ordered_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // RetailerOrder.php
public function user()
{
    return $this->belongsTo(User::class);
}

public function retailer()
{
    return $this->belongsTo(User::class, 'user_id');
}
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
