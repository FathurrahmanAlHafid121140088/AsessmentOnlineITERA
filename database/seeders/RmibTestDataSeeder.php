<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Users;
use App\Models\KarirDataDiri;
use App\Models\RmibHasilTes;
use App\Models\RmibJawabanPeserta;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RmibTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar nama untuk generate user
        $namaDepan = ['Ahmad', 'Budi', 'Citra', 'Dewi', 'Eka', 'Fajar', 'Gita', 'Hadi', 'Indah', 'Joko', 'Kartika', 'Lina', 'Made', 'Novi', 'Oscar', 'Putri', 'Qori', 'Rani', 'Siti', 'Tono', 'Udin', 'Vina', 'Wati', 'Yoga', 'Zahra'];
        $namaBelakang = ['Pratama', 'Kusuma', 'Wijaya', 'Santoso', 'Permata', 'Ramadhan', 'Sari', 'Putra', 'Putri', 'Wibowo', 'Setiawan', 'Hidayat', 'Nugroho', 'Lestari', 'Hakim', 'Rahman', 'Abdullah', 'Firmansyah', 'Saputra', 'Maulana'];

        // Daftar program studi dengan mapping fakultas
        $prodiWithFakultas = [
            // FTI (Fakultas Teknologi Industri)
            ['prodi' => 'Teknik Informatika', 'fakultas' => 'FTI'],
            ['prodi' => 'Teknik Elektro', 'fakultas' => 'FTI'],
            ['prodi' => 'Teknik Mesin', 'fakultas' => 'FTI'],
            ['prodi' => 'Teknik Kimia', 'fakultas' => 'FTI'],
            ['prodi' => 'Teknik Fisika', 'fakultas' => 'FTI'],

            // FTIK (Fakultas Teknik Infrastruktur dan Kewilayahan)
            ['prodi' => 'Teknik Sipil', 'fakultas' => 'FTIK'],
            ['prodi' => 'Arsitektur', 'fakultas' => 'FTIK'],
            ['prodi' => 'Teknik Geologi', 'fakultas' => 'FTIK'],
            ['prodi' => 'Perencanaan Wilayah dan Kota', 'fakultas' => 'FTIK'],

            // FS (Fakultas Sains)
            ['prodi' => 'Matematika', 'fakultas' => 'FS'],
            ['prodi' => 'Fisika', 'fakultas' => 'FS'],
            ['prodi' => 'Kimia', 'fakultas' => 'FS'],
            ['prodi' => 'Biologi', 'fakultas' => 'FS'],
            ['prodi' => 'Sains Aktuaria', 'fakultas' => 'FS']
        ];

        // 12 kategori RMIB
        $kategoriRMIB = [
            'Outdoor',
            'Mechanical',
            'Computational',
            'Scientific',
            'Personal Contact',
            'Aesthetic',
            'Literary',
            'Musical',
            'Social Service',
            'Clerical',
            'Practical',
            'Medical'
        ];

        // Daftar asal sekolah
        $asalSekolahList = ['SMA', 'SMK', 'Boarding School'];

        $this->command->info('🚀 Mulai generate 50 akun dengan data tes RMIB...');

        for ($i = 1; $i <= 50; $i++) {
            // Generate NIM
            $tahun = rand(2021, 2024);
            $nim = $tahun . str_pad($i, 5, '0', STR_PAD_LEFT);

            // Generate nama dengan nomor untuk memastikan unik
            $nama = $namaDepan[array_rand($namaDepan)] . ' ' . $namaBelakang[array_rand($namaBelakang)] . ' ' . $i;
            $email = $nim . '@student.itera.ac.id';

            // 1. Buat User
            $user = Users::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $nama,
                    'nim' => $nim,
                    'password' => Hash::make('password123'),
                ]
            );

            // 2. Buat Data Diri
            $gender = rand(0, 1) ? 'L' : 'P';
            $prodiData = $prodiWithFakultas[array_rand($prodiWithFakultas)];

            $dataDiri = KarirDataDiri::firstOrCreate(
                ['nim' => $nim],
                [
                    'nama' => $nama,
                    'jenis_kelamin' => $gender,
                    'usia' => rand(18, 25),
                    'alamat' => 'Lampung',
                    'fakultas' => $prodiData['fakultas'],
                    'program_studi' => $prodiData['prodi'],
                    'email' => $email,
                    'asal_sekolah' => $asalSekolahList[array_rand($asalSekolahList)],
                    'provinsi' => 'Lampung',
                    'status_tinggal' => rand(0, 1) ? 'Kost' : 'Bersama Orang Tua',
                    'prodi_sesuai_keinginan' => rand(0, 1) ? 'Ya' : 'Tidak',
                ]
            );

            // 3. Buat Hasil Tes RMIB
            $tanggalPengerjaan = Carbon::now()->subDays(rand(1, 90));

            // Pilih top 3 kategori secara random
            $topKategori = array_rand(array_flip($kategoriRMIB), 3);

            $hasilTes = RmibHasilTes::create([
                'karir_data_diri_id' => $dataDiri->id,
                'tanggal_pengerjaan' => $tanggalPengerjaan,
                'top_1_pekerjaan' => $topKategori[0],
                'top_1_alasan' => 'Sesuai dengan minat dan kemampuan saya',
                'top_2_pekerjaan' => $topKategori[1],
                'top_2_alasan' => 'Memberikan peluang karir yang baik',
                'top_3_pekerjaan' => $topKategori[2],
                'top_3_alasan' => 'Sesuai dengan passion saya',
                'pekerjaan_lain' => null,
                'pekerjaan_lain_alasan' => null,
                'skor_konsistensi' => rand(70, 100),
                'interpretasi' => 'Valid',
            ]);

            // 4. Generate Jawaban Peserta (9 kelompok x 12 pekerjaan)
            // Setiap kelompok punya 12 pekerjaan yang harus di-rank 1-12
            for ($kelompok = 1; $kelompok <= 9; $kelompok++) {
                $jobRankings = range(1, 12);
                shuffle($jobRankings);

                for ($job = 1; $job <= 12; $job++) {
                    RmibJawabanPeserta::create([
                        'hasil_id' => $hasilTes->id,
                        'kelompok' => $kelompok,
                        'pekerjaan' => $job,
                        'peringkat' => $jobRankings[$job - 1],
                    ]);
                }
            }

            $this->command->info("✅ [{$i}/50] User: {$nama} ({$email}) - Tes berhasil dibuat!");
        }

        $this->command->info('🎉 Selesai! 50 akun dengan data tes RMIB berhasil dibuat.');
        $this->command->info('📊 Total data:');
        $this->command->info('   - Users: ' . Users::count());
        $this->command->info('   - Data Diri Karir: ' . KarirDataDiri::count());
        $this->command->info('   - Hasil Tes RMIB: ' . RmibHasilTes::count());
        $this->command->info('   - Jawaban Peserta: ' . RmibJawabanPeserta::count());
    }
}
