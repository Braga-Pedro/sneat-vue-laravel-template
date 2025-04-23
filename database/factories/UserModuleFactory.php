<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserModuleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'admin' => $this->faker->boolean(30),
            'operator'=> $this->faker->boolean(60),
        ];
    }
}
