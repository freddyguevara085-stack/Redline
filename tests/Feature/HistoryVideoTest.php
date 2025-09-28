<?php

namespace Tests\Feature;

use App\Models\History;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HistoryVideoTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_rejects_unsupported_video_url(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $initialCount = History::count();

        $response = $this->actingAs($user)->post(route('historia.store'), [
            'title' => 'Historia con video inválido',
            'content' => 'Contenido básico de la historia.',
            'video_url' => 'https://example.com/video/123',
        ]);

        $response->assertSessionHasErrors('video_url');
        $this->assertSame($initialCount, History::count());
    }

    public function test_it_allows_youtube_video_url(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('historia.store'), [
            'title' => 'Historia con video válido',
            'content' => 'Contenido suficiente para la historia.',
            'video_url' => 'https://www.youtube.com/watch?v=abc123XYZ89',
        ]);

        $response->assertRedirect();

        $history = History::where('title', 'Historia con video válido')->first();

        $this->assertNotNull($history);
        $this->assertSame('https://www.youtube.com/watch?v=abc123XYZ89', $history->video_url);
        $this->assertSame('https://www.youtube.com/embed/abc123XYZ89', $history->video_embed_url);
    }

    public function test_show_view_renders_video_embed(): void
    {
        $history = History::factory()->create([
            'title' => 'Historia con reproducción',
            'video_url' => 'https://youtu.be/9bZkp7q19f0',
        ]);

        $response = $this->get(route('historia.show', $history));

        $response->assertOk();
        $response->assertSee('iframe', false);
        $response->assertSee('https://www.youtube.com/embed/9bZkp7q19f0', false);
    }
}
