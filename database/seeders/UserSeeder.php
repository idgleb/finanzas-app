<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario administrador con acceso completo
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'plan' => 'pro',
            'zona_horaria' => 'America/Argentina/Buenos_Aires',
        ]);

        // Usuario premium con plan PRO
        User::factory()->create([
            'name' => 'Gleb PRO',
            'email' => 'glebpro@example.com',
            'password' => Hash::make('gleb123'),
            'role' => 'user',
            'plan' => 'pro',
            'zona_horaria' => 'America/Argentina/Buenos_Aires',
        ]);

        // Usuarios de prueba con plan gratuito
        User::factory()->create([
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'plan' => 'free',
            'zona_horaria' => 'America/Argentina/Buenos_Aires',
        ]);

        User::factory()->create([
            'name' => 'María García',
            'email' => 'maria@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'plan' => 'free',
            'zona_horaria' => 'America/Argentina/Buenos_Aires',
        ]);

        User::factory()->create([
            'name' => 'Carlos López',
            'email' => 'carlos@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'plan' => 'free',
            'zona_horaria' => 'America/Argentina/Buenos_Aires',
        ]);
    }
}
