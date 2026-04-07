<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RmibPekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Kosongkan tabel terlebih dahulu
        DB::table('rmib_pekerjaan')->truncate();
        $now = Carbon::now();
        $dataToInsert = [];

        // ==========================================================
        // == KUNCI JAWABAN PRIA (BEST GUESS)
        // ==========================================================
        $mappingPria = [
            'A' => [
                'Profesional Geodesi dan Geomatika' => 'Outdoor',
                'Insinyur Sipil' => 'Mechanical',
                'Akuntan' => 'Computational',
                'Ilmuwan' => 'Scientific',
                'Manager Penjualan' => 'Personal Contact',
                'Seniman' => 'Aesthetic',
                'Wartawan' => 'Literary',
                'Pianis Konser' => 'Musical',
                'Konselor Kesehatan' => 'Social Service',
                'Manager Bank' => 'Clerical',
                'Engineering staff' => 'Practical',
                'Dokter' => 'Medical',
            ],
            'B' => [
                'Perancang dan Pengembang Alat' => 'Mechanical',
                'Ahli Statistik' => 'Computational',
                'Insinyur Kimia Industri' => 'Scientific',
                'Penyiar Radio' => 'Personal Contact',
                'Artis Profesional' => 'Aesthetic',
                'Pengarang' => 'Literary',
                'Dirigen Orkestra' => 'Musical',
                'Psikolog Pendidikan' => 'Social Service',
                'Sekretaris Perusahaan' => 'Clerical',
                'Ahli Bangunan' => 'Practical',
                'Ahli Bedah' => 'Medical',
                'Ahli Kehutanan' => 'Outdoor',
            ],
            'C' => [
                'Auditor' => 'Computational',
                'Ahli Meteorologi' => 'Scientific',
                'Salesman' => 'Personal Contact',
                'Arsitek' => 'Aesthetic',
                'Penulis Drama' => 'Literary',
                'Komponis' => 'Musical',
                'Kepala Sekolah' => 'Social Service',
                'Pegawai Kecamatan' => 'Clerical',
                'Manufaktur dan Produksi' => 'Practical',
                'Dokter Hewan' => 'Medical',
                'Asisten Spesialis Restorasi Ekosistem dan Reforestasi' => 'Outdoor',
                'Instrumentation and Automation Engineer' => 'Mechanical',
            ],
            'D' => [
                'Ahli Biologi' => 'Scientific',
                'Influencer' => 'Personal Contact',
                'Dekorator Interior' => 'Aesthetic',
                'Ahli Sejarah' => 'Literary',
                'Kritikus Musik' => 'Musical',
                'Trainer' => 'Social Service',
                'Regulator Bidang Keuangan' => 'Clerical',
                'Site Operasional' => 'Practical',
                'Apoteker' => 'Medical',
                'Pariwisata Bahari' => 'Outdoor',
                'Electrical Engineer' => 'Mechanical',
                'Business Analyst' => 'Computational',
            ],
            'E' => [
                'Petugas Wawancara' => 'Personal Contact',
                'Perancang Perhiasan' => 'Aesthetic',
                'Ahli Perpustakaan' => 'Literary',
                'Guru Musik' => 'Musical',
                'Pembina Rohani' => 'Social Service',
                'Digital Data Curator' => 'Clerical',
                'Supervisor Jasa Konstruksi' => 'Practical',
                'Dokter Gigi' => 'Medical',
                'Prospektor' => 'Outdoor',
                'Site Engineering' => 'Mechanical',
                'Guru Ilmu Pasti' => 'Computational',
                'Ahli Pertanian' => 'Scientific',
            ],
            'F' => [
                'Fotografer' => 'Aesthetic',
                'Penulis Majalah' => 'Literary',
                'Pemain Orgen Tunggal' => 'Musical',
                'Sociopreneur' => 'Social Service',
                'Supervisor & Manager Rantai Pasok' => 'Clerical',
                'Building contractor' => 'Practical',
                'Ahli Kacamata' => 'Medical',
                'Oil and Gas Engineer' => 'Outdoor',
                'Renewable Energy Engineer' => 'Mechanical',
                'Asisten Kasir Bank' => 'Computational',
                'Ahli Botani' => 'Scientific',
                'Pedagang Keliling' => 'Personal Contact',
            ],
            'G' => [
                'Kritikus Buku' => 'Literary',
                'Ahli Pustaka Musik' => 'Musical',
                'Community Facilitator' => 'Social Service',
                'Pegawai Kantor' => 'Clerical',
                'Pelaksana Pekerjaan Lanskap' => 'Practical',
                'Ahli Rontgent' => 'Medical',
                'Analis Hidrografi dan Metocean' => 'Outdoor',
                'Pemeliharaan Sarana Kereta Api' => 'Mechanical',
                'Ekonom Perminyakan & Investasi' => 'Computational',
                'Ahli Astronomi' => 'Scientific',
                'Juru Lelang' => 'Personal Contact',
                'Penata Panggung' => 'Aesthetic',
            ],
            'H' => [
                'Pemain Musik Band' => 'Musical',
                'Ahli Penyuluh Jabatan' => 'Social Service',
                'Pegawai Kantor Pos' => 'Clerical',
                'Piping Engineer' => 'Practical',
                'Ahli Fisioterapi' => 'Medical',
                'Sopir Angkutan Umum' => 'Outdoor',
                'Product Designer' => 'Mechanical',
                'Data Science Architect' => 'Computational',
                'Ahli Geologi' => 'Scientific',
                'Petugas Hubungan Masyarakat' => 'Personal Contact',
                'Illustrator' => 'Aesthetic',
                'Jurnalis' => 'Literary',
            ],
            'I' => [
                'Petugas Kesejahteraan Sosial' => 'Social Service',
                'Petugas Ekspedisi Surat' => 'Clerical',
                'Quality Control Staff' => 'Practical',
                'Paramedik/Mantri Kesehatan' => 'Medical',
                'Petani Tanaman Hias' => 'Outdoor',
                'Welding Inspector' => 'Mechanical',
                'Regulator Bidang Keuangan' => 'Computational',
                'Asisten Laboratorium' => 'Scientific',
                'Human Resource (HR)' => 'Personal Contact',
                'Perancang Motif Tekstil' => 'Aesthetic',
                'Penyair' => 'Literary',
                'Pramuniaga Toko Musik' => 'Musical',
            ]
        ];

        // ==========================================================
        // == KUNCI JAWABAN WANITA (BEST GUESS)
        // ==========================================================
        $mappingWanita = [
            'A' => [
                'Profesional Geodesi dan Geomatika' => 'Outdoor',
                'Instrumentation and Automation Engineer' => 'Mechanical',
                'Akuntan' => 'Computational',
                'Ilmuwan' => 'Scientific',
                'Penjual produk fashion' => 'Personal Contact',
                'Seniawati/ Seniman Wanita' => 'Aesthetic',
                'Wartawati/ Wartawan Wanita' => 'Literary',
                'Pianis Konser' => 'Musical',
                'Konselor Kesehatan' => 'Social Service',
                'Sekretaris Pribadi' => 'Clerical',
                'Teknisi Scale-Up Formulasi' => 'Practical',
                'Dokter' => 'Medical',
            ],
            'B' => [
                'Petugas Assembling/ Perakitan Alat' => 'Mechanical',
                'Data Engineer' => 'Computational',
                'Insinyur Kimia Industri' => 'Scientific',
                'Penyiar Radio' => 'Personal Contact',
                'Artis Profesional' => 'Aesthetic',
                'Pengarang' => 'Literary',
                'Pemain Musik Orkestra' => 'Musical',
                'Psikolog Pendidikan' => 'Social Service',
                'Administrator Jaringan' => 'Clerical',
                'Pembuat Pot Keramik' => 'Practical',
                'Ahli Bedah' => 'Medical',
                'Guru Pendidikan Jasmani' => 'Outdoor',
            ],
            'C' => [
                'Auditor' => 'Computational',
                'Ahli Meteorologi' => 'Scientific',
                'Salesgirl' => 'Personal Contact',
                'Guru Kesenian' => 'Aesthetic',
                'Penulis Naskah Drama' => 'Literary',
                'Komponis Lagu' => 'Musical',
                'Kepala Yayasan Sosial' => 'Social Service',
                'Resepsionis' => 'Clerical',
                'Cosmetic Product Designer' => 'Practical',
                'Dokter Hewan' => 'Medical',
                'Pariwisata Bahari' => 'Outdoor',
                'Pegawai PDAM' => 'Mechanical',
            ],
            'D' => [
                'Ahli Biologi' => 'Scientific',
                'Influencer' => 'Personal Contact',
                'Dekorator Interior' => 'Aesthetic',
                'Ahli Sejarah' => 'Literary',
                'Kritikus Musik' => 'Musical',
                'Pekerja Sosial' => 'Social Service',
                'Digital Data Curator' => 'Clerical',
                'Penjilid Buku' => 'Practical',
                'Apoteker' => 'Medical',
                'Ahli Pertamanan' => 'Outdoor',
                'Product Designer' => 'Mechanical',
                'Petugas Mesin Hitung' => 'Computational',
            ],
            'E' => [
                'Petugas Wawancara' => 'Personal Contact',
                'Perancang Pakaian' => 'Aesthetic',
                'Ahli Perpustakaan' => 'Literary',
                'Guru Musik' => 'Musical',
                'Pembina Agama' => 'Social Service',
                'Petugas Arsip' => 'Clerical',
                'Process Development Scientist' => 'Practical',
                'Pelatih Rehabilitasi Pasien' => 'Medical',
                'Pembina Keolahragaan' => 'Outdoor',
                'Ahli Reparasi Jam' => 'Mechanical',
                'Guru Ilmu Pasti' => 'Computational',
                'Ahli Pertanian' => 'Scientific',
            ],
            'F' => [
                'Fotografer' => 'Aesthetic',
                'Penulis Majalah' => 'Literary',
                'Pemain Orgen Tunggal' => 'Musical',
                'Petugas Palang Merah' => 'Social Service',
                'Pegawai Bank' => 'Clerical',
                'Manufaktur dan Produksi' => 'Practical',
                'Perawat' => 'Medical',
                'Peternak' => 'Outdoor',
                'Ahli Optik' => 'Mechanical',
                'Kasir' => 'Computational',
                'Ahli Botani' => 'Scientific',
                'Perencana Strategis' => 'Personal Contact',
            ],
            'G' => [
                'Kritikus Buku' => 'Literary',
                'Ahli Pustaka Musik' => 'Musical',
                'Trainer' => 'Social Service',
                'Pegawai Kantor' => 'Clerical',
                'Pelaksana Pekerjaan Lanskap' => 'Practical',
                'Ahli Rontgen' => 'Medical',
                'Petani Bunga' => 'Outdoor',
                'Industrial Engineer' => 'Mechanical',
                'Ahli Tata Buku' => 'Computational',
                'Ahli Astronomi' => 'Scientific',
                'Peraga Alat Kosmetika' => 'Personal Contact',
                'Penata Panggung' => 'Aesthetic',
            ],
            'H' => [
                'Pemain Musik Band' => 'Musical',
                'Ahli Penyuluh Jabatan' => 'Social Service',
                'Pegawai Kantor Pos' => 'Clerical',
                'Engineering staff' => 'Practical',
                'Ahli Fisioterapi' => 'Medical',
                'Peternak Ayam' => 'Outdoor',
                'Ahli Reparasi Permata' => 'Mechanical',
                'Business Analyst' => 'Computational',
                'Ahli Geologi' => 'Scientific',
                'Petugas Hubungan Masyarakat' => 'Personal Contact',
                'Penata Etalase' => 'Aesthetic',
                'Jurnalis' => 'Literary',
            ],
            'I' => [
                'Petugas Kesejahteraan Sosial' => 'Social Service',
                'Penyusun Arsip' => 'Clerical',
                'Juru Masak' => 'Practical',
                'Perawat Orang Tua/ Caregiver' => 'Medical',
                'Tukang Kebun' => 'Outdoor',
                'Production Engineer Kosmetik' => 'Mechanical',
                'Pegawai Pajak' => 'Computational',
                'Asisten Laboratorium' => 'Scientific',
                'Human Resource (HR)' => 'Personal Contact',
                'Perancang Motif Tekstil' => 'Aesthetic',
                'Penyair' => 'Literary',
                'Pramuniaga Toko Musik' => 'Musical',
            ]
        ];

        // 7. Proses Pria
        foreach ($mappingPria as $kelompokChar => $pekerjaanList) {
            foreach ($pekerjaanList as $pekerjaan => $kategori) {
                $dataToInsert[] = [
                    'kelompok' => $kelompokChar,
                    'gender' => 'L',
                    'kategori' => $kategori,
                    'nama_pekerjaan' => $pekerjaan,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // 8. Proses Wanita
        foreach ($mappingWanita as $kelompokChar => $pekerjaanList) {
            foreach ($pekerjaanList as $pekerjaan => $kategori) {
                $dataToInsert[] = [
                    'kelompok' => $kelompokChar,
                    'gender' => 'P',
                    'kategori' => $kategori,
                    'nama_pekerjaan' => $pekerjaan,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // 9. Masukkan semua data sekaligus ke DB
        DB::table('rmib_pekerjaan')->insert($dataToInsert);
    }
}