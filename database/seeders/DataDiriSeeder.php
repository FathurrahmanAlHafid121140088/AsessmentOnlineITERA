<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataDiris;
use App\Models\RiwayatKeluhans;

class DataDiriSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            DataDiris::create([
                'nim' => '1211400' . $i,
                'nama' => 'Mahasiswa ' . $i,
                'jenis_kelamin' => $i % 2 == 0 ? 'L' : 'P',
                'alamat' => 'Jl. Contoh Alamat ' . $i,
                'usia' => rand(18, 24),
                'fakultas' => 'Fakultas Sains',
                'program_studi' => 'Teknik Informatika',
                'email' => 'mahasiswa' . $i . '@email.com',
            ]);

            RiwayatKeluhans::create([
                'nim' => '1211400' . $i,
                'keluhan' => 'Keluhan ke-' . $i,
                'lama_keluhan' => rand(1, 5) . ' bulan',
                'pernah_konsul' => $i % 2 == 0 ? 'Ya' : 'Tidak',
                'pernah_tes' => $i % 2 == 0 ? 'Ya' : 'Tidak',
            ]);
        }
    }
}
