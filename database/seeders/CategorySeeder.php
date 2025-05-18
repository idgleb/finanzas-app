<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

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

        foreach ($users as $user) {
            foreach ($categorias as $tipo => $items) {
                foreach ($items as $item) {
                    Category::create([
                        'nombre' => $item['nombre'],
                        'icono' => $item['icono'],
                        'tipo' => $tipo,
                        'user_id' => $user->id,
                        'activa' => true,
                    ]);
                }
            }
        }
    }
}
