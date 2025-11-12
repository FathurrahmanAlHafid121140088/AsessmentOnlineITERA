<?php

namespace App\Services;

use App\Models\RmibPekerjaan;
use App\Models\RmibJawabanPeserta;

class RmibScoringService
{
    /**
     * Hitung skor kategori berdasarkan jawaban peserta
     * Menggunakan tabel rmib_pekerjaan sebagai kunci jawaban
     *
     * @param int $hasilId ID hasil tes
     * @param string $gender Gender peserta (L/P)
     * @return array
     */
    public function hitungSemuaSkor($hasilId, $gender)
    {
        // Langkah 1: Inisialisasi Peta Kunci Jawaban
        // Ambil semua pekerjaan dari tabel rmib_pekerjaan sesuai gender
        $masterPekerjaan = RmibPekerjaan::where('gender', $gender)
            ->pluck('kategori', 'nama_pekerjaan')
            ->toArray();

        // Langkah 2: Inisialisasi Peta Skor Kategori
        $skorKategori = [
            'Outdoor' => 0,
            'Mechanical' => 0,
            'Computational' => 0,
            'Scientific' => 0,
            'Personal Contact' => 0,
            'Aesthetic' => 0,
            'Literary' => 0,
            'Musical' => 0,
            'Social Service' => 0,
            'Clerical' => 0,
            'Practical' => 0,
            'Medical' => 0,
        ];

        // Array untuk menyimpan peringkat per kategori (untuk konsistensi)
        $peringkatPerKategori = array_fill_keys(array_keys($skorKategori), []);

        // Langkah 3: Akumulasi Skor
        $jawabanPeserta = RmibJawabanPeserta::where('hasil_id', $hasilId)->get();

        foreach ($jawabanPeserta as $jawaban) {
            $namaPekerjaan = $jawaban->pekerjaan;
            $peringkat = $jawaban->peringkat; // Nilai 1-12

            // Cari kategori untuk pekerjaan ini menggunakan Peta Kunci Jawaban
            if (isset($masterPekerjaan[$namaPekerjaan])) {
                $kategori = $masterPekerjaan[$namaPekerjaan];

                // Tambahkan nilai peringkat ke total skor kategori yang sesuai
                $skorKategori[$kategori] += $peringkat;

                // Simpan peringkat untuk perhitungan konsistensi
                $peringkatPerKategori[$kategori][] = $peringkat;
            }
        }

        // Langkah 4: Perhitungan Peringkat dengan Aturan +0.5
        $skorPeringkat = $this->hitungPeringkatDenganTies($skorKategori);

        // Langkah 5: Perhitungan Skor Konsistensi
        $skorKonsistensi = $this->hitungSkorKonsistensi($peringkatPerKategori);

        return [
            'skor_kategori' => $skorKategori,
            'peringkat' => $skorPeringkat,
            'skor_konsistensi' => $skorKonsistensi,
            'peringkat_per_kategori' => $peringkatPerKategori,
        ];
    }

    /**
     * Hitung peringkat dengan aturan +0.5 untuk ties
     *
     * Aturan:
     * - Kategori dengan skor terkecil = peringkat tertinggi (paling diminati)
     * - Jika beberapa kategori memiliki skor sama, semua dapat peringkat dasar + 0.5
     *
     * @param array $skorKategori
     * @return array
     */
    protected function hitungPeringkatDenganTies($skorKategori)
    {
        // Urutkan berdasarkan skor (ascending - skor kecil = minat tinggi)
        asort($skorKategori);

        $peringkat = [];
        $currentRank = 1;
        $previousScore = null;
        $tiedCategories = [];

        foreach ($skorKategori as $kategori => $skor) {
            if ($previousScore === null || $skor != $previousScore) {
                // Jika ada kategori tied sebelumnya, beri mereka peringkat tengah
                if (count($tiedCategories) > 1) {
                    $avgRank = ($currentRank - count($tiedCategories) + $currentRank - 1) / 2;
                    foreach ($tiedCategories as $tiedCat) {
                        $peringkat[$tiedCat] = $avgRank;
                    }
                } elseif (count($tiedCategories) == 1) {
                    $peringkat[$tiedCategories[0]] = $currentRank - 1;
                }

                // Reset tied categories
                $tiedCategories = [$kategori];
                $previousScore = $skor;
            } else {
                // Skor sama dengan sebelumnya (tied)
                $tiedCategories[] = $kategori;
            }

            $currentRank++;
        }

        // Handle kategori tied terakhir
        if (count($tiedCategories) > 1) {
            $avgRank = ($currentRank - count($tiedCategories) + $currentRank - 1) / 2;
            foreach ($tiedCategories as $tiedCat) {
                $peringkat[$tiedCat] = $avgRank;
            }
        } elseif (count($tiedCategories) == 1) {
            $peringkat[$tiedCategories[0]] = $currentRank - 1;
        }

        return $peringkat;
    }

