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
        $prompt = Prompt::factory()->create();

        return [
            'user_id' => User::factory(),
            'prompt_id' => $prompt->id,
            'purchase_price' => $prompt->price, // Use current prompt price
            'purchase_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'prompt_snapshot' => [
                'title' => $prompt->title,
                'description' => $prompt->description,
                'category' => $prompt->category,
                'image' => $prompt->image,
                'rating' => $prompt->rating,
                'popular' => $prompt->popular,
            ],
            'status' => $this->faker->randomElement(['active', 'active', 'active', 'inactive']), // More active purchases
        ];
    }

    /**
     * Indicate that the purchase is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the purchase is refunded.
     */
    public function refunded(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'refunded',
        ]);
    }

    /**
     * Create a purchase for an existing prompt.
     */
    public function forPrompt(Prompt $prompt): static
    {
        return $this->state(fn (array $attributes) => [
            'prompt_id' => $prompt->id,
            'purchase_price' => $prompt->price,
            'prompt_snapshot' => [
                'title' => $prompt->title,
                'description' => $prompt->description,
                'category' => $prompt->category,
                'image' => $prompt->image,
                'rating' => $prompt->rating,
                'popular' => $prompt->popular,
            ],
        ]);
    }

    /**
     * Create a purchase with a specific category.
     */
    public function category(string $category): static
    {
        return $this->state(function (array $attributes) use ($category) {
            $prompt = Prompt::factory()->create(['category' => $category]);

            return [
                'prompt_id' => $prompt->id,
                'purchase_price' => $prompt->price,
                'prompt_snapshot' => [
                    'title' => $prompt->title,
                    'description' => $prompt->description,
                    'category' => $prompt->category,
                    'image' => $prompt->image,
                    'rating' => $prompt->rating,
                    'popular' => $prompt->popular,
                ],
            ];
        });
    }
}
