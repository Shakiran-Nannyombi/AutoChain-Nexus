<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'activity',
        'details',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
} 