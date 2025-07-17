<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition()
    {
        return [
            'customer_id' => null, // Set in seeder
            'product_id' => Product::inRandomOrder()->first()?->id ?? 1,
            'amount' => $this->faker->randomFloat(2, 20, 2000),
            'purchase_date' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
} 