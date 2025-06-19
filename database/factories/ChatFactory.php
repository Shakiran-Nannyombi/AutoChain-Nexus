<?php

namespace Database\Factories;

use App\Models\Chat;
use App\Models\User;
use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chat>
 */
class ChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sender = User::inRandomOrder()->first() ?? User::factory()->create();
        $receiver = User::where('id', '!=', $sender->id)->inRandomOrder()->first() ?? User::factory()->create();
        $order = Order::inRandomOrder()->first() ?? Order::factory()->create();

        return [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'order_id' => $order->id,
            'message' => $this->faker->sentence,
            'timestamp' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
