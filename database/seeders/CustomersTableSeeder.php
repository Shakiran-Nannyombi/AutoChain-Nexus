<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomersTableSeeder extends Seeder
{
    public function run()
    {
        Customer::insert([
            [ 'name' => 'Auto World Ltd.', 'email' => 'contact@autoworld.com', 'phone' => '0700123456', 'address' => '101 Motorway Ave', 'segment' => 2 ], // High Value
            [ 'name' => 'CarZone Dealers', 'email' => 'info@carzone.com', 'phone' => '0700234567', 'address' => '202 Highway Blvd', 'segment' => 1 ], // Occasional
            [ 'name' => 'Speedy Repairs', 'email' => 'service@speedyrepairs.com', 'phone' => '0700345678', 'address' => '303 Garage Rd', 'segment' => 3 ], // At Risk
            [ 'name' => 'Luxury Motors', 'email' => 'luxury@motors.com', 'phone' => '0700456789', 'address' => '404 Elite Ave', 'segment' => 2 ], // High Value
            [ 'name' => 'Budget Cars', 'email' => 'budget@cars.com', 'phone' => '0700567890', 'address' => '505 Saver St', 'segment' => 1 ], // Occasional
            [ 'name' => 'Fleet Solutions', 'email' => 'fleet@solutions.com', 'phone' => '0700678901', 'address' => '606 Fleet Rd', 'segment' => 3 ], // At Risk
            [ 'name' => 'Quick Fix Garage', 'email' => 'quickfix@garage.com', 'phone' => '0700789012', 'address' => '707 Quick St', 'segment' => 2 ], // High Value
            [ 'name' => 'Elite Auto', 'email' => 'elite@auto.com', 'phone' => '0700890123', 'address' => '808 Elite Blvd', 'segment' => 1 ], // Occasional
            [ 'name' => 'Urban Motors', 'email' => 'urban@motors.com', 'phone' => '0700901234', 'address' => '909 Urban Ave', 'segment' => 3 ], // At Risk
            [ 'name' => 'Classic Cars', 'email' => 'classic@cars.com', 'phone' => '0701012345', 'address' => '1010 Classic Rd', 'segment' => 2 ], // High Value
        ]);
    }
} 