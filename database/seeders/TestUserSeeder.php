<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class TestUserSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('email', 'test@example.com')->first();
        
        if (!$user) {
            $user = new User();
            $user->name = 'Test Vendor';
            $user->email = 'test@example.com';
            $user->password = bcrypt('password');
            $user->role = 'vendor';
            $user->status = 'pending';
            $user->company = 'Test Company Ltd';
            $user->phone = '+1234567890';
            $user->address = '123 Test Street, Test City';
            $user->save();
        }

        // Add comprehensive validation data
        $user->validation_score = 85;
        $user->financial_score = 25;
        $user->reputation_score = 20;
        $user->compliance_score = 22;
        $user->profile_score = 13;
        $user->validated_at = now();
        $user->auto_visit_scheduled = true;
        $user->extracted_data = json_encode([
            'financial_data' => [
                'years_in_business' => '15',
                'annual_revenue' => '$5,000,000',
                'employee_count' => '150',
                'financial_stability' => 'Excellent'
            ],
            'reputation_data' => [
                'industry_awards' => '3',
                'customer_rating' => '4.8/5',
                'industry_membership' => 'Active',
                'certifications' => 'ISO 9001, ISO 14001'
            ],
            'compliance_data' => [
                'regulatory_compliance' => 'Fully Compliant',
                'licenses' => 'Business License, Tax ID',
                'insurance_coverage' => '$2,000,000',
                'safety_record' => 'No violations'
            ]
        ]);
        $user->save();

        $this->command->info('Test user created/updated with comprehensive validation data');
    }
} 