<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\History;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<History>
 */
class HistoryFactory extends Factory
{
    protected $model = History::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(5);
        $categoryId = Category::query()->inRandomOrder()->value('id');

        return [
            'user_id' => User::factory(),
            'category_id' => $categoryId ?? Category::factory(),
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(6),
            'excerpt' => $this->faker->sentence(18),
            'content' => $this->faker->paragraphs(5, true),
            'cover_path' => 'covers/' . Str::slug($this->faker->words(2, true)) . '.jpg',
            'video_url' => $this->faker->optional(0.35)->randomElement([
                'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'https://youtu.be/9bZkp7q19f0',
                'https://vimeo.com/76979871',
            ]),
            'era' => $this->faker->randomElement([
                'Período precolombino',
                'Época colonial',
                'Siglo XIX',
                'Siglo XX',
                'Nicaragua contemporánea',
            ]),
            'leading_figure' => $this->faker->name(),
            'published_at' => $this->faker->optional()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
