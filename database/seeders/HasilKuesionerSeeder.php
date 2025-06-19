<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HasilKuesioner;

class HasilKuesionerSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            $skor = rand(38, 226); // Skor acak dalam range total skor MHI-38
            $kategori = $this->getKategori($skor);

            HasilKuesioner::create([
                'nim' => '1211400' . $i,
                'total_skor' => $skor,
                'kategori' => $kategori,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function getKategori($skor)
    {
        if ($skor >= 191 && $skor <= 226) {
            return 'Sangat Baik (Sejahtera Secara Mental)';
        } elseif ($skor >= 161 && $skor <= 190) {
            return 'Baik (Sehat Secara Mental)';
        } elseif ($skor >= 131 && $skor <= 160) {
            return 'Sedang (Rentan)';
        } elseif ($skor >= 91 && $skor <= 130) {
            return 'Buruk (Distres Sedang)';
        } elseif ($skor >= 38 && $skor <= 90) {
            return 'Sangat Buruk (Distres Berat)';
        } else {
            return 'Tidak Diketahui';
        }
    }
}
