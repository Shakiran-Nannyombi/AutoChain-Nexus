<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\RetailerOrderSeeder;

class SeedRetailerOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:retailer-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed retailer orders for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Seeding retailer orders...');
        
        // Run the RetailerOrderSeeder
        $seeder = new RetailerOrderSeeder();
        $seeder->setContainer(app())->setCommand($this);
        $seeder->run();
        
        $this->info('Retailer orders seeded successfully!');
    }
}