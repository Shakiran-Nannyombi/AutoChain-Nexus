<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendorSeeder extends Seeder
{
    public function run()
    {
        DB::table('vendors')->truncate();
        $approvedVendors = DB::table('users')->where('role', 'vendor')->where('status', 'approved')->get();
        foreach ($approvedVendors as $user) {
            DB::table('vendors')->insert([
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'password' => $user->password,
                'company' => $user->company,
                'address' => $user->address,
                'profile_picture' => $user->profile_photo,
                'supporting_documents' => $user->supporting_documents,
                'vendor_license' => null,
                'product_categories' => null,
                'service_areas' => null,
                'contract_terms' => null,
                'segment' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 