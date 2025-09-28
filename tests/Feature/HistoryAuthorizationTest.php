<?php

namespace Tests\Feature;

use App\Models\History;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HistoryAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_when_trying_to_edit_a_history(): void
    {
        $history = History::factory()->create();

        $this->get(route('historia.edit', $history))
            ->assertRedirect(route('login'));
    }

    public function test_non_owner_cannot_edit_history(): void
    {
        $owner = User::factory()->create();
        $history = History::factory()->for($owner, 'author')->create();

        $this->actingAs(User::factory()->create())
            ->get(route('historia.edit', $history))
            ->assertForbidden();
    }

    public function test_owner_can_edit_history(): void
    {
        $owner = User::factory()->create();
        $history = History::factory()->for($owner, 'author')->create();

        $this->actingAs($owner)
            ->get(route('historia.edit', $history))
            ->assertOk();
    }
}
