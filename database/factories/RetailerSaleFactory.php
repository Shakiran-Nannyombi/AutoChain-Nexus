<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RetailerSale>
 */
class RetailerSaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'retailer_id' => \App\Models\User::factory(), // assuming user table has retailers
        'car_model' => $this->faker->randomElement(['Toyota Corolla', 'Mazda CX-5', 'Ford Ranger']),
        'quantity_sold' => $this->faker->numberBetween(1, 5),
        'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
