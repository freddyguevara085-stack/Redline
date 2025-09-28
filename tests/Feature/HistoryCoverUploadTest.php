<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\History;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Tests\TestCase;

class HistoryCoverUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_rejects_cover_with_small_dimensions(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $initialCount = History::count();

        $response = $this->actingAs($user)->post(route('historia.store'), [
            'title' => 'Historia con miniatura inválida',
            'content' => 'Contenido obligatorio para crear la historia.',
            'cover' => UploadedFile::fake()->image('tiny.jpg', 400, 300),
        ]);

        $response->assertSessionHasErrors('cover');
        $this->assertSame($initialCount, History::count());
    }

    public function test_it_processes_and_stores_cover_image(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->post(route('historia.store'), [
            'title' => 'Historia con portada',
            'content' => str_repeat('Contenido ', 20),
            'category_id' => $category->id,
            'cover' => UploadedFile::fake()->image('cover.png', 2000, 1500)->size(1024),
        ]);

        $response->assertRedirect();
        $history = History::where('title', 'Historia con portada')->first();

        $this->assertNotNull($history);
        $this->assertTrue(Str::endsWith($history->cover_path, '.jpg'));

        Storage::disk('public')->assertExists(ltrim($history->cover_path, '/'));

        $image = Image::make(Storage::disk('public')->get(ltrim($history->cover_path, '/')));
        $this->assertLessThanOrEqual(1920, $image->width());
        $this->assertLessThanOrEqual(1080, $image->height());
    }

    public function test_updating_cover_replaces_previous_file(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $history = History::factory()->for($user, 'author')->create([
            'cover_path' => 'covers/original.jpg',
        ]);

        Storage::disk('public')->put($history->cover_path, 'original-image');

        $response = $this->actingAs($user)->put(route('historia.update', $history), [
            'title' => 'Historia actualizada',
            'content' => str_repeat('Nuevo contenido ', 10),
            'cover' => UploadedFile::fake()->image('new-cover.jpg', 1800, 1200)->size(1500),
        ]);

        $response->assertRedirect();

        Storage::disk('public')->assertMissing('covers/original.jpg');

        $history->refresh();
        Storage::disk('public')->assertExists(ltrim($history->cover_path, '/'));
        $this->assertTrue(Str::endsWith($history->cover_path, '.jpg'));
    }

    public function test_cover_route_returns_processed_image(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $history = History::factory()->for($user, 'author')->create([
            'cover_path' => 'covers/example.jpg',
        ]);

        $fakeImage = UploadedFile::fake()->image('example.jpg', 1200, 900);
        Storage::disk('public')->put($history->cover_path, file_get_contents($fakeImage->getRealPath()));

        $response = $this->get(route('historia.cover', $history));

        $response->assertOk();
        $response->assertHeader('Cache-Control', 'max-age=604800, public');
        $response->assertHeader('Content-Type', 'image/jpeg');
    }
}
