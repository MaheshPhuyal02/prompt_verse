<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\User;
use App\Models\Prompt;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition(): array
    {
        // To avoid creating a new prompt for every purchase, we get a random one if it exists.
        $prompt = Prompt::inRandomOrder()->first();
        if (!$prompt) {
            $prompt = Prompt::factory()->create();
        }

        $user = User::inRandomOrder()->first();
        if (!$user) {
            $user = User::factory()->create();
        }

        return [
            'user_id' => $user->id,
            'prompt_id' => $prompt->id,
            'price_at_time' => $prompt->price,
            'purchased_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'status' => $this->faker->randomElement(['completed', 'pending', 'failed', 'refunded']),
            'payment_id' => 'khalti:' . $this->faker->uuid,
            'payment_method' => 'khalti',
            'transaction_id' => $this->faker->uuid,
        ];
    }
}
