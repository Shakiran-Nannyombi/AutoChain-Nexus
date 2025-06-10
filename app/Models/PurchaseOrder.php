<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'supplier_id',
        'status',
        'total',
        'expected_delivery',
        'delivery_date',
        'quality_check_passed',
    ];

    protected $casts = [
        'expected_delivery' => 'datetime',
        'delivery_date' => 'datetime',
        'quality_check_passed' => 'boolean',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function logisticsUpdates()
    {
        return $this->hasMany(LogisticsUpdate::class);
    }
}
