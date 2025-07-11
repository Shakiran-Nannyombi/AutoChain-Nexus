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
        ValidationRule::updateOrCreate([
            'name' => 'ISO Certification Required',
            'category' => 'Compliance',
            'value' => 'true'
        ], [
            'description' => 'Vendors must have at least one valid ISO certification (e.g., ISO 9001, ISO 14001, ISO 27001) to pass compliance validation.',
            'status' => 'Active',
        ]);

        ValidationRule::updateOrCreate([
            'name' => 'Allowed File Formats',
            'category' => 'Document Validation',
            'value' => 'pdf,doc,docx,jpg,jpeg,png'
        ], [
            'description' => 'Only PDF, DOC, DOCX, JPG, JPEG, and PNG files are accepted for vendor document uploads.',
            'status' => 'Active',
        ]);

        ValidationRule::updateOrCreate([
            'name' => 'Minimum Financial Score',
            'category' => 'Financial',
            'value' => '25'
        ], [
            'description' => 'Vendors must achieve a financial score of at least 25 to pass financial validation.',
            'status' => 'Active',
        ]);

        ValidationRule::updateOrCreate([
            'name' => 'Minimum Reputation Score',
            'category' => 'Reputation',
            'value' => '20'
        ], [
            'description' => 'Vendors must achieve a reputation score of at least 20, based on years in business, customer reviews, and industry ranking.',
            'status' => 'Active',
        ]);

        ValidationRule::updateOrCreate([
            'name' => 'Minimum Years in Business',
            'category' => 'Financial',
            'value' => '3'
        ], [
            'description' => 'Vendors must have been in business for at least 3 years.',
            'status' => 'Active',
        ]);

        ValidationRule::updateOrCreate([
            'name' => 'Minimum Revenue',
            'category' => 'Financial',
            'value' => '500000'
        ], [
            'description' => 'Vendors must have an annual revenue of at least 500,000 shs.',
            'status' => 'Active',
        ]);

        ValidationRule::updateOrCreate([
            'name' => 'Minimum Employee Count',
            'category' => 'Financial',
            'value' => '10'
        ], [
            'description' => 'Vendors must have at least 10 employees.',
            'status' => 'Active',
        ]);
    }
}
