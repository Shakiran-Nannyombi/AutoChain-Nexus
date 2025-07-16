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

        // Get all approved vendors
        $approvedVendors = User::where('role', 'vendor')->where('status', 'approved')->get();

        if ($approvedVendors->count() > 0) {
            $visitTypes = [
                'Facility Inspection',
                'Compliance Audit', 
                'Quality Assessment',
                'Safety Review',
                'Capacity Evaluation'
            ];

            $purposes = [
                'Initial vendor onboarding assessment',
                'Quarterly compliance check',
                'Quality management system review',
                'Safety protocol verification',
                'Production capacity evaluation'
            ];

            foreach ($approvedVendors as $index => $vendor) {
                // Create one visit per approved vendor
                $visitType = $visitTypes[$index % count($visitTypes)];
                $purpose = $purposes[$index % count($purposes)];
                
                // Mark first 2 as completed, rest as pending
                $status = $index < 2 ? 'completed' : 'pending';
                $visitDate = $status === 'completed' 
                    ? Carbon::now()->subDays(rand(5, 30)) 
                    : Carbon::now()->addDays(rand(1, 14));
                
                FacilityVisit::create([
                    'user_id' => $vendor->id,
                    'company_name' => $vendor->company_name ?? $vendor->name,
                    'contact_person' => $vendor->name,
                    'contact_email' => $vendor->email,
                    'visit_type' => $visitType,
                    'purpose' => $purpose,
                    'location' => $vendor->address ?? 'TBD',
                    'visit_date' => $visitDate,
                    'requested_date' => Carbon::now()->subDays(rand(10, 45)),
                    'status' => $status,
                ]);
            }
        }
    }
}
