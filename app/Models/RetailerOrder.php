<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RetailerOrder extends Model
{
    protected $fillable = [
        'retailer_id', 'customer_name', 'car_model', 'quantity',
    ];

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }

use HasFactory;

}
