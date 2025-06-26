<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HasilKuesioner;
use App\Models\DataDiris;
use App\Models\RiwayatKeluhans;
use Illuminate\Support\Str;

class HasilKuesionerSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            $nim = '1211400' . $i;

            // Buat data diri jika belum ada
            $dataDiri = DataDiris::firstOrCreate(
                ['nim' => $nim],
                [
                    'nama' => 'Mahasiswa ' . $i,
                    'program_studi' => 'Teknik Informatika',
                    'email' => 'user' . $i . '@example.com',
                    'alamat' => 'Jalan Contoh ' . $i,
                    'jenis_kelamin' => $i % 2 == 0 ? 'L' : 'P',
                    'fakultas' => 'Fakultas Sains',
                    'usia' => rand(18, 24),
                ]
            );

            // Simpan satu atau dua hasil kuesioner
            $jumlahKuesioner = $i % 2 == 0 ? 1 : 2;
            for ($j = 1; $j <= $jumlahKuesioner; $j++) {
                $skor = rand(38, 226);
                $kategori = $this->getKategori($skor);

                HasilKuesioner::create([
                    'nim' => $nim,
                    'total_skor' => $skor,
                    'kategori' => $kategori,
                    'created_at' => now()->subDays($j),
                    'updated_at' => now()->subDays($j),
                ]);
            }

            // Simpan satu atau dua riwayat keluhan
            $jumlahKeluhan = $i % 2 == 0 ? 1 : 2;
            for ($k = 1; $k <= $jumlahKeluhan; $k++) {
                RiwayatKeluhans::create([
                    'nim' => $nim,
                    'keluhan' => 'Keluhan ke-' . $k . ' untuk ' . $nim,
                    'lama_keluhan' => $k . ' bulan',
                    'pernah_konsul' => $k % 2 == 0 ? 'Tidak' : 'Ya',
                    'pernah_tes' => $k % 2 == 0 ? 'Ya' : 'Tidak',
                    'created_at' => now()->subDays($k + 1),
                    'updated_at' => now()->subDays($k + 1),
                ]);
            }
        }
    }

    private function getKategori($skor)
    {
        return match (true) {
            $skor >= 191 => 'Sangat Baik (Sejahtera Secara Mental)',
            $skor >= 161 => 'Baik (Sehat Secara Mental)',
            $skor >= 131 => 'Sedang (Rentan)',
            $skor >= 91  => 'Buruk (Distres Sedang)',
            $skor >= 38  => 'Sangat Buruk (Distres Berat)',
            default => 'Tidak Diketahui',
        };
    }
}
