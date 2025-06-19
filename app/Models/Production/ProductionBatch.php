<?php

namespace App\Models\Production;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Warehouse\Car;

class ProductionBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'manufacturer_id',
        'car_name',
        'model',
        'current_stage',
        'progress',
        'efficiency',
        'status',
        'is_completed',
    ];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}
