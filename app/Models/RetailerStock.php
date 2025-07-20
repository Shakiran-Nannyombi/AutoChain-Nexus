<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RetailerStock extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'retailer_id', 'car_model', 'vendor_name', 'quantity_received', 'status', 'vendor_id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function retailer()
    {
        return $this->belongsTo(\App\Models\User::class, 'retailer_id');
    }

    public function vendor()
{
    return $this->belongsTo(Vendor::class);
}

}
