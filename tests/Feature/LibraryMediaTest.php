<?php

namespace Tests\Feature;

use App\Models\LibraryItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Tests\TestCase;

class LibraryMediaTest extends TestCase
{
    use RefreshDatabase;

    public function test_video_requires_a_file_or_valid_url(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('biblioteca.store'), [
            'title' => 'Video sin fuente',
            'type' => 'video',
            'description' => 'Un recurso sin archivo ni enlace.',
        ]);

        $response->assertSessionHasErrors('video_url');
        $this->assertSame(0, LibraryItem::count());
    }

    public function test_video_validates_supported_platforms(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('biblioteca.store'), [
            'title' => 'Video con URL no soportada',
            'type' => 'video',
            'description' => 'Intento con proveedor no permitido.',
            'video_url' => 'https://example.com/videos/123',
        ]);

        $response->assertSessionHasErrors('video_url');
        $this->assertSame(0, LibraryItem::count());
    }

    public function test_video_with_youtube_link_and_cover_is_stored(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $cover = UploadedFile::fake()->image('portada.png', 1600, 1000)->size(1024);

        $response = $this->actingAs($user)->post(route('biblioteca.store'), [
            'title' => 'Documental sobre artesanía',
            'type' => 'video',
            'description' => 'Serie de videos sobre artesanía local.',
            'video_url' => 'https://youtu.be/9bZkp7q19f0',
            'video_caption' => 'Video con interpretación en lengua de señas.',
            'cover' => $cover,
        ]);

        $response->assertRedirect();

        $item = LibraryItem::first();

        $this->assertNotNull($item);
        $this->assertSame('https://youtu.be/9bZkp7q19f0', $item->video_url);
        $this->assertSame('https://www.youtube.com/embed/9bZkp7q19f0', $item->video_embed_url);
        $this->assertEquals('video', $item->type);
        $this->assertNotNull($item->cover_path);

        Storage::disk('public')->assertExists($item->cover_path);
        $image = Image::make(Storage::disk('public')->get($item->cover_path));
        $this->assertLessThanOrEqual(1920, $image->width());
        $this->assertLessThanOrEqual(1080, $image->height());
    }

    public function test_link_type_requires_external_url(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $initialCount = LibraryItem::count();

        $response = $this->actingAs($user)->post(route('biblioteca.store'), [
            'title' => 'Recurso sin enlace',
            'type' => 'link',
            'description' => 'Debe incluir un enlace externo.',
        ]);

        $response->assertSessionHasErrors('external_url');
        $this->assertSame($initialCount, LibraryItem::count());
    }

    public function test_show_view_renders_video_embed(): void
    {
        $item = LibraryItem::factory()->create([
            'type' => 'video',
            'video_url' => 'https://www.youtube.com/watch?v=abc123XYZ89',
            'video_caption' => 'Descripción accesible del contenido audiovisual.',
        ]);

        $response = $this->get(route('biblioteca.show', $item));

        $response->assertOk();
        $response->assertSee('iframe', false);
        $response->assertSee('https://www.youtube.com/embed/abc123XYZ89', false);
        $response->assertSee('Descripción accesible del contenido audiovisual.', false);
    }
}
