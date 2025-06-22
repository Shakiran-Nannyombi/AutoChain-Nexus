<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ValidationRule;

class ValidationRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ValidationRule::create([
            'name' => 'Allowed File Formats',
            'category' => 'Document Validation',
            'description' => 'Specifies the allowed file formats for vendor document uploads. Only PDF, DOC, DOCX, JPG, JPEG, and PNG files are accepted for validation.',
            'value' => 'pdf,doc,docx,jpg,jpeg,png',
            'status' => 'active',
        ]);

        // Add some additional common validation rules for testing
        ValidationRule::create([
            'name' => 'Financial Score Threshold',
            'category' => 'Financial',
            'description' => 'Minimum financial score required for vendor validation. Vendors must achieve this score or higher to pass financial validation.',
            'value' => '70',
            'status' => 'active',
        ]);

        ValidationRule::create([
            'name' => 'Reputation Score Threshold',
            'category' => 'Reputation',
            'description' => 'Minimum reputation score required for vendor validation. Based on years in business, customer reviews, and industry ranking.',
            'value' => '60',
            'status' => 'active',
        ]);

        ValidationRule::create([
            'name' => 'ISO Certification Required',
            'category' => 'Compliance',
            'description' => 'Vendors must have at least one ISO certification (ISO 9001, ISO 14001, etc.) to pass compliance validation.',
            'value' => 'ISO',
            'status' => 'active',
        ]);
    }
}
