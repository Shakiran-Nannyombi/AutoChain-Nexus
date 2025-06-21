<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\UserMigrationService;

class ApproveUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:approve {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Approve a user and migrate them to their role-specific table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found.");
            return 1;
        }

        if ($user->status === 'approved') {
            $this->info("User {$email} is already approved.");
            return 0;
        }

        if ($user->status === 'rejected') {
            $this->error("User {$email} has been rejected and cannot be approved.");
            return 1;
        }

        try {
            // Update status to approved
            $user->update(['status' => 'approved']);
            
            $this->info("User {$email} has been approved and will be migrated to {$user->role} table on next login.");
            $this->info("User details:");
            $this->table(['Field', 'Value'], [
                ['Name', $user->name],
                ['Email', $user->email],
                ['Role', $user->role],
                ['Company', $user->company],
                ['Status', $user->status],
            ]);
            
            return 0;
        } catch (\Exception $e) {
            $this->error("Failed to approve user: " . $e->getMessage());
            return 1;
        }
    }
}
