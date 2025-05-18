<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence(),
            'contenido' => $this->faker->paragraph(),
            'imagen' => 'images/demo.png',
            'fecha' => now(),
            'created_by' => User::factory(),
        ];
    }
}
