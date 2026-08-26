<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $author = User::query()->first() ?? User::factory()->create();

        Post::factory()
            ->count(6)
            ->for($author, 'author')
            ->create();
    }
}
