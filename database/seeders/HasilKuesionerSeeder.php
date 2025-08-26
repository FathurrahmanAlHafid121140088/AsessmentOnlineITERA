<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HasilKuesioner;
use App\Models\DataDiris;
use App\Models\RiwayatKeluhans;
use Illuminate\Support\Facades\DB;

class HasilKuesionerSeeder extends Seeder
{
    public function run()
    {
        // ✅ Matikan foreign key check dulu
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tabel anak ke induk
        DB::table('hasil_kuesioners')->truncate();
        DB::table('riwayat_keluhans')->truncate();
        DB::table('data_diris')->truncate();

        // ✅ Aktifkan kembali
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ✅ Daftar fakultas & prodi
        $fakultasList = [
            'Fakultas Sains' => [
                'Fisika',
                'Matematika',
                'Biologi',
                'Kimia',
                'Farmasi',
                'Sains Data',
                'Sains Aktuaria',
                'Sains Lingkungan Kelautan',
                'Sains Atmosfer dan Keplanetan',
                'Magister Fisika',
            ],
            'Fakultas Teknologi Infrastruktur dan Kewilayahan' => [
                'Perencanaan Wilayah dan Kota',
                'Teknik Geomatika',
                'Teknik Sipil',
                'Arsitektur',
                'Teknik Lingkungan',
                'Teknik Kelautan',
                'Desain Komunikasi Visual',
                'Arsitektur Lanskap',
                'Teknik Perkeretaapian',
                'Rekayasa Tata Kelola Air Terpadu',
                'Pariwisata',
            ],
            'Fakultas Teknologi Industri' => [
                'Teknik Elektro',
                'Teknik Fisika',
                'Teknik Informatika',
                'Teknik Geologi',
                'Teknik Geofisika',
                'Teknik Mesin',
                'Teknik Kimia',
                'Teknik Material',
                'Teknik Sistem Energi',
                'Teknik Industri',
                'Teknik Telekomunikasi',
                'Teknik Biomedis',
                'Teknik Biosistem',
                'Teknik Pertambangan',
                'Teknologi Industri Pertanian',
                'Teknologi Pangan',
                'Rekayasa Kehutanan',
                'Rekayasa Kosmetik',
                'Rekayasa Minyak dan Gas',
                'Rekayasa Instrumentasi dan Automasi',
                'Rekayasa Keolahragaan',
            ],
        ];

        // ✅ Daftar provinsi (38 provinsi sesuai BPS)
        $provinsiList = [
            'Aceh',
            'Sumatera Utara',
            'Sumatera Barat',
            'Riau',
            'Kepulauan Riau',
            'Jambi',
            'Bengkulu',
            'Sumatera Selatan',
            'Kepulauan Bangka Belitung',
            'Lampung',
            'Banten',
            'DKI Jakarta',
            'Jawa Barat',
            'Jawa Tengah',
            'DI Yogyakarta',
            'Jawa Timur',
            'Bali',
            'Nusa Tenggara Barat',
            'Nusa Tenggara Timur',
            'Kalimantan Barat',
            'Kalimantan Tengah',
            'Kalimantan Selatan',
            'Kalimantan Timur',
            'Kalimantan Utara',
            'Sulawesi Utara',
            'Sulawesi Tengah',
            'Sulawesi Selatan',
            'Sulawesi Tenggara',
            'Gorontalo',
            'Sulawesi Barat',
            'Maluku',
            'Maluku Utara',
            'Papua',
            'Papua Barat',
            'Papua Selatan',
            'Papua Tengah',
            'Papua Pegunungan',
            'Papua Barat Daya'
        ];

        // ✅ Status tinggal
        $statusTinggalList = ['Bersama Orang Tua', 'Kost'];

        // ✅ Asal sekolah
        $asalSekolahList = ['SMA', 'SMK', 'Boarding School'];

        // ✅ Generate data
        for ($i = 1; $i <= 1000; $i++) {
            $nim = '121140' . str_pad($i, 3, '0', STR_PAD_LEFT);

            // Random fakultas & prodi
            $fakultas = array_rand($fakultasList);
            $prodi = $fakultasList[$fakultas][array_rand($fakultasList[$fakultas])];

            // Insert data diri
            DataDiris::create([
                'nim' => $nim,
                'nama' => 'Mahasiswa ' . $i,
                'program_studi' => $prodi,
                'email' => 'user' . $i . '@example.com',
                'alamat' => 'Jalan Contoh ' . $i,
                'jenis_kelamin' => $i % 2 === 0 ? 'L' : 'P',
                'fakultas' => $fakultas,
                'usia' => rand(17, 25),
                'provinsi' => $provinsiList[array_rand($provinsiList)],
                'status_tinggal' => $statusTinggalList[array_rand($statusTinggalList)],
                'asal_sekolah' => $asalSekolahList[array_rand($asalSekolahList)],
            ]);

            // Insert hasil kuesioner
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

            // Insert riwayat keluhan
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
            $skor >= 190 => 'Sangat Sehat',
            $skor >= 152 => 'Sehat',
            $skor >= 114 => 'Cukup Sehat',
            $skor >= 76 => 'Perlu Dukungan',
            $skor >= 38 => 'Perlu Dukungan Intensif',
            default => 'Tidak Diketahui',
        };
    }
}
