<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run()
    {
        Plan::create([
            'name' => 'Plan Gratuito',
            'code' => 'free',
            'price' => 0.00,
            'description' => 'Plan básico con funcionalidades esenciales',
            'features' => [
                'Categorías básicas',
                'Iconos predeterminados',
                'Soporte por email'
            ],
            'is_active' => true
        ]);

        Plan::create([
            'name' => 'Plan PRO',
            'code' => 'pro',
            'price' => 1.00,
            'description' => 'Plan PRO para usuarios que necesitan funcionalidades avanzadas',
            'features' => [
                'Categorías personalizadas ilimitadas',
                'Iconos personalizados',
                'Gestión avanzada de categorías',
                'Soporte prioritario'
            ],
            'is_active' => true
        ]);
    }
} 