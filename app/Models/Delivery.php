<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Delivery extends Model
{
    protected $fillable = ['supplier_id', 'manufacturer_id', 'materials_delivered', 'retailer_order_id', 'checklist_request_id', 'status', 'driver', 'destination', 'progress', 'eta', 'tracking_history'];

    protected $casts = [
        'materials_delivered' => 'array',
        'tracking_history' => 'array',
    ];

use HasFactory;

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function retailerOrder()
    {
        return $this->belongsTo(RetailerOrder::class, 'retailer_order_id');
    }
    
    public function checklistRequest()
    {
        return $this->belongsTo(ChecklistRequest::class, 'checklist_request_id');
    }

    /**
     * Get the manufacturer (user) that this delivery belongs to.
     */
    public function manufacturer()
    {
        return $this->belongsTo(\App\Models\User::class, 'manufacturer_id');
    }
}
