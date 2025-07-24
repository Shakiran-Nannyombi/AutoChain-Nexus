<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Delivery>
 */
class DeliveryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'supplier_id' => \App\Models\User::factory(),
            'manufacturer_id' => \App\Models\User::factory(),
            'materials_delivered' => [
                'Steel' => $this->faker->numberBetween(10, 100),
                'Rubber' => $this->faker->numberBetween(5, 50)
            ],
            'status' => $this->faker->randomElement(['pending', 'in_transit', 'out_for_delivery', 'delivered']),
            'driver' => $this->faker->name,
            'destination' => $this->faker->address,
            'progress' => $this->faker->numberBetween(0, 100),
            'eta' => $this->faker->dateTimeBetween('now', '+7 days')
        ];
    }
}
