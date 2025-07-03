<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HasilKuesioner;
use App\Models\DataDiris;
use App\Models\RiwayatKeluhans;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HasilKuesionerSeeder extends Seeder
{
    public function run()
    {
        // ✅ MATIKAN FOREIGN KEY CHECK DULU
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate dari tabel anak ke induk (urutan penting)
        DB::table('hasil_kuesioners')->truncate();
        DB::table('riwayat_keluhans')->truncate();
        DB::table('data_diris')->truncate();

        // ✅ AKTIFKAN KEMBALI
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ✅ Daftar fakultas dan prodi
        $fakultasList = [
            'Fakultas Sains' => ['Matematika', 'Fisika', 'Biologi'],
            'Fakultas Teknologi Industri' => ['Teknik Elektro', 'Teknik Kimia', 'Teknik Mesin'],
            'Fakultas Teknologi Infrastruktur dan Kewilayahan' => ['Teknik Sipil', 'Arsitektur', 'Perencanaan Wilayah'],
        ];

        for ($i = 1; $i <= 1000; $i++) {
            $nim = '121140' . str_pad($i, 3, '0', STR_PAD_LEFT);

            // Random fakultas dan prodi
            $fakultas = array_rand($fakultasList);
            $prodi = $fakultasList[$fakultas][array_rand($fakultasList[$fakultas])];

            // Buat Data Diri
            DataDiris::create([
                'nim' => $nim,
                'nama' => 'Mahasiswa ' . $i,
                'program_studi' => $prodi,
                'email' => 'user' . $i . '@example.com',
                'alamat' => 'Jalan Contoh ' . $i,
                'jenis_kelamin' => $i % 2 === 0 ? 'L' : 'P',
                'fakultas' => $fakultas,
                'usia' => rand(17, 25),
            ]);

            // Buat hasil kuesioner 1–2x
            $jumlahKuesioner = rand(1, 2);
            for ($j = 1; $j <= $jumlahKuesioner; $j++) {
                $skor = rand(38, 226);
                HasilKuesioner::create([
                    'nim' => $nim,
                    'total_skor' => $skor,
                    'kategori' => $this->getKategori($skor),
                    'created_at' => now()->subDays($j),
                    'updated_at' => now()->subDays($j),
                ]);
            }

            // Buat riwayat keluhan 1–2x
            $jumlahKeluhan = rand(1, 2);
            for ($k = 1; $k <= $jumlahKeluhan; $k++) {
                RiwayatKeluhans::create([
                    'nim' => $nim,
                    'keluhan' => "Keluhan ke-{$k} untuk {$nim}",
                    'lama_keluhan' => rand(1, 6) . ' bulan',
                    'pernah_konsul' => rand(0, 1) ? 'Ya' : 'Tidak',
                    'pernah_tes' => rand(0, 1) ? 'Ya' : 'Tidak',
                    'created_at' => now()->subDays($k + 2),
                    'updated_at' => now()->subDays($k + 2),
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
            $skor >= 91 => 'Buruk (Distres Sedang)',
            $skor >= 38 => 'Sangat Buruk (Distres Berat)',
            default => 'Tidak Diketahui',
        };
    }
}
