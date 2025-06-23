<?php

namespace Database\Factories;

use App\Models\Prompt;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromptFactory extends Factory
{
    protected $model = Prompt::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 5, 100),
            'category' => $this->faker->randomElement(['Technology', 'Art', 'Writing', 'Business']),
            'image' => $this->faker->uuid,
            'popular' => $this->faker->boolean(20), // 20% chance of being popular
            'rating' => $this->faker->randomFloat(1, 4, 5),
        ];
    }
} 