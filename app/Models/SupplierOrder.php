<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierOrder extends Model
{
    protected $fillable = [
        'supplier_id', 'order_date', 'status',
    ];

    public function items()
    {
        return $this->hasMany(SupplierOrderItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }
}
