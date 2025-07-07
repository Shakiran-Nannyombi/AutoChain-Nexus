<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RetailerStock extends Model
{
    protected $fillable = [
        'retailer_id', 'car_model', 'vendor_name', 'quantity_received', 'status',
    ];

    public function retailer()
    {
        return $this->belongsTo(Retailer::class);
    }

    public function vendor()
{
    return $this->belongsTo(User::class, 'vendor_id');
}

use HasFactory;

}
