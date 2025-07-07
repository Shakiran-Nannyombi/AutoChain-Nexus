<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RetailerOrder>
 */
class RetailerOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'retailer_id' => \App\Models\User::factory(),
            'customer_name' => $this->faker->name,
            'car_model' => $this->faker->randomElement(['Toyota Corolla', 'Nissan Altima', 'Ford Ranger']),
            'quantity' => $this->faker->numberBetween(1, 10),
        ];
    }
}
