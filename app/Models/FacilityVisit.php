<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'contact_person',
        'contact_email',
        'visit_type',
        'purpose',
        'location',
        'visit_date',
        'requested_date',
        'status',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
        'requested_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
