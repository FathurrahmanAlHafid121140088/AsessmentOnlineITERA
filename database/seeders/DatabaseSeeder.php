<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Jalankan semua database seeder.
     */
    public function run(): void
    {
        $this->call([
            DataDiriSeeder::class,
            HasilKuesionerSeeder::class,
        ]);
    }
}
