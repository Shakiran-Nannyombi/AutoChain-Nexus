<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Delivery extends Model
{
    protected $fillable = ['supplier_id', 'manufacturer_id', 'materials_delivered'];

    protected $casts = [
        'materials_delivered' => 'array',
    ];

    use HasFactory;

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }
}
