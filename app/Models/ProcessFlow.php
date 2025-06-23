<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessFlow extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'current_stage',
        'status',
        'entered_stage_at',
        'completed_stage_at',
        'failure_reason',
    ];
} 