<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Users;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Users::create([
            'username' => 'user1',
            'email' => 'user1@example.com',
            'password' => bcrypt('password123'),
        ]);

        Users::create([
            'username' => 'user2',
            'email' => 'user2@example.com',
            'password' => bcrypt('password123'),
        ]);
    }
}
