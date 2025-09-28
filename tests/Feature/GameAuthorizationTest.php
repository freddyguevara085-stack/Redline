<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_is_redirected_when_trying_to_edit_a_game(): void
    {
        $game = Game::factory()->create();

        $response = $this->get(route('juegos.edit', $game));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function non_owner_cannot_access_question_management(): void
    {
        $owner = User::factory()->create();
        $game = Game::factory()->create(['user_id' => $owner->id]);
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)->get(route('juegos.questions', $game));

        $response->assertForbidden();
    }

    /** @test */
    public function owner_can_manage_questions(): void
    {
        $owner = User::factory()->create();
        $game = Game::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($owner)->get(route('juegos.questions', $game));

        $response->assertOk();
    }
}
