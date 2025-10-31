<?php

namespace Database\Factories;

use App\Models\RiwayatKeluhans;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RiwayatKeluhans>
 */
class RiwayatKeluhansFactory extends Factory
{
    /**
     * Tentukan model yang sesuai dengan factory ini.
     *
     * @var string
     */
    protected $model = RiwayatKeluhans::class;

    /**
     * Definisikan status default dari model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Di sinilah kita mendefinisikan resep data dummy
        // untuk semua kolom yang wajib diisi.
        return [
            // Tes Anda akan mengisi 'nim' secara manual.
            // Kita bisa menggunakan 'Users::factory()' sebagai default
            // jika factory ini dipanggil tanpa 'nim'.
            'nim' => \App\Models\Users::factory(),

            'keluhan' => $this->faker->sentence(10), // Buat kalimat dummy
            'lama_keluhan' => $this->faker->randomElement(['1 Bulan', '2-3 Bulan', '6 Bulan']),
            'pernah_konsul' => $this->faker->randomElement(['Ya', 'Tidak']),
            'pernah_tes' => $this->faker->randomElement(['Ya', 'Tidak']),
        ];
    }
}
