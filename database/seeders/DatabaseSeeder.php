<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->createUser();

        $this->call([
            UserModuleSeeder::class,
        ]);
    }

    private function createUser(): void
    {
        $user = \App\Models\User::factory()->create([
            'name' => 'Pedro',
            'email' => 'pedro@gmail.com',
            'address' => 'R. Rosas de congonhas, 404, bairro, municipio',
            'phone' => '5584999999999',
            'email_verified_at' => now(),
            'password' => bcrypt('123456'),
            'remember_token' => Str::random(10),
        ]);

        info('User created: ' . $user->id);
    
        \App\Models\UserModule::create([
            'admin' => true,
            'operator' => true,
            'user_id' => $user->id,
        ]);
    }
}
