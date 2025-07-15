<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChecklistRequest extends Model
{
    protected $fillable = ['manufacturer_id', 'supplier_id', 'materials_requested', 'status'];

    protected $casts = [
        'materials_requested' => 'array',
    ];

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function manufacturer()
    {
        return $this->belongsTo(User::class, 'manufacturer_id');
    }
}
