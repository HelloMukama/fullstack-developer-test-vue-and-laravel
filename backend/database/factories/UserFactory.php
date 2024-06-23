<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(), // Use unique safeEmail
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // Default password
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function janedoe()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Jane Doe',
                'email' => 'janedoe@example.com',
                'password' => bcrypt('password'),
            ];
        });
    }
}
