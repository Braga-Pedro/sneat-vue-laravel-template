<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserModule>
 */
class UserModuleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'company_id' => $firstCompany->id,
            'uuid' => $this->faker->uuid,
            'admin' => $this->faker->boolean(30),
            'operator'=> $this->faker->boolean(60),
        ];
    }
}
