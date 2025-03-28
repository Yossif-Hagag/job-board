<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Yossif Hagag',
            'email' => 'joe@example.com',
            'password' => bcrypt('password'),
        ]);

        $this->call([
            LanguageSeeder::class,
            LocationSeeder::class,
            CategorySeeder::class,
            AttributeSeeder::class,
            JobSeeder::class,
        ]);
    }
}
