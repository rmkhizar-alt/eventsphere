<?php

use App\Models\Feedback;
use Illuminate\Database\Factory as Factory;
use Faker\Generator as Faker;

$factory->define(Feedback::class, function (Faker $faker) {
    return [
        'event_id' => rand(1, 20),
        'user_id' => rand(1, 50),
        'rating' => rand(1, 5),
        'venue_rating' => rand(1, 5),
        'coordination_rating' => rand(1, 5),
        'technical_rating' => rand(1, 5),
        'hospitality_rating' => rand(1, 5),
        'comments' => $faker->sentence(),
    ];
});