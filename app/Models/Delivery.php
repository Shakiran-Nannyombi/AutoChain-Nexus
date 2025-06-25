<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = ['supplier_id', 'manufacturer_id', 'materials_delivered'];

    protected $casts = [
        'materials_delivered' => 'array',
    ];
}
