<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Vendor extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'company',
        'address',
        'profile_picture',
        'supporting_documents',
        'vendor_license',
        'product_categories',
        'service_areas',
        'contract_terms',
        'segment', // Vendor segmentation
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'supporting_documents' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
