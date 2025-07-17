<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'segment' => $this->faker->randomElement([1, 2, 3]), // 1: Occasional, 2: High Value, 3: At Risk
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
} 