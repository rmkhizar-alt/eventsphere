<?php

use App\Models\MediaGallery;
use Illuminate\Database\Factory as Factory;
use Faker\Generator as Faker;

$factory->define(MediaGallery::class, function (Faker $faker) {
    $fileTypes = ['image', 'video'];

    return [
        'event_id' => rand(1, 20),
        'uploaded_by' => rand(1, 50),
        'file_type' => $faker->randomElement($fileTypes),
        'file_path' => 'media/' . rand(1, 20) . '/' . Str::random(8) . '.' . ($faker->randomElement($fileTypes) === 'image' ? 'jpg' : 'mp4'),
        'caption' => $faker->sentence(),
        'is_approved' => $faker->boolean(0.9),
    ];
});