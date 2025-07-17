<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Analyst extends Authenticatable
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
        'analyst_certification',
        'specialization_areas',
        'research_methodologies',
        'reporting_capabilities',
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

    public function manufacturer()
    {
        return $this->hasOneThrough(
            Manufacturer::class,
            'analyst_manufacturer',
            'analyst_id', // Foreign key on analyst_manufacturer
            'user_id',    // Foreign key on manufacturers
            'user_id',    // Local key on analysts
            'manufacturer_id' // Local key on analyst_manufacturer
        );
    }

    public function manufacturers()
    {
        return $this->belongsToMany(
            Manufacturer::class,
            'analyst_manufacturer',
            'analyst_id', // Foreign key on analyst_manufacturer
            'manufacturer_id', // Foreign key on analyst_manufacturer
            'user_id', // Local key on analysts
            'user_id'  // Local key on manufacturers
        )->withPivot('status')->withTimestamps();
    }
}
