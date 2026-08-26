<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class UserFactory extends Factory
{
    /**
     * The current model being built.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(Faker $faker)
    {
        $roles = ['participant', 'organizer', 'admin'];
        $weights = [0.7, 0.2, 0.1];
        $role = $roles[array_rand(array_combine($roles, $weights))];

        return [
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'username' => strtolower($faker->userName . rand(10, 99)),
            'contact_number' => '+91 ' . rand(6000000000, 9999999999),
            'password' => bcrypt('password'),
            'role' => $role,
        ];
    }
}