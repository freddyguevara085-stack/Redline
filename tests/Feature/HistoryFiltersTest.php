<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\History;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HistoryFiltersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_filters_histories_by_era_category_and_protagonist(): void
    {
        $categoryXix = Category::factory()->create([
            'name' => 'Siglo XIX',
            'slug' => 'siglo-xix',
        ]);

        $categoryXx = Category::factory()->create([
            'name' => 'Siglo XX',
            'slug' => 'siglo-xx',
        ]);

        History::factory()->for($categoryXix)->create([
            'title' => 'Historia Siglo XIX',
            'era' => 'Siglo XIX',
            'leading_figure' => 'Miguel de Larreynaga',
        ]);

        History::factory()->for($categoryXx)->create([
            'title' => 'Historia Siglo XX',
            'era' => 'Siglo XX',
            'leading_figure' => 'Augusto C. Sandino',
        ]);

        $response = $this->get(route('historia.index', [
            'category' => $categoryXix->slug,
            'era' => 'Siglo XIX',
            'protagonist' => 'Miguel de Larreynaga',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Historia Siglo XIX');
        $response->assertDontSee('Historia Siglo XX');
    }

    /** @test */
    public function it_searches_histories_by_keyword_across_fields(): void
    {
        $category = Category::factory()->create();

        History::factory()->for($category)->create([
            'title' => 'Historia del Lago',
            'excerpt' => 'Crónica ancestral del lago Cocibolca',
            'content' => 'El lago aparece como eje narrativo en toda la historia del país.',
        ]);

        History::factory()->for($category)->create([
            'title' => 'Crónica de la Montaña',
            'excerpt' => 'Narrativa de montaña y cafetales',
            'content' => 'Relato de cordilleras y cafetales históricos.',
        ]);

        $response = $this->get(route('historia.index', [
            'q' => 'lago',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Historia del Lago');
        $response->assertDontSee('Crónica de la Montaña');
    }
}
