<?php

namespace Database\Factories;

use App\Models\LibraryItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<LibraryItem>
 */
class LibraryItemFactory extends Factory
{
    protected $model = LibraryItem::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(5);

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'type' => $this->faker->randomElement(['pdf', 'image', 'video', 'link']),
            'description' => $this->faker->optional()->paragraph(),
            'cover_path' => null,
            'file_path' => null,
            'video_url' => null,
            'video_caption' => null,
            'external_url' => null,
        ];
    }

    public function withCover(): self
    {
        return $this->state(function () {
            $slug = Str::slug($this->faker->words(2, true));

            return [
                'cover_path' => 'library/covers/'.$slug.'.jpg',
            ];
        });
    }

    public function videoLink(): self
    {
        return $this->state(fn () => [
            'type' => 'video',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'video_caption' => $this->faker->sentence(),
        ]);
    }

    public function link(): self
    {
        return $this->state(fn () => [
            'type' => 'link',
            'external_url' => $this->faker->url(),
        ]);
    }
}
