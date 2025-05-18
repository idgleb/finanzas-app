<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\User;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();

        if (!$admin) {
            $admin = User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'role' => 'admin',
                'plan' => 'pro',
            ]);
        }

        News::factory()->count(3)->state([
            'created_by' => $admin->id,
        ])->create();
    }
}
