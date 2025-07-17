<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class VendorProductSeeder extends Seeder
{
    public function run(): void
    {
        // Get all vendors
        $vendors = User::where('role', 'vendor')->where('status', 'active')->get();
        
        if ($vendors->isEmpty()) {
            // Create some vendors if none exist
            $vendors = collect([
                User::create([
                   'name' => 'Amanda Foster',
                    'email' => 'amanda@autochain.com',
                    'password' => Hash::make('password123'),
                   'role' => 'vendor',
               'status' => 'active',
                    'company' => 'AutoChain Nexus',
                    'phone' => '+1-555-0123',
                   'address' => '123 Auto Street, Detroit, MI 48201',
                ]),
                User::create([
                    'name' => 'Michael Chen',
               'email' => 'michael@autochain.com',
                    'password' => Hash::make('password123'),
                   'role' => 'vendor',
               'status' => 'active',
                'company' => 'MotorMax Solutions',
                    'phone' => '+1-555-0124',
                   'address' => '456 Vehicle Ave, Chicago, IL 60601',
                ]),
                User::create([
                  'name' => 'Sarah Johnson',
                   'email' => 'sarah@autochain.com',
                    'password' => Hash::make('password123'),
                   'role' => 'vendor',
               'status' => 'active',
                   'company' => 'CarConnect Pro',
                    'phone' => '+1-555-0125',
                   'address' => '789e Way, Los Angeles, CA 90001',
                ])
            ]);
        }

        // Product data for each vendor
        $products = [
            // Amanda Foster's products
            [
                'name' => 'Toyota Corolla 2024',
                'category' => 'Sedan',
                'price' => 25000.00,
                'stock' => 5,
                'vendor_id' => $vendors[0]->id,
                'manufacturer_id' => null,
            ],
            [
                'name' => 'Honda Civic 2024',
                'category' => 'Sedan',
                'price' => 24000.00,
                'stock' => 2,
                'vendor_id' => $vendors[0]->id,
                'manufacturer_id' => null,
            ],
            [
                'name' => 'Ford F-150 2024',
                'category' => 'Truck',
                'price' => 45000.00,
                'stock' => 8,
                'vendor_id' => $vendors[0]->id,
                'manufacturer_id' => null,
            ],
            [
                'name' => 'BMW 3 Series 2024',
                'category' => 'Luxury Sedan',
                'price' => 45000.00,
                'stock' => 6,
                'vendor_id' => $vendors[0]->id,
                'manufacturer_id' => null,
            ],
            [
                'name' => 'Mercedes-Benz C-Class 2024',
                'category' => 'Luxury Sedan',
                'price' => 48000.00,
                'stock' => 5,
                'vendor_id' => $vendors[0]->id,
                'manufacturer_id' => null,
            ],

            // Michael Chen's products
            [
                'name' => 'Toyota Camry 2024',
                'category' => 'Sedan',
                'price' => 28000.00,
                'stock' => 8,
                'vendor_id' => $vendors[1]->id,
                'manufacturer_id' => null,
            ],
            [
                'name' => 'Honda CR-V 2024',
                'category' => 'SUV',
                'price' => 32000.00,
                'stock' => 4,
                'vendor_id' => $vendors[1]->id,
                'manufacturer_id' => null,
            ],
            [
                'name' => 'Ford Mustang 2024',
                'category' => 'Sports Car',
                'price' => 35000.00,
                'stock' => 0,
                'vendor_id' => $vendors[1]->id,
                'manufacturer_id' => null,
            ],
            [
                'name' => 'BMW X3 2024',
                'category' => 'Luxury SUV',
                'price' => 48000.00,
                'stock' => 7,
                'vendor_id' => $vendors[1]->id,
                'manufacturer_id' => null,
            ],
            [
                'name' => 'Volkswagen Golf 2024',
                'category' => 'Hatchback',
                'price' => 25000.00,
                'stock' => 9,
                'vendor_id' => $vendors[1]->id,
                'manufacturer_id' => null,
            ],

            // Sarah Johnson's products
            [
                'name' => 'Toyota RAV4 2024',
                'category' => 'SUV',
                'price' => 32000.00,
                'stock' => 6,
                'vendor_id' => $vendors[2]->id,
                'manufacturer_id' => null,
            ],
            [
                'name' => 'Honda Accord 2024',
                'category' => 'Sedan',
                'price' => 27000.00,
                'stock' => 3,
                'vendor_id' => $vendors[2]->id,
                'manufacturer_id' => null,
            ],
            [
                'name' => 'Ford Explorer 2024',
                'category' => 'SUV',
                'price' => 38000.00,
                'stock' => 1,
                'vendor_id' => $vendors[2]->id,
                'manufacturer_id' => null,
            ],
            [
                'name' => 'BMW 5 Series 2024',
                'category' => 'Luxury Sedan',
                'price' => 55000.00,
                'stock' => 4,
                'vendor_id' => $vendors[2]->id,
                'manufacturer_id' => null,
            ],
            [
                'name' => 'Mercedes-Benz GLC 2024',
                'category' => 'Luxury SUV',
                'price' => 52000.00,
                'stock' => 6,
                'vendor_id' => $vendors[2]->id,
                'manufacturer_id' => null,
            ],
        ];

        // Create products
        foreach ($products as $productData) {
            $product = Product::create($productData);
            $this->command->info("Created product: {$product->name} for vendor: " . User::find($product->vendor_id)->name);
        }

        $this->command->info('Vendor products seeded successfully!');
        $this->command->info('Created ' . count($products) . ' products for ' . $vendors->count() . ' vendors.');
    }
}
