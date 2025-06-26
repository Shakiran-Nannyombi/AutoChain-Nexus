<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RetailerSale extends Model
{
    protected $fillable = [
        'retailer_id', 'car_model', 'quantity_sold',
    ];

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }
}
