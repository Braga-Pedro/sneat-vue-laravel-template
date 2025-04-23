<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserModuleSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\UserModule::factory(16)->create();               
    }
}
