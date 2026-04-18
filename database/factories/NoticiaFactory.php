<?php

namespace Database\Factories;

use App\Noticia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Noticia>
 */
class NoticiaFactory extends Factory
{
    protected $model = Noticia::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence(6),
            'bajada' => $this->faker->sentence(20),
            'autor' => $this->faker->name(),
            'fecha' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'texto' => $this->faker->paragraph(2),
            'volanta' => $this->faker->sentence(3),
        ];
    }
}
