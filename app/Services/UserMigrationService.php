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
    public function migrateUserToRoleTable(User $user, $roleSpecificData = [])
    {
        try {
            DB::beginTransaction();

            // Update user status and role
            $user->status = 'approved';
            $user->role = $roleSpecificData['role'] ?? $user->role;
            $user->save();

            // Create related record in the appropriate role table if it doesn't exist
            switch ($user->role) {
                case 'manufacturer':
                    if (!$user->manufacturer) {
                        $user->manufacturer()->create([
                            'manufacturing_license' => 'LIC-2024-001',
                            'quality_certification' => 'ISO 9001:2015',
                            'production_capacity' => '5000 units per month',
                            'specializations' => 'Automotive Manufacturing, Quality Control',
                        ]);
                    }
                    break;
                case 'supplier':
                    if (!$user->supplier) {
                        $user->supplier()->create([
                            'name' => $user->name,
                            'email' => $user->email,
                            'phone' => $user->phone,
                            'password' => $user->password,
                            'company' => $user->company,
                            'address' => $user->address,
                            'profile_picture' => $user->profile_photo,
                            'supplier_license' => 'SUP-2024-001',
                            'supply_categories' => 'Raw Materials, Components',
                            'delivery_capabilities' => 'Next Day Delivery, Express Shipping',
                            'payment_terms' => 'Net 30',
                        ]);
                    }
                    break;
                case 'vendor':
                    if (!$user->vendor) {
                        $user->vendor()->create([
                            'name' => $user->name,
                            'email' => $user->email,
                            'phone' => $user->phone,
                            'password' => $user->password,
                            'company' => $user->company,
                            'address' => $user->address,
                            'profile_picture' => $user->profile_photo,
                            'vendor_license' => 'VEN-2024-001',
                            'product_categories' => 'Automotive Parts, Accessories',
                            'service_areas' => 'Local, Regional, National',
                            'contract_terms' => 'Annual Contract',
                        ]);
                    }
                    break;
                case 'retailer':
                    if (!$user->retailer) {
                        $user->retailer()->create([
                            'name' => $user->name,
                            'email' => $user->email,
                            'phone' => $user->phone,
                            'password' => $user->password,
                            'company' => $user->company,
                            'address' => $user->address,
                            'profile_picture' => $user->profile_photo,
                            'retail_license' => 'RET-2024-001',
                            'store_locations' => 'Multiple locations across the city',
                            'product_inventory' => 'Automotive parts and accessories',
                            'business_hours' => 'Mon-Fri: 9AM-6PM, Sat: 10AM-4PM',
                        ]);
                    }
                    break;
                case 'analyst':
                    if (!$user->analyst) {
                        $user->analyst()->create([
                            'analyst_certification' => 'Certified Business Analyst',
                            'specialization_areas' => 'Inventory Management, Supply Chain Analysis',
                            'research_methodologies' => 'Quantitative Analysis, Market Research',
                            'reporting_capabilities' => 'Advanced Analytics, Data Visualization',
                        ]);
                    }
                    break;
                case 'admin':
                    // Handle admin-specific logic if needed
                    break;
                default:
                    throw new \Exception("Unknown role: {$user->role}");
            }

            DB::commit();
            Log::info("User {$user->email} approved and linked to {$user->role} table");
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to approve user {$user->email}: " . $e->getMessage());
            throw $e;
        }
    }

    public function authenticateUser($email, $password, $role)
    {
        if ($role === 'admin') {
            // For admin users, authenticate against the Admin model
            $admin = Admin::where('email', $email)->first();
        
            if (!$admin || !password_verify($password, $admin->password)) {
                return false;
            }

            // Check if admin is active
            if (!$admin->is_active) {
            return false;
            }

            return $admin;
        }

        // For non-admin users, find the user in the users table
        $user = User::where('email', $email)->first();
        
        if (!$user || !password_verify($password, $user->password)) {
            return false;
        }

        // Check if the user has the correct role
        if ($user->role !== $role) {
            return false;
        }

        // Check if the user is approved
        if ($user->status !== 'approved') {
            return false;
        }

        // Ensure the user has a record in the appropriate role table
        $this->ensureRoleRecordExists($user, $role);

        return $user;
    }

    private function ensureRoleRecordExists(User $user, $role)
    {
        switch ($role) {
            case 'manufacturer':
                if (!$user->manufacturer) {
                    $user->manufacturer()->create([
                        'manufacturing_license' => 'LIC-2024-001',
                        'quality_certification' => 'ISO 9001:2015',
                        'production_capacity' => '5000 units per month',
                        'specializations' => 'Automotive Manufacturing, Quality Control',
                    ]);
                }
                break;
            case 'supplier':
                if (!$user->supplier) {
                    $user->supplier()->create([
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'password' => $user->password,
                        'company' => $user->company,
                        'address' => $user->address,
                        'profile_picture' => $user->profile_photo,
                        'supplier_license' => 'SUP-2024-001',
                        'supply_categories' => 'Raw Materials, Components',
                        'delivery_capabilities' => 'Next Day Delivery, Express Shipping',
                        'payment_terms' => 'Net 30',
                    ]);
                }
                break;
            case 'vendor':
                if (!$user->vendor) {
                    $user->vendor()->create([
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'password' => $user->password,
                        'company' => $user->company,
                        'address' => $user->address,
                        'profile_picture' => $user->profile_photo,
                        'vendor_license' => 'VEN-2024-001',
                        'product_categories' => 'Automotive Parts, Accessories',
                        'service_areas' => 'Local, Regional, National',
                        'contract_terms' => 'Annual Contract',
                    ]);
                }
                break;
            case 'retailer':
                if (!$user->retailer) {
                    $user->retailer()->create([
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'password' => $user->password,
                        'company' => $user->company,
                        'address' => $user->address,
                        'profile_picture' => $user->profile_photo,
                        'retail_license' => 'RET-2024-001',
                        'store_locations' => 'Multiple locations across the city',
                        'product_inventory' => 'Automotive parts and accessories',
                        'business_hours' => 'Mon-Fri: 9AM-6PM, Sat: 10AM-4PM',
                    ]);
                }
                break;
            case 'analyst':
                if (!$user->analyst) {
                    $user->analyst()->create([
                        'analyst_certification' => 'Certified Business Analyst',
                        'specialization_areas' => 'Inventory Management, Supply Chain Analysis',
                        'research_methodologies' => 'Quantitative Analysis, Market Research',
                        'reporting_capabilities' => 'Advanced Analytics, Data Visualization',
                    ]);
                }
                break;
            case 'admin':
                // Admin users don't need a separate record
                break;
        }
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