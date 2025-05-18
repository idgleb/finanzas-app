<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $tipo = $this->faker->randomElement(['gasto', 'ingreso']);

        $categorias = [
            'gasto' => [
                ['nombre' => 'Comida', 'icono' => '🍔'],
                ['nombre' => 'Transporte', 'icono' => '🚗'],
                ['nombre' => 'Alquiler', 'icono' => '🏠'],
                ['nombre' => 'Salud', 'icono' => '💊'],
                ['nombre' => 'Entretenimiento', 'icono' => '🎮'],
                ['nombre' => 'Ropa', 'icono' => '👕'],
                ['nombre' => 'Educación', 'icono' => '📚'],
            ],
            'ingreso' => [
                ['nombre' => 'Sueldo', 'icono' => '💼'],
                ['nombre' => 'Freelance', 'icono' => '🧑‍💻'],
                ['nombre' => 'Inversiones', 'icono' => '📈'],
                ['nombre' => 'Regalos', 'icono' => '🎁'],
                ['nombre' => 'Venta de productos', 'icono' => '🛒'],
            ],
        ];

        $item = collect($categorias[$tipo])->random();

        return [
            'nombre' => $item['nombre'],
            'icono' => $item['icono'],
            'tipo' => $tipo,
            'user_id' => User::factory(),
            'activa' => true,
        ];
    }
}

