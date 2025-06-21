<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
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
        'admin_level',
        'permissions',
        'department',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'supporting_documents' => 'array',
            'permissions' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function hasPermission($permission)
    {
        if (!$this->permissions) {
            return false;
        }
        
        return in_array($permission, $this->permissions);
    }

    public function isSuperAdmin()
    {
        return $this->admin_level === 'super';
    }

    public function isSeniorAdmin()
    {
        return $this->admin_level === 'senior' || $this->admin_level === 'super';
    }
}