    /**
     * Hitung skor konsistensi berdasarkan varians peringkat per kategori
     * Varians rendah = konsisten = skor tinggi
     *
     * @param array $peringkatPerKategori
     * @return int Skor 0-10
     */
    protected function hitungSkorKonsistensi($peringkatPerKategori)
    {
        $totalVarians = 0;
        $jumlahKategori = 0;

        foreach ($peringkatPerKategori as $kategori => $peringkatArray) {
            if (empty($peringkatArray)) continue;

            // Hitung varians untuk kategori ini
            $mean = array_sum($peringkatArray) / count($peringkatArray);
            $variance = 0;
            foreach ($peringkatArray as $val) {
                $variance += pow($val - $mean, 2);
            }
            $variance = $variance / count($peringkatArray);

            $totalVarians += $variance;
            $jumlahKategori++;
        }

        // Rata-rata varians
        $avgVarians = $jumlahKategori > 0 ? $totalVarians / $jumlahKategori : 0;

        // Normalisasi ke skala 0-10
        // Varians maksimal teoritikal = variance dari [1,12] = 11.92
        // Varians rendah (misal 0-2) = skor tinggi (8-10)
        // Varians tinggi (misal 10-12) = skor rendah (0-2)
        $maxVarians = 11.92;
        $normalizedVarians = min($avgVarians / $maxVarians, 1);
        $skorKonsistensi = round((1 - $normalizedVarians) * 10);

        return max(0, min(10, $skorKonsistensi)); // Pastikan dalam range 0-10
    }

    /**
     * Generate interpretasi berdasarkan top 3 kategori
     *
     * @param array $top3Kategori
     * @return string
     */
    public function generateInterpretasi($top3Kategori)
    {
        $interpretasi = "Berdasarkan hasil tes RMIB Anda, minat karir dominan Anda adalah:\n\n";

        $rank = 1;
        foreach ($top3Kategori as $kategori => $skor) {
            $interpretasi .= "{$rank}. {$kategori} (Skor: {$skor})\n";
            $rank++;
        }

        $interpretasi .= "\nKategori-kategori ini menunjukkan area pekerjaan yang paling sesuai dengan minat dan preferensi Anda.";

        return $interpretasi;
    }

