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
                'Teknik Pertambangan',
                'Teknik Biosistem',
                'Teknologi Industri Pertanian',
                'Teknologi Pangan',
                'Rekayasa Kehutanan',
                'Rekayasa Kosmetik',
                'Rekayasa Minyak dan Gas',
                'Rekayasa Instrumentasi dan Automasi',
                'Rekayasa Keolahragaan',
            ],
        ];

        $provinsiList = [
            "Aceh",
            "Sumatera Utara",
            "Sumatera Barat",
            "Riau",
            "Kepulauan Riau",
            "Jambi",
            "Sumatera Selatan",
            "Bangka Belitung",
            "Bengkulu",
            "Lampung",
            "DKI Jakarta",
            "Banten",
            "Jawa Barat",
            "Jawa Tengah",
            "DI Yogyakarta",
            "Jawa Timur",
            "Bali",
            "Nusa Tenggara Barat",
            "Nusa Tenggara Timur",
            "Kalimantan Barat",
            "Kalimantan Tengah",
            "Kalimantan Selatan",
            "Kalimantan Timur",
            "Kalimantan Utara",
            "Sulawesi Utara",
            "Sulawesi Tengah",
            "Sulawesi Selatan",
            "Sulawesi Tenggara",
            "Gorontalo",
            "Sulawesi Barat",
            "Maluku",
            "Maluku Utara",
            "Papua",
            "Papua Barat",
            "Papua Tengah",
            "Papua Pegunungan",
            "Papua Selatan",
            "Papua Barat Daya"
        ];

        $statusTinggalList = ['Bersama Orang Tua', 'Kost'];
        $asalSekolahList = ['SMA', 'SMK', 'Boarding School'];

        for ($i = 1; $i <= 1000; $i++) {
            $nim = '121140' . str_pad($i, 3, '0', STR_PAD_LEFT);
            $fakultas = $fakultasList[array_rand($fakultasList)];
            $prodi = $prodiByFakultas[$fakultas][array_rand($prodiByFakultas[$fakultas])];
            $provinsi = $provinsiList[array_rand($provinsiList)];
            $statusTinggal = $statusTinggalList[array_rand($statusTinggalList)];
            $asalSekolah = $asalSekolahList[array_rand($asalSekolahList)];

            DataDiris::create([
                'nim' => $nim,
                'nama' => 'Mahasiswa ' . $i,
                'jenis_kelamin' => $i % 2 == 0 ? 'L' : 'P',
                'provinsi' => $provinsi,
                'alamat' => 'Jl. Contoh Alamat No.' . $i,
                'usia' => rand(17, 25),
                'fakultas' => $fakultas,
                'program_studi' => $prodi,
                'asal_sekolah' => $asalSekolah,
                'status_tinggal' => $statusTinggal,
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
