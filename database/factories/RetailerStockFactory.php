<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RetailerStock>
 */
class RetailerStockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'retailer_id' => \App\Models\User::factory(), // assuming user with role 'retailer'
            'vendor_name' => $this->faker->company,
            'car_model' => $this->faker->randomElement(['Toyota Camry', 'Honda Civic', 'BMW X5']),
            'quantity_received' => $this->faker->numberBetween(1, 50),
            'status' => $this->faker->randomElement(['pending', 'accepted', 'rejected']),
        ];
    }
}
