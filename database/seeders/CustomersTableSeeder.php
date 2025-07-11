<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;

class CustomersTableSeeder extends Seeder
{
    public function run()
    {
        Customer::insert([
            [ 'name' => 'Auto World Ltd.', 'email' => 'contact@autoworld.com', 'phone' => '0700123456', 'address' => '101 Motorway Ave', 'segment' => null ],
            [ 'name' => 'CarZone Dealers', 'email' => 'info@carzone.com', 'phone' => '0700234567', 'address' => '202 Highway Blvd', 'segment' => null ],
            [ 'name' => 'Speedy Repairs', 'email' => 'service@speedyrepairs.com', 'phone' => '0700345678', 'address' => '303 Garage Rd', 'segment' => null ],
            [ 'name' => 'Luxury Motors', 'email' => 'luxury@motors.com', 'phone' => '0700456789', 'address' => '404 Elite Ave', 'segment' => null ],
            [ 'name' => 'Budget Cars', 'email' => 'budget@cars.com', 'phone' => '0700567890', 'address' => '505 Saver St', 'segment' => null ],
            [ 'name' => 'Fleet Solutions', 'email' => 'fleet@solutions.com', 'phone' => '0700678901', 'address' => '606 Fleet Rd', 'segment' => null ],
            [ 'name' => 'Quick Fix Garage', 'email' => 'quickfix@garage.com', 'phone' => '0700789012', 'address' => '707 Quick St', 'segment' => null ],
            [ 'name' => 'Elite Auto', 'email' => 'elite@auto.com', 'phone' => '0700890123', 'address' => '808 Elite Blvd', 'segment' => null ],
            [ 'name' => 'Urban Motors', 'email' => 'urban@motors.com', 'phone' => '0700901234', 'address' => '909 Urban Ave', 'segment' => null ],
            [ 'name' => 'Classic Cars', 'email' => 'classic@cars.com', 'phone' => '0701012345', 'address' => '1010 Classic Rd', 'segment' => null ],
        ]);
    }
} 