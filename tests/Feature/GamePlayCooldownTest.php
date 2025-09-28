<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Option;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GamePlayCooldownTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    /** @test */
    public function cooldown_blocks_immediate_resubmission(): void
    {
        $user = User::factory()->create();
    $game = Game::factory()->create(['points_per_question' => 10]);
        $question = Question::factory()->create(['game_id' => $game->id]);
        $correctOption = Option::factory()->create([
            'question_id' => $question->id,
            'is_correct' => true,
        ]);
        $wrongOption = Option::factory()->create([
            'question_id' => $question->id,
            'is_correct' => false,
        ]);

        $payload = [
            "q{$question->id}" => $correctOption->id,
        ];

        $firstResponse = $this->actingAs($user)
            ->from(route('juegos.play', $game))
            ->post(route('juegos.submit', $game), $payload);

        $firstResponse->assertStatus(200);
        $firstResponse->assertViewIs('juegos.result');
        $firstResponse->assertViewHasAll([
            'game', 'score', 'total', 'correct',
        ]);

        $secondResponse = $this->actingAs($user)
            ->from(route('juegos.play', $game))
            ->post(route('juegos.submit', $game), $payload);

        $secondResponse->assertRedirect(route('juegos.play', $game));
        $secondResponse->assertSessionHasErrors('cooldown');

        $this->assertDatabaseCount('game_attempts', 1);
        $this->assertDatabaseHas('game_attempts', [
            'game_id' => $game->id,
            'user_id' => $user->id,
            'score' => 10,
            'correct_answers' => 1,
            'total_questions' => 1,
        ]);
    }
}
