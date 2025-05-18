<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Movement;
use Carbon\Carbon;

class MovementSeeder extends Seeder
{
    public function run(): void
    {
        // Asegurarse de que existan usuarios y categorías
        if (User::count() === 0) {
            User::factory(3)->create();
        }

        if (Category::count() === 0) {
            $this->call(CategorySeeder::class);
        }

        // Generar movimientos por usuario
        $usuarios = User::all();

        foreach ($usuarios as $usuario) {
            foreach (['ingreso', 'gasto'] as $tipo) {
                $categorias = Category::where('user_id', $usuario->id)
                    ->where('tipo', $tipo)
                    ->pluck('id');

                if ($categorias->isEmpty()) {
                    continue;
                }

                // Crear 3 movimientos de cada tipo
                for ($i = 0; $i < 3; $i++) {
                    // Generar una fecha aleatoria en los últimos 30 días
                    $fecha = Carbon::now()->subDays(rand(0, 30))->setHour(rand(8, 20))->setMinute(rand(0, 59));
                    
                    Movement::create([
                        'user_id' => $usuario->id,
                        'tipo' => $tipo,
                        'monto' => fake()->randomFloat(2, 50, 5000),
                        'categoria_id' => $categorias->random(),
                        'fecha' => $fecha->setTimezone('UTC'),
                        'created_at' => $fecha,
                        'updated_at' => $fecha,
                    ]);
                }
            }
        }
    }
}
