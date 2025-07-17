<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ManufacturerSeeder extends Seeder
{
    public function run()
    {
        DB::table('manufacturers')->truncate();
        $manufacturers = [
            [
                'user_id' => 22,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 23,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 24,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 25,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 26,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 27,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 28,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 29,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 31,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 32,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 33,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 34,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 35,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 36,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 37,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 38,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 39,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 40,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 41,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        DB::table('manufacturers')->insert($manufacturers);
    }
} 