<?php

use App\Models\Event;
use Illuminate\Database\Factory as Factory;
use Faker\Generator as Faker;

$factory->define(Event::class, function (Faker $faker) {
    $categories = ['Technical', 'Cultural', 'Sports', 'Workshops', 'Annual Day', 'Competitions'];
    $statuses = ['pending', 'approved', 'rejected', 'completed'];

    return [
        'organizer_id' => User::inRandomOrder()->first()->id,
        'title' => $faker->sentence(3),
        'slug' => Str::slug($faker->sentence(3)) . '-' . rand(100, 999),
        'description' => $faker->paragraph(2),
        'category' => $faker->randomElement($categories),
        'venue' => $faker->streetAddress,
        'event_date' => $faker->date('m/d/Y', 'now'),
        'start_time' => $faker->time('H:i', 'now'),
        'end_time' => $faker->time('H:i', 'now'),
        'total_seats' => rand(20, 200),
        'seats_booked' => rand(0, 100),
        'waitlist_enabled' => $faker->boolean(0.4),
        'certificate_fee' => $faker->randomElement([null, 100.00, 250.50, 500.00]),
        'status' => $faker->randomElement($statuses),
        'cancellation_reason' => $faker->boolean(0.2) ? $faker->sentence() : null,
        'registration_cutoff' => $faker->boolean(0.7) ? now()->addDays(rand(1, 30))->toDateTimeString() : null,
    ];
});