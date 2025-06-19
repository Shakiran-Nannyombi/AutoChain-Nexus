<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    protected $fillable = [
        'product_name',
        'quantity',
        'status',
        'start_date',
        'due_date',
        'assigned_to_user_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
    ];
}
