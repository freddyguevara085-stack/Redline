<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\History;
use Illuminate\Support\Str;

class HistorySeeder extends Seeder
{
    
    public function run(): void
    {
        $historias = [
            [
                'title' => 'La leyenda de la Carreta Nagua',
                'excerpt' => 'Un relato popular nicaragüense sobre la carreta misteriosa que aparece en las noches...',
                'content' => 'Cuenta la tradición que la Carreta Nagua es un presagio de muerte y desgracia, recorría las calles silenciosas de los pueblos...',
                'user_id' => 1,
                'slug' => Str::slug('La leyenda de la Carreta Nagua') . '-' . uniqid(),
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'published_at' => now(),
            ],
            [
                'title' => 'El Güegüense: Patrimonio cultural',
                'excerpt' => 'Una de las obras teatrales más antiguas y representativas de Nicaragua...',
                'content' => 'El Güegüense es una obra satírica que combina teatro, danza y música, declarada Patrimonio Oral e Intangible de la Humanidad...',
                'user_id' => 1,
                'slug' => Str::slug('El Güegüense: Patrimonio cultural') . '-' . uniqid(),
                'video_url' => 'https://youtu.be/9bZkp7q19f0',
                'published_at' => now(),
            ],
            [
                'title' => 'Historias de Granada colonial',
                'excerpt' => 'Recuerdos de las calles empedradas y las leyendas que aún viven en la ciudad más antigua...',
                'content' => 'Granada guarda secretos en cada esquina, desde piratas que atacaban el puerto hasta casas embrujadas con siglos de historia...',
                'user_id' => 1,
                'slug' => Str::slug('Historias de Granada colonial') . '-' . uniqid(),
                'video_url' => 'https://vimeo.com/76979871',
                'published_at' => now(),
            ],
        ];

        foreach ($historias as $h) {
            History::create($h);
        }
    }
}