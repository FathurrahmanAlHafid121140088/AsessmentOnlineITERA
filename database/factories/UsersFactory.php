<?php

namespace Database\Factories;

use App\Models\Users;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UsersFactory extends Factory
{
    /**
     * Tentukan model yang sesuai dengan factory ini.
     *
     * @var string
     */
    protected $model = Users::class;

    /**
     * Definisikan status default dari model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Di sinilah kita memberitahu factory cara membuat data dummy
        return [
            // 'nim' adalah primary key. Kita buat 9 digit angka unik.
            'nim' => $this->faker->unique()->numerify('#########'),

            // Kolom 'name' yang wajib diisi
            'name' => $this->faker->name(),

            // Kolom 'email' yang wajib diisi
            'email' => $this->faker->unique()->safeEmail(),

            // Kolom 'password' yang wajib diisi
            'password' => bcrypt('password'), // 'password' sederhana sudah cukup untuk tes

            // 'google_id' bisa kita isi acak
            'google_id' => Str::random(20),
        ];
    }
}
