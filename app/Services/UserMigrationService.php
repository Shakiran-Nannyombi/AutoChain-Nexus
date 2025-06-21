<?php

namespace App\Services;

use App\Models\User;
use App\Models\Manufacturer;
use App\Models\Supplier;
use App\Models\Vendor;
use App\Models\Retailer;
use App\Models\Analyst;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserMigrationService
{
    public function migrateUserToRoleTable(User $user)
    {
        try {
            DB::beginTransaction();

            $roleData = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
                'password' => $user->password,
                'company' => $user->company ?? '',
                'address' => $user->address ?? '',
                'profile_picture' => $user->profile_picture ?? null,
                'supporting_documents' => $user->supporting_documents ?? null,
            ];

            switch ($user->role) {
                case 'admin':
                    $this->migrateToAdmin($roleData);
                    break;
                case 'manufacturer':
                    $this->migrateToManufacturer($roleData);
                    break;
                case 'supplier':
                    $this->migrateToSupplier($roleData);
                    break;
                case 'vendor':
                    $this->migrateToVendor($roleData);
                    break;
                case 'retailer':
                    $this->migrateToRetailer($roleData);
                    break;
                case 'analyst':
                    $this->migrateToAnalyst($roleData);
                    break;
                default:
                    throw new \Exception("Unknown role: {$user->role}");
            }

            // Delete the user from the users table
            $user->delete();

            DB::commit();
            Log::info("User {$user->email} successfully migrated to {$user->role} table");

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to migrate user {$user->email}: " . $e->getMessage());
            throw $e;
        }
    }

    private function migrateToAdmin($data)
    {
        $data['admin_level'] = 'standard';
        $data['permissions'] = ['user_approval', 'user_management', 'view_reports'];
        $data['is_active'] = true;
        Admin::create($data);
    }

    private function migrateToManufacturer($data)
    {
        Manufacturer::create($data);
    }

    private function migrateToSupplier($data)
    {
        Supplier::create($data);
    }

    private function migrateToVendor($data)
    {
        Vendor::create($data);
    }

    private function migrateToRetailer($data)
    {
        Retailer::create($data);
    }

    private function migrateToAnalyst($data)
    {
        Analyst::create($data);
    }

    public function authenticateUser($email, $password, $role)
    {
        $model = $this->getModelForRole($role);
        
        if (!$model) {
            return false;
        }

        $user = $model::where('email', $email)->first();
        
        if (!$user || !password_verify($password, $user->password)) {
            return false;
        }

        // For admin users, check if they are active
        if ($role === 'admin' && !$user->is_active) {
            return false;
        }

        return $user;
    }

    private function getModelForRole($role)
    {
        switch ($role) {
            case 'admin':
                return Admin::class;
            case 'manufacturer':
                return Manufacturer::class;
            case 'supplier':
                return Supplier::class;
            case 'vendor':
                return Vendor::class;
            case 'retailer':
                return Retailer::class;
            case 'analyst':
                return Analyst::class;
            default:
                return null;
        }
    }
} 