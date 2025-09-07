<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class superAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'RW 08',
            'email' => 'daryantohadibrata@gmail.com',
            'password' => bcrypt('daryantorw08'),
            'role' => 'super_admin',
        ]);
    }
}
