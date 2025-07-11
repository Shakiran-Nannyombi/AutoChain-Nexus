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
            'name' => 'ISO Certification Required',
            'category' => 'Compliance',
            'description' => 'Vendors must have at least one valid ISO certification (e.g., ISO 9001, ISO 14001, ISO 27001) to pass compliance validation.',
            'value' => 'true',
            'status' => 'Active',
        ]);

        ValidationRule::create([
            'name' => 'Allowed File Formats',
            'category' => 'Document Validation',
            'description' => 'Only PDF, DOC, DOCX, JPG, JPEG, and PNG files are accepted for vendor document uploads.',
            'value' => 'pdf,doc,docx,jpg,jpeg,png',
            'status' => 'Active',
        ]);

        ValidationRule::create([
            'name' => 'Minimum Financial Score',
            'category' => 'Financial',
            'description' => 'Vendors must achieve a financial score of at least 25 to pass financial validation.',
            'value' => '25',
            'status' => 'Active',
        ]);

        ValidationRule::create([
            'name' => 'Minimum Reputation Score',
            'category' => 'Reputation',
            'description' => 'Vendors must achieve a reputation score of at least 20, based on years in business, customer reviews, and industry ranking.',
            'value' => '20',
            'status' => 'Active',
        ]);

        ValidationRule::create([
            'name' => 'Minimum Years in Business',
            'category' => 'Financial',
            'description' => 'Vendors must have been in business for at least 3 years.',
            'value' => '3',
            'status' => 'Active',
        ]);

        ValidationRule::create([
            'name' => 'Minimum Revenue',
            'category' => 'Financial',
            'description' => 'Vendors must have an annual revenue of at least 500,000 shs.',
            'value' => '500000',
            'status' => 'Active',
        ]);

        ValidationRule::create([
            'name' => 'Minimum Employee Count',
            'category' => 'Financial',
            'description' => 'Vendors must have at least 10 employees.',
            'value' => '10',
            'status' => 'Active',
        ]);
    }
}
