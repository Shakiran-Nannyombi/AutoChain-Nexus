<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierStock extends Model
{
    protected $fillable = ['supplier_id', 'material_name', 'quantity', 'colour'];

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

use HasFactory;

}
