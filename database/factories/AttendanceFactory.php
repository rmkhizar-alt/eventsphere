<?php

use App\Models\Attendance;
use Illuminate\Database\Factory as Factory;
use Faker\Generator as Faker;

$factory->define(Attendance::class, function (Faker $faker) {
    return [
        'registration_id' => rand(1, 100),
        'attended' => $faker->boolean(0.6),
        'marked_by' => User::inRandomOrder()->first()->id,
    ];
});