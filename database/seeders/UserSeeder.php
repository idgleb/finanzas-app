<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Asegurarnos de que los planes existan
        $planPro = Plan::where('code', 'pro')->first();
        $planFree = Plan::where('code', 'free')->first();

        if (!$planPro || !$planFree) {
            throw new \Exception('Los planes necesarios no existen. Ejecute primero el PlanSeeder.');
        }

        // Usuario administrador con acceso completo
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'plan_id' => $planPro->id,
            'zona_horaria' => 'America/Argentina/Buenos_Aires',
        ]);

        // Usuario premium con plan PRO
        User::factory()->create([
            'name' => 'Gleb PRO',
            'email' => 'glebpro@example.com',
            'password' => Hash::make('gleb123'),
            'role' => 'user',
            'plan_id' => $planPro->id,
            'zona_horaria' => 'America/Argentina/Buenos_Aires',
        ]);

        // Usuarios de prueba con plan gratuito
        User::factory()->create([
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'plan_id' => $planFree->id,
            'zona_horaria' => 'America/Argentina/Buenos_Aires',
        ]);

        User::factory()->create([
            'name' => 'María García',
            'email' => 'maria@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'plan_id' => $planFree->id,
            'zona_horaria' => 'America/Argentina/Buenos_Aires',
        ]);

        User::factory()->create([
            'name' => 'Carlos López',
            'email' => 'carlos@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'plan_id' => $planFree->id,
            'zona_horaria' => 'America/Argentina/Buenos_Aires',
        ]);
    }
}
