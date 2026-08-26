<?php

use App\Models\Certificate;
use Illuminate\Database\Factory as Factory;
use Faker\Generator as Faker;

$factory->define(Certificate::class, function (Faker $faker) {
    return [
        'event_id' => rand(1, 20),
        'user_id' => rand(1, 50),
        'fee_paid' => $faker->boolean(0.3),
        'certificate_path' => 'certificates/' . rand(1, 20) . '/' . rand(1000, 9999) . '.pdf',
        'issued_at' => $faker->boolean(0.7) ? now()->subDays(rand(1, 60))->toDateTimeString() : null,
    ];
});