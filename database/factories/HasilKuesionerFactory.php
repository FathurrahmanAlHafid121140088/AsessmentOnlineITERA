<?php

namespace Database\Factories;

use App\Models\HasilKuesioner;
use Illuminate\Database\Eloquent\Factories\Factory;

class HasilKuesionerFactory extends Factory
{
    protected $model = HasilKuesioner::class;

    public function definition()
    {
        $skor = $this->faker->numberBetween(38, 226);
        $kategori = $this->getKategori($skor);

        return [
            'nim' => '121140010', // Disesuaikan dengan factory DataDiris di unit test agar relasi valid
            'total_skor' => $skor,
            'kategori' => $kategori,
        ];
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
