<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Warehouse\Car;

class ProductionStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_batch_id',
        'stage_name',
        'target_defect_rate',
        'current_defect_rate',
        'status',
        'started_at',
        'ended_at',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
