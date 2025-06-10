<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'company_name',
        'company_address',
        'role',
        'rejection_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the documents associated with the user.
     */
    public function documents()
    {
        return $this->hasMany(UserDocument::class);
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is approved.
     */
    public function isApproved()
    {
        // Since the status column is removed, we can assume a user is approved if they are not an admin
        // or if they are verified. Adjust this logic as per your application's approval flow.
        return true; // For now, assuming all users are approved unless explicitly handled elsewhere
    }

    /**
     * Check if the user is pending.
     */
    public function isPending()
    {
        // The 'status' column has been removed. For profile icon purposes, we will return false.
        // If there's another way to determine pending status (e.g., email not verified), it needs to be implemented here.
        return false;
    }
}
