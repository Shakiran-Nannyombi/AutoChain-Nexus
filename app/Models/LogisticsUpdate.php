<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogisticsUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'timestamp',
        'purchase_order_id',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
} 