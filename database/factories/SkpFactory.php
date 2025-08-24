<?php

namespace Database\Factories;

use App\Models\Skp;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkpFactory extends Factory
{
    protected $model = Skp::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'jenis' => $this->faker->word(),
            'kode' => $this->faker->unique()->numerify('SKP###'),
            'nama' => $this->faker->word(),
            'bulan' => $this->faker->monthName(),
            'tahun' => $this->faker->year(),
            'link' => $this->faker->url(),
            'konten' => $this->faker->sentence(),
        ];
    }
}
