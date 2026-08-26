<?php

use App\Models\SavedMedia;
use Illuminate\Database\Factory as Factory;
use Faker\Generator as Faker;

$factory->define(SavedMedia::class, function (Faker $faker) {
    return [
        'user_id' => rand(1, 50),
        'media_id' => rand(1, 100),
    ];
});