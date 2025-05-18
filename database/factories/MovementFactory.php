<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movement>
 */
class MovementFactory extends Factory
{
    public function definition(): array
    {
        $tipo = $this->faker->randomElement(['ingreso', 'gasto']);

        return [
            'user_id' => User::factory(),
            'tipo' => $tipo,
            'monto' => $this->faker->randomFloat(2, 100, 5000),
            'categoria_id' => Category::factory()->state([
                'tipo' => $tipo,
            ]),
            'fecha' => $this->faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
        ];
    }
}

