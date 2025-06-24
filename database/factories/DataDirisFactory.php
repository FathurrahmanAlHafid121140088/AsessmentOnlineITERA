<?php

namespace Database\Factories;

use App\Models\DataDiris;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataDirisFactory extends Factory
{
    protected $model = DataDiris::class;

    public function definition()
    {
        return [
            'nim' => $this->faker->unique()->numerify('1211400##'),
            'nama' => $this->faker->name,
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'alamat' => $this->faker->address,
            'usia' => $this->faker->numberBetween(18, 25),
            'fakultas' => 'Fakultas Sains',
            'program_studi' => 'Teknik Informatika',
            'email' => $this->faker->unique()->safeEmail,
        ];
    }
}
