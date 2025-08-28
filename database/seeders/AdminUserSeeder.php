<?php

// database/seeders/AdminUserSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'RT26',
            'email' => 'hadipermadi@gmail.com',
            'password' => bcrypt('hadipermadi26'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'RT27',
            'email' => 'cecekomarudin@gmail.com',
            'password' => bcrypt('cecekomarudin27'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'RT28',
            'email' => 'hendra@gmail.com',
            'password' => bcrypt('hendra28'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'RT29',
            'email' => 'ali@gmail.com',
            'password' => bcrypt('ali29'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'RT30',
            'email' => 'yanasuharyana@gmail.com',
            'password' => bcrypt('yanasuharyana30'),
            'role' => 'admin',
        ]);
    }
}