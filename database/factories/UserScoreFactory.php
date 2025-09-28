<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\User;
use App\Models\UserScore;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserScore>
 */
class UserScoreFactory extends Factory
{
    protected $model = UserScore::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'game_id' => $this->faker->boolean(80) ? Game::factory() : null,
            'points' => $this->faker->numberBetween(0, 300),
            'score' => $this->faker->numberBetween(0, 1000),
            'quizzes_taken' => $this->faker->numberBetween(0, 25),
            'games_played' => $this->faker->numberBetween(0, 20),
            'source' => $this->faker->optional()->randomElement(['quiz', 'trivia', 'memoria', 'ranking']),
        ];
    }
}