    /**
     * Get deskripsi lengkap kategori
     */
    public function getDeskripsiKategori()
    {
        return [
            'Outdoor' => [
                'singkatan' => 'OUT',
                'nama' => 'Minat Outdoor',
                'deskripsi' => 'Anda memiliki minat yang tinggi pada aktivitas di luar ruangan dan pekerjaan yang berhubungan dengan alam. Anda cenderung menikmati pekerjaan yang memungkinkan Anda bekerja di lingkungan terbuka seperti pertanian, kehutanan, atau pekerjaan lapangan lainnya.'
            ],
            'Mechanical' => [
                'singkatan' => 'MECH',
                'nama' => 'Minat Mekanikal',
                'deskripsi' => 'Anda tertarik pada mesin, alat-alat, dan teknologi mekanik. Anda senang bekerja dengan tangan untuk memperbaiki, merakit, atau mengoperasikan peralatan teknis. Karir di bidang teknik mesin, otomotif, atau manufaktur bisa menjadi pilihan yang cocok.'
            ],
            'Computational' => [
                'singkatan' => 'COMP',
                'nama' => 'Minat Komputasional',
                'deskripsi' => 'Anda memiliki keterampilan dalam bekerja dengan angka, perhitungan, dan analisis data. Anda senang memecahkan masalah matematika dan bekerja dengan detail numerik. Karir di bidang akuntansi, keuangan, atau analisis data mungkin sesuai dengan minat Anda.'
            ],
            'Scientific' => [
                'singkatan' => 'SCI',
                'nama' => 'Minat Ilmiah',
                'deskripsi' => 'Anda memiliki keingintahuan yang tinggi tentang bagaimana dunia bekerja dan tertarik pada penelitian dan eksperimen ilmiah. Anda senang mengamati, menganalisis, dan memecahkan masalah secara sistematis. Karir di bidang sains, penelitian, atau laboratorium bisa menjadi pilihan yang tepat.'
            ],
            'Personal Contact' => [
                'singkatan' => 'PERS',
                'nama' => 'Minat Hubungan Personal',
                'deskripsi' => 'Anda menikmati berinteraksi dengan orang lain dan membangun hubungan interpersonal. Anda memiliki kemampuan komunikasi yang baik dan senang bekerja dalam tim atau melayani orang lain. Karir di bidang penjualan, customer service, atau hubungan masyarakat mungkin cocok untuk Anda.'
            ],
            'Aesthetic' => [
                'singkatan' => 'AETH',
                'nama' => 'Minat Artistik-Estetik',
                'deskripsi' => 'Anda memiliki minat yang tinggi pada bidang seni dan estetika. Anda cenderung menikmati aktivitas yang melibatkan kreativitas, keindahan, dan ekspresi diri. Orang dengan minat AETH tinggi biasanya memiliki kepekaan terhadap nilai-nilai estetika, desain, dan seni rupa. Anda mungkin cocok dengan karir yang memungkinkan anda untuk mengekspresikan diri melalui seni visual, desain, atau kreativitas.'
            ],
            'Literary' => [
                'singkatan' => 'LIT',
                'nama' => 'Minat Literasi',
                'deskripsi' => 'Anda memiliki minat yang kuat dalam membaca, menulis, dan bekerja dengan kata-kata. Anda senang mengekspresikan ide melalui tulisan dan memiliki apresiasi terhadap literatur. Karir di bidang jurnalisme, penulisan, penerbitan, atau pengajaran bahasa mungkin sesuai dengan minat Anda.'
            ],
            'Musical' => [
                'singkatan' => 'MUS',
                'nama' => 'Minat Musikal',
                'deskripsi' => 'Anda memiliki ketertarikan yang kuat terhadap musik dan ekspresi musikal. Minat MUS yang tinggi mencerminkan apresiasi terhadap harmoni, ritme, dan berbagai bentuk ekspresi musikal. Anda mungkin senang terlibat dalam kegiatan mendengarkan, menciptakan, atau memainkan musik. Karir di bidang musik, produksi audio, atau pendidikan musik mungkin sesuai dengan minat anda.'
            ],
            'Social Service' => [
                'singkatan' => 'S.S',
                'nama' => 'Minat Pelayanan Sosial',
                'deskripsi' => 'Anda memiliki keinginan yang kuat untuk membantu orang lain dan berkontribusi pada kesejahteraan masyarakat. Anda peduli dengan isu-isu sosial dan ingin membuat perbedaan positif dalam kehidupan orang lain. Karir di bidang pekerjaan sosial, konseling, atau organisasi nirlaba mungkin cocok untuk Anda.'
            ],
            'Clerical' => [
                'singkatan' => 'CLER',
                'nama' => 'Minat Administratif',
                'deskripsi' => 'Anda menikmati pekerjaan yang terstruktur dan terorganisir dengan baik. Anda senang menangani tugas-tugas administratif, dokumentasi, dan manajemen informasi. Anda memiliki perhatian pada detail dan kemampuan untuk mengelola sistem dengan efisien. Karir di bidang administrasi, sekretariat, atau manajemen kantor mungkin sesuai.'
            ],
            'Practical' => [
                'singkatan' => 'PRAC',
                'nama' => 'Minat Praktis',
                'deskripsi' => 'Anda senang bekerja dengan tangan dan menghasilkan sesuatu yang nyata dan bermanfaat. Anda menikmati pekerjaan yang praktis dan aplikatif, yang memberikan hasil konkret. Karir di bidang kerajinan, konstruksi, atau pekerjaan teknis hands-on mungkin cocok untuk Anda.'
            ],
            'Medical' => [
                'singkatan' => 'MED',
                'nama' => 'Minat Medis',
                'deskripsi' => 'Anda menunjukkan ketertarikan yang signifikan pada bidang kesehatan dan pengobatan. Minat MED yang tinggi menggambarkan keinginan untuk membantu orang lain melalui layanan kesehatan, pemahaman tentang tubuh manusia, dan proses penyembuhan. Anda mungkin memiliki empati yang kuat dan keinginan untuk berkontribusi pada kesejahteraan orang lain. Karir di bidang medis, keperawatan, atau layanan kesehatan bisa menjadi pilihan yang cocok.'
            ],
        ];
    }

