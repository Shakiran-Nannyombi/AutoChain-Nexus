<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Production\ProductionBatch;

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
