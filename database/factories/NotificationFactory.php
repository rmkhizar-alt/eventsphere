<?php

use App\Models\Notification;
use Illuminate\Database\Factory as Factory;
use Faker\Generator as Faker;

$factory->define(Notification::class, function (Faker $faker) {
    $types = ['event', 'system', 'review'];
    $icons = ['alert-circle', 'star', 'megaphone', 'book', 'message-circle', 'calendar'];

    return [
        'user_id' => rand(1, 50),
        'type' => $faker->randomElement($types),
        'icon' => $faker->randomElement($icons),
        'title' => $faker->sentence(3),
        'body' => $faker->sentence(),
        'href' => $faker->optional()->sentence(),
        'is_read' => $faker->boolean(0.5),
    ];
});