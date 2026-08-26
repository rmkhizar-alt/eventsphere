<?php

use App\Models\Registration;
use Illuminate\Database\Factory as Factory;
use Faker\Generator as Faker;

$factory->define(Registration::class, function (Faker $faker) {
    $statuses = ['confirmed', 'waitlisted', 'cancelled'];
    $statusIndex = rand(0, 2);
    $qrTokenIndex = $statusIndex === 0 ? rand(100000, 999999) : rand(10000, 99999);

    return [
        'event_id' => Event::inRandomOrder()->first()->id,
        'user_id' => User::inRandomOrder()->first()->id,
        'status' => $statuses[$statusIndex],
        'qr_token' => 'qr_' . str_pad($qrTokenIndex, 6, '0', STR_PAD_LEFT) . '_' . rand(1000, 9999),
        'registered_at' => now()->subDays(rand(0, 30)),
        'cancelled_at' => $statusIndex === 2 ? now()->subDays(rand(1, 15)) : null,
    ];
});