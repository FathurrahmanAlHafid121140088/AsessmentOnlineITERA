<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataDiris;
use App\Models\RiwayatKeluhans;
use Illuminate\Support\Facades\DB;


class DataDiriSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('data_diris')->truncate();
        DB::table('riwayat_keluhans')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $fakultasList = [
            'Fakultas Sains',
            'Fakultas Teknologi Industri',
            'Fakultas Teknologi Infrastruktur dan Kewilayahan',
        ];

        $prodiByFakultas = [
            'Fakultas Sains' => ['Matematika', 'Fisika', 'Biologi'],
            'Fakultas Teknologi Industri' => ['Teknik Elektro', 'Teknik Kimia', 'Teknik Mesin'],
            'Fakultas Teknologi Infrastruktur dan Kewilayahan' => ['Teknik Sipil', 'Arsitektur', 'Perencanaan Wilayah'],
        ];

        for ($i = 1; $i <= 30; $i++) {
            $nim = '121140' . str_pad($i, 3, '0', STR_PAD_LEFT);
            $fakultas = $fakultasList[array_rand($fakultasList)];
            $prodi = $prodiByFakultas[$fakultas][array_rand($prodiByFakultas[$fakultas])];

            DataDiris::create([
                'nim' => $nim,
                'nama' => 'Mahasiswa ' . $i,
                'jenis_kelamin' => $i % 2 == 0 ? 'L' : 'P',
                'alamat' => 'Jl. Contoh Alamat No.' . $i,
                'usia' => rand(17, 25),
                'fakultas' => $fakultas,
                'program_studi' => $prodi,
                'email' => 'mahasiswa' . $i . '@example.com',
            ]);

            RiwayatKeluhans::create([
                'nim' => $nim,
                'keluhan' => 'Keluhan ke-' . $i,
                'lama_keluhan' => rand(1, 6),
                'pernah_konsul' => rand(0, 1) ? 'Ya' : 'Tidak',
                'pernah_tes' => rand(0, 1) ? 'Ya' : 'Tidak',
            ]);
        }
    }
}
