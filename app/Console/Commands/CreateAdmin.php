<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {name} {email} {password} {--level=standard} {--department=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user directly in the admins table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');
        $level = $this->option('level');
        $department = $this->option('department');

        // Check if admin already exists
        if (Admin::where('email', $email)->exists()) {
            $this->error("Admin with email {$email} already exists.");
            return 1;
        }

        try {
            $admin = Admin::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'admin_level' => $level,
                'department' => $department,
                'permissions' => $this->getDefaultPermissions($level),
                'is_active' => true,
            ]);

            $this->info("Admin user created successfully!");
            $this->table(['Field', 'Value'], [
                ['Name', $admin->name],
                ['Email', $admin->email],
                ['Admin Level', $admin->admin_level],
                ['Department', $admin->department ?? 'N/A'],
                ['Permissions', implode(', ', $admin->permissions)],
                ['Status', $admin->is_active ? 'Active' : 'Inactive'],
            ]);

            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to create admin: " . $e->getMessage());
            return 1;
        }
    }

    private function getDefaultPermissions($level)
    {
        $basePermissions = ['user_approval', 'user_management', 'view_reports'];
        
        switch ($level) {
            case 'super':
                return array_merge($basePermissions, [
                    'system_administration',
                    'role_management',
                    'database_management',
                    'security_settings',
                    'create_admins',
                    'delete_users',
                    'system_configuration'
                ]);
            case 'senior':
                return array_merge($basePermissions, [
                    'system_administration',
                    'role_management',
                    'create_admins'
                ]);
            case 'standard':
            default:
                return $basePermissions;
        }
    }
}