    /**
     * Generate matrix 12x9 sesuai dengan metode circular shift RMIB
     *
     * Matrix ini menunjukkan distribusi peringkat ke dalam kategori
     * berdasarkan metode yang dijelaskan di logika_matrixRMIB.md
     *
     * @param int $hasilId ID hasil tes
     * @param string $gender Gender peserta (L/P)
     * @return array Matrix 12x9 beserta data tambahan
     */
    public function generateMatrix($hasilId, $gender)
    {
        // 1. Definisi urutan kategori (12 kategori)
        $kategoriUrutan = [
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
            'Medical',
        ];

        // 2. Definisi urutan kluster (9 kluster)
        $klusterUrutan = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

        // 3. Inisialisasi matrix 12x9 dengan nilai 0
        $matrix = [];
        foreach ($kategoriUrutan as $kategori) {
            $matrix[$kategori] = array_fill_keys($klusterUrutan, 0);
        }

        // 4. Ambil semua jawaban peserta dalam bentuk map: [kelompok][pekerjaan] => peringkat
        $jawabanMap = [];
        $allJawaban = RmibJawabanPeserta::where('hasil_id', $hasilId)->get();
        foreach ($allJawaban as $jawaban) {
            $jawabanMap[$jawaban->kelompok][$jawaban->pekerjaan] = $jawaban->peringkat;
        }

        // 5. Ambil urutan pekerjaan yang BENAR dari database untuk setiap kluster
        // Pekerjaan HARUS diurutkan berdasarkan ID (insertion order) untuk menjaga circular shift
        $pekerjaanPerKluster = RmibPekerjaan::where('gender', $gender)
            ->orderBy('kelompok')
            ->orderBy('id') // Penting! Urutan sesuai insertion di seeder (circular shift)
            ->get()
            ->groupBy('kelompok');

        // 6. Untuk setiap kluster, isi matrix secara vertikal dengan wrap-around
        foreach ($klusterUrutan as $klusterIndex => $kluster) {
            if (!isset($pekerjaanPerKluster[$kluster])) {
                continue; // Skip jika tidak ada pekerjaan untuk kluster ini
            }

            // Ambil daftar pekerjaan untuk kluster ini (dari database)
            // JANGAN DI-SORT ULANG! Urutan sudah benar dari database (circular shift)
            $pekerjaanList = $pekerjaanPerKluster[$kluster];

            // Tentukan starting position (offset) untuk kluster ini
            // Kluster A dimulai dari index 0 (Outdoor)
            // Kluster B dimulai dari index 1 (Mechanical), dst.
            $startingRow = $klusterIndex;

            // Isi matrix secara vertikal dengan wrap-around
            $rowIndex = $startingRow;
            foreach ($pekerjaanList as $pekerjaan) {
                $namaPekerjaan = $pekerjaan->nama_pekerjaan;

                // Ambil peringkat yang diberikan user untuk pekerjaan ini
                if (isset($jawabanMap[$kluster][$namaPekerjaan])) {
                    $peringkat = $jawabanMap[$kluster][$namaPekerjaan];

                    // Masukkan peringkat ke dalam matrix
                    // Gunakan modulo untuk wrap-around
                    $targetKategori = $kategoriUrutan[$rowIndex % 12];
                    $matrix[$targetKategori][$kluster] = $peringkat;

                    $rowIndex++;
                }
            }
        }

        // 7. Hitung SUM (total horizontal) untuk setiap kategori
        $sum = [];
        foreach ($kategoriUrutan as $kategori) {
            $sum[$kategori] = array_sum($matrix[$kategori]);
        }

        // 8. Hitung RANK dengan aturan ties (+0.5)
        $rank = $this->hitungPeringkatDenganTies($sum);

        // 9. Hitung persentase (normalisasi dari SUM)
        // Rumus: % = ((max_possible_score - actual_score) / max_possible_score) * 100
        // max_possible_score = 9 kluster * 12 (peringkat maksimal) = 108
        $maxScore = 108;
        $percentage = [];
        foreach ($sum as $kategori => $skorTotal) {
            // Skor rendah = persentase tinggi (karena skor rendah = minat tinggi)
            $percentage[$kategori] = round((($maxScore - $skorTotal) / $maxScore) * 100, 1);
        }

        return [
            'matrix' => $matrix,
            'sum' => $sum,
            'rank' => $rank,
            'percentage' => $percentage,
            'kategori_urutan' => $kategoriUrutan,
            'kluster_urutan' => $klusterUrutan,
        ];
    }
}
