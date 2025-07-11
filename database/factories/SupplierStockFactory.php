<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SupplierStock>
 */
class SupplierStockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'supplier_id' => \App\Models\User::factory(), // assuming user with role 'supplier'
            'material_name' => $this->faker->randomElement(['Steel', 'Rubber', 'Glass', 'Paint']),
            'quantity' => $this->faker->numberBetween(100, 1000),
            'colour' => $this->faker->safeColorName,
        ];
    }
}
