<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
            RedlineSeeder::class,
            RedlineContentSeeder::class,
        ]);

        // Datos adicionales generados por factories para entornos de prueba
        \App\Models\User::factory(30)->create();
        \App\Models\Game::factory(10)->create();
        \App\Models\Event::factory(50)->create();
        \App\Models\History::factory(100)->create();
        \App\Models\UserScore::factory(30)->create();
    }
}
