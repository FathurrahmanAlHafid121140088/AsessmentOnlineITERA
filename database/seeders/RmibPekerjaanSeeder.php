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
                'Petani' => 'Outdoor',
                'Insinyur Sipil' => 'Mechanical',
                'Akuntan' => 'Computational',
                'Ilmuwan' => 'Scientific',
                'Manager Penjualan' => 'Personal Contact',
                'Seniman' => 'Aesthetic',
                'Wartawan' => 'Literary',
                'Pianis Konser' => 'Musical',
                'Guru SD' => 'Social Service',
                'Manager Bank' => 'Clerical',
                'Tukang Kayu' => 'Practical',
                'Dokter' => 'Medical',
            ],
            'B' => [
                'Ahli Pembuat Alat' => 'Mechanical',
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
                'Ahli Meubel/ Furniture' => 'Practical',
                'Dokter Hewan' => 'Medical',
                'Juru Ukur Tanah' => 'Outdoor',
                'Tukang Bubut/ Lemer' => 'Mechanical',
            ],
            'D' => [
                'Ahli Biologi' => 'Scientific',
                'Agen Biro Periklanan' => 'Personal Contact',
                'Dekorator Interior' => 'Aesthetic',
                'Ahli Sejarah' => 'Literary',
                'Kritikus Musik' => 'Musical',
                'Pekerja Sosial' => 'Social Service',
                'Pegawai Asuransi' => 'Clerical',
                'Tukang Cat' => 'Practical',
                'Apoteker' => 'Medical',
                'Penjelajah' => 'Outdoor',
                'Tukang Listrik' => 'Mechanical',
                'Penilai Pajak Pendapatan' => 'Computational',
            ],
            'E' => [
                'Petugas Wawancara' => 'Personal Contact',
                'Perancang Perhiasan' => 'Aesthetic',
                'Ahli Perpustakaan' => 'Literary',
                'Guru Musik' => 'Musical',
                'Pembina Rohani' => 'Social Service',
                'Petugas Arsip' => 'Clerical',
                'Tukang Batu' => 'Practical',
                'Dokter Gigi' => 'Medical',
                'Prospektor' => 'Outdoor',
                'Montir' => 'Mechanical',
                'Guru Ilmu Pasti' => 'Computational',
                'Ahli Pertanian' => 'Scientific',
            ],
            'F' => [
                'Fotografer' => 'Aesthetic',
                'Penulis Majalah' => 'Literary',
                'Pemain Orgen Tunggal' => 'Musical',
                'Organisator Kepramukaan' => 'Social Service',
                'Petugas Pengirim Barang' => 'Clerical',
                'Operator Mesin Perkayuan' => 'Practical',
                'Ahli Kacamata' => 'Medical',
                'Ahli Sortir Kulit' => 'Outdoor',
                'Instalator' => 'Mechanical',
                'Asisten Kasir Bank' => 'Computational',
                'Ahli Botani' => 'Scientific',
                'Pedagang Keliling' => 'Personal Contact',
            ],
            'G' => [
                'Kritikus Buku' => 'Literary',
                'Ahli Pustaka Musik' => 'Musical',
                'Pengurus Karang Taruna' => 'Social Service',
                'Pegawai Kantor' => 'Clerical',
                'Tukang Plester Tembok' => 'Practical',
                'Ahli Rontgent' => 'Medical',
                'Nelayan' => 'Outdoor',
                'Pembuat Arloji' => 'Mechanical',
                'Kasir' => 'Computational',
                'Ahli Astronomi' => 'Scientific',
                'Juru Lelang' => 'Personal Contact',
                'Penata Panggung' => 'Aesthetic',
            ],
            'H' => [
                'Pemain Musik Band' => 'Musical',
                'Ahli Penyuluh Jabatan' => 'Social Service',
                'Pegawai Kantor Pos' => 'Clerical',
                'Tukang Ledeng/ Pipa Air' => 'Practical',
                'Ahli Fisioterapi' => 'Medical',
                'Sopir Angkutan Umum' => 'Outdoor',
                'Montir Radio' => 'Mechanical',
                'Juru Bayar' => 'Computational',
                'Ahli Geologi' => 'Scientific',
                'Petugas Hubungan Masyarakat' => 'Personal Contact',
                'Penata Etalase' => 'Aesthetic',
                'Penulis Sandiwara Radio' => 'Literary',
            ],
            'I' => [
                'Petugas Kesejahteraan Sosial' => 'Social Service',
                'Petugas Ekspedisi Surat' => 'Clerical',
                'Tukang Sepatu' => 'Practical',
                'Paramedik/Mantri Kesehatan' => 'Medical',
                'Petani Tanaman Hias' => 'Outdoor',
                'Tukang Las' => 'Mechanical',
                'Petugas Pajak' => 'Computational',
                'Asisten Laboratorium' => 'Scientific',
                'Salesman Asuransi' => 'Personal Contact',
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
                'Pekerjaan Pertanian' => 'Outdoor',
                'Pengemudi Kendaraan Militer' => 'Mechanical',
                'Akuntan' => 'Computational',
                'Ilmuwan' => 'Scientific',
                'Penjual produk fashion' => 'Personal Contact',
                'Seniawati/ Seniman Wanita' => 'Aesthetic',
                'Wartawati/ Wartawan Wanita' => 'Literary',
                'Pianis Konser' => 'Musical',
                'Guru SD' => 'Social Service',
                'Sekretaris Pribadi' => 'Clerical',
                'Modiste/ Penjahit Baju Khusus Wanita' => 'Practical',
                'Dokter' => 'Medical',
            ],
            'B' => [
                'Petugas Assembling/ Perakitan Alat' => 'Mechanical',
                'Pegawai urusan gaji' => 'Computational',
                'Insinyur Kimia Industri' => 'Scientific',
                'Penyiar Radio' => 'Personal Contact',
                'Artis Profesional' => 'Aesthetic',
                'Pengarang' => 'Literary',
                'Pemain Musik Orkestra' => 'Musical',
                'Psikolog Pendidikan' => 'Social Service',
                'Juru TIK' => 'Clerical',
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
                'Komponis' => 'Musical',
                'Kepala Yayasan Sosial' => 'Social Service',
                'Resepsionis' => 'Clerical',
                'Penata Rambut' => 'Practical',
                'Dokter Hewan' => 'Medical',
                'Pramugari' => 'Outdoor',
                'Operator Mesin Rajut' => 'Mechanical',
            ],
            'D' => [
                'Ahli Biologi' => 'Scientific',
                'Agen Biro Periklanan' => 'Personal Contact',
                'Dekorator Interior' => 'Aesthetic',
                'Ahli Sejarah' => 'Literary',
                'Kritikus Musik' => 'Musical',
                'Pekerja Sosial' => 'Social Service',
                'Penulis Steno' => 'Clerical',
                'Penjilid Buku' => 'Practical',
                'Apoteker' => 'Medical',
                'Ahli Pertamanan' => 'Outdoor',
                'Petugas Pompa Bensin' => 'Mechanical',
                'Petugas Mesin Hitung' => 'Computational',
            ],
            'E' => [
                'Petugas Wawancara' => 'Personal Contact',
                'Perancang Pakaian' => 'Aesthetic',
                'Ahli Perpustakaan' => 'Literary',
                'Guru Musik' => 'Musical',
                'Pembina Agama' => 'Social Service',
                'Petugas Arsip' => 'Clerical',
                'Tukang Bungkus Cokelat' => 'Practical',
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
                'Pengurus Kerumahtanggan' => 'Practical',
                'Perawat' => 'Medical',
                'Peternak' => 'Outdoor',
                'Ahli Gosok Lensa/ Lens Grinder' => 'Mechanical',
                'Kasir' => 'Computational',
                'Ahli Botani' => 'Scientific',
                'Pedagang Keliling' => 'Personal Contact',
            ],
            'G' => [
                'Kritikus Buku' => 'Literary',
                'Ahli Pustaka Musik' => 'Musical',
                'Pejabat Klub Remaja' => 'Social Service',
                'Pegawai Kantor' => 'Clerical',
                'Tukang Binatu/ Laundry' => 'Practical',
                'Ahli Rontgen' => 'Medical',
                'Petani Bunga' => 'Outdoor',
                'Operator Mesin Sulam' => 'Mechanical',
                'Ahli Tata Buku' => 'Computational',
                'Ahli Astronomi' => 'Scientific',
                'Peraga Alat Kosmetika' => 'Personal Contact',
                'Penata Panggung' => 'Aesthetic',
            ],
            'H' => [
                'Pemain Musik Band' => 'Musical',
                'Ahli Penyuluh Jabatan' => 'Social Service',
                'Pegawai Kantor Pos' => 'Clerical',
                'Penjahit' => 'Practical',
                'Ahli Fisioterapi' => 'Medical',
                'Peternak Ayam' => 'Outdoor',
                'Ahli Reparasi Permata' => 'Mechanical',
                'Juru Bayar' => 'Computational',
                'Ahli Geologi' => 'Scientific',
                'Petugas Hubungan Masyarakat' => 'Personal Contact',
                'Penata Etalase' => 'Aesthetic',
                'Penulis Sandiwara Radio' => 'Literary',
            ],
            'I' => [
                'Petugas Kesejahteraan Sosial' => 'Social Service',
                'Penyusun Arsip' => 'Clerical',
                'Juru Masak' => 'Practical',
                'Perawat Orang Tua' => 'Medical',
                'Tukang Kebun' => 'Outdoor',
                'Petugas Mesin Kaos' => 'Mechanical',
                'Pegawai Pajak' => 'Computational',
                'Asisten Laboratorium' => 'Scientific',
                'Peraga Barang-barang/ Bahan' => 'Personal Contact',
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