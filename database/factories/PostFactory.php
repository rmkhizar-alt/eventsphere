<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'slug' => str($title)->slug(),
            'excerpt' => fake()->sentence(12),
            'body' => fake()->paragraphs(4, true),
            'published_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
