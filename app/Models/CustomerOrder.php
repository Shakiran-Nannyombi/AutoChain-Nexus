<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerOrder extends Model
{
    use HasFactory;

    protected $fillable = [
     customer_id,
     retailer_id',
    product_id',
  quantity',
      total_amount,       status',
       customer_name',
        customer_email',
        customer_phone',
       customer_address',
    order_date',
      notes'
    ];

    protected $casts = [
       order_date' => 'datetime',
       created_at' => 'datetime',
       updated_at' => 'datetime',
    ];

    public function customer()
    [object Object]      return $this->belongsTo(Customer::class);
    }

    public function retailer()
    [object Object]      return $this->belongsTo(User::class, 'retailer_id');
    }

    public function product()
    [object Object]      return $this->belongsTo(Product::class);
    }
} 