<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'username' => 'Tomijs',
            'email' => 'tomijs@tomijs.com',
            'password'=> bcrypt('password'),
            'country_code' => '+371',
            'phone_number' => '22543123',
        ]);

        User::factory()->create([
            'username' => 'Admin',
            'email' => 'admin@admin.com',
            'password'=> bcrypt('password'),
            'is_admin' => true,
        ]);

        $this->call(CarSeeder::class);
        $this->call(CarImageSeeder::class);
    }
}
