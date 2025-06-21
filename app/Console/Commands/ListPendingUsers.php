<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ListPendingUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:list-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all pending users for approval';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pendingUsers = User::where('status', 'pending')->get();

        if ($pendingUsers->isEmpty()) {
            $this->info('No pending users found.');
            return 0;
        }

        $this->info("Found {$pendingUsers->count()} pending user(s):\n");

        $headers = ['ID', 'Name', 'Email', 'Role', 'Company', 'Phone', 'Created'];
        $rows = [];

        foreach ($pendingUsers as $user) {
            $rows[] = [
                $user->id,
                $user->name,
                $user->email,
                ucfirst($user->role),
                $user->company,
                $user->phone,
                $user->created_at->format('M d, Y'),
            ];
        }

        $this->table($headers, $rows);

        $this->info("\nTo approve a user, use: php artisan user:approve {email}");
        $this->info("Example: php artisan user:approve {$pendingUsers->first()->email}");

        return 0;
    }
}
