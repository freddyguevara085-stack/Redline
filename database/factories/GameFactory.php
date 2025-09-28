<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    protected $model = Game::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->unique()->sentence(3),
            'description' => $this->faker->paragraph(),
            'points_per_question' => $this->faker->randomElement([5, 10, 15, 20]),
            'type' => $this->faker->randomElement(['trivia','ruleta','juicio','memoria']),
        ];
    }
}
