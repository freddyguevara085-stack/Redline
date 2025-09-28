<?php

namespace App\Policies;

use App\Models\Game;
use App\Models\User;

class GamePolicy
{
    /**
     * Determine whether the user can view any games.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the game.
     */
    public function view(?User $user, Game $game): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create games.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the game.
     */
    public function update(User $user, Game $game): bool
    {
        return $this->ownsGame($user, $game);
    }

    /**
     * Determine whether the user can delete the game.
     */
    public function delete(User $user, Game $game): bool
    {
        return $this->ownsGame($user, $game);
    }

    /**
     * Determine whether the user can manage questions and options for the game.
     */
    public function manageQuestions(User $user, Game $game): bool
    {
        return $this->ownsGame($user, $game);
    }

    protected function ownsGame(User $user, Game $game): bool
    {
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }

        return $user->id === $game->user_id;
    }
}
