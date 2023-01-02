<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::create([
            'name' => 'Sri Agung Tirtayasa',
            'username' => 'sagungt',
            'email' => 'agungjordan.aj@gmail.com',
            'password' => 'jaringan'
        ]);
        $this->call(CategorySeeder::class);
        $this->call(TagSeeder::class);
    }
}
