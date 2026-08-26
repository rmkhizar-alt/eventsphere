<?php

use App\Models\Bookmark;
use Illuminate\Database\Factory as Factory;
use Faker\Generator as Faker;

$factory->define(Bookmark::class, function (Faker $faker) {
    return [
        'user_id' => rand(1, 50),
        'event_id' => rand(1, 20),
    ];
});