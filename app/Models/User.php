<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\UserResetPasswordNotification;

/**
 * @property \Illuminate\Notifications\DatabaseNotificationCollection $notifications
 * @method \Illuminate\Notifications\DatabaseNotificationCollection unreadNotifications()
 * @method \Illuminate\Notifications\DatabaseNotificationCollection notifications()
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'phone',
        'address',
        'company',
        'validation_score',
        'financial_score',
        'reputation_score',
        'compliance_score',
        'profile_score',
        'extracted_data',
        'validated_at',
        'auto_visit_scheduled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'extracted_data' => 'array',
        'validated_at' => 'datetime',
        'auto_visit_scheduled' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function documents()
    {
        return $this->hasMany(UserDocument::class);
    }

    public function profilePicture()
    {
        return $this->documents()->where('document_type', 'profile_picture')->first();
    }

    public function supportingDocuments()
    {
        return $this->hasMany(UserDocument::class)->where('document_type', 'supporting_document');
    }

    public function facilityVisits()
    {
        return $this->hasMany(FacilityVisit::class);
    }

    public function manufacturer()
    {
        return $this->hasOne(Manufacturer::class);
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class);
    }

    public function vendor()
    {
        return $this->hasOne(Vendor::class);
    }

    public function retailer()
    {
        return $this->hasOne(Retailer::class);
    }

    public function analyst()
    {
        return $this->hasOne(Analyst::class);
    }

    public function sentCommunications()
    {
        return $this->hasMany(Communication::class, 'sender_id');
    }

    public function receivedCommunications()
    {
        return $this->hasMany(Communication::class, 'receiver_id');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserResetPasswordNotification($token));
    }

    public function getProfilePhotoPathAttribute()
    {
        $doc = $this->documents()->where('document_type', 'profile_picture')->latest()->first();
        if ($doc) {
            return $doc->file_path;
        }
        return $this->profile_photo ?? null;
    }
}
