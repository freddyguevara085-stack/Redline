<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Badge;
use Illuminate\Support\Facades\Hash;

class RedlineSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuarios base
        User::firstOrCreate(
            ['email' => 'admin@redline.test'],
            ['name' => 'Admin', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        User::firstOrCreate(
            ['email' => 'docente@redline.test'],
            ['name' => 'Docente', 'password' => Hash::make('password'), 'role' => 'docente']
        );

        User::firstOrCreate(
            ['email' => 'estudiante@redline.test'],
            ['name' => 'Estudiante', 'password' => Hash::make('password'), 'role' => 'estudiante']
        );

        // Crear insignias
        Badge::firstOrCreate(
            ['name' => 'Explorador'],
            ['threshold' => 50, 'icon' => null, 'created_at' => now(), 'updated_at' => now()]
        );

        Badge::firstOrCreate(
            ['name' => 'Historiador'],
            ['threshold' => 150, 'icon' => null, 'created_at' => now(), 'updated_at' => now()]
        );
    }
}