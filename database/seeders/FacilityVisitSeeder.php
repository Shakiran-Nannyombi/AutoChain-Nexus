<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FacilityVisit;
use App\Models\User;
use Carbon\Carbon;

class FacilityVisitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FacilityVisit::truncate();

        $vendorUser = User::where('role', 'vendor')->first();
        $manufacturerUser = User::where('role', 'manufacturer')->first();

        if ($vendorUser) {
            FacilityVisit::create([
                'user_id' => $vendorUser->id,
                'company_name' => $vendorUser->company,
                'contact_person' => $vendorUser->name,
                'contact_email' => $vendorUser->email,
                'visit_type' => 'Facility Inspection',
                'purpose' => 'Initial vendor onboarding assessment',
                'location' => $vendorUser->address,
                'visit_date' => Carbon::now()->addDays(10),
                'requested_date' => Carbon::now()->subDays(2),
                'status' => 'pending',
            ]);

            FacilityVisit::create([
                'user_id' => $vendorUser->id,
                'company_name' => $vendorUser->company,
                'contact_person' => $vendorUser->name,
                'contact_email' => $vendorUser->email,
                'visit_type' => 'Compliance Audit',
                'purpose' => 'Quarterly compliance check',
                'location' => $vendorUser->address,
                'visit_date' => Carbon::now()->subDays(30),
                'requested_date' => Carbon::now()->subDays(45),
                'status' => 'completed',
            ]);
        }

        if ($manufacturerUser) {
            FacilityVisit::create([
                'user_id' => $manufacturerUser->id,
                'company_name' => $manufacturerUser->company,
                'contact_person' => $manufacturerUser->name,
                'contact_email' => $manufacturerUser->email,
                'visit_type' => 'Partnership Meeting',
                'purpose' => 'To discuss supply chain partnership',
                'location' => $manufacturerUser->address,
                'visit_date' => Carbon::now()->addDays(5),
                'requested_date' => Carbon::now(),
                'status' => 'pending',
            ]);
        }
    }
}
