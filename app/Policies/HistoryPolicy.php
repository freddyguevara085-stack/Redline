<?php

namespace App\Policies;

use App\Models\History;
use App\Models\User;

class HistoryPolicy
{
    /**
     * Anyone can view the list of histories.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Anyone can view a single history.
     */
    public function view(?User $user, History $history): bool
    {
        return true;
    }

    /**
     * Any authenticated user can create histories.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Only the owner or an admin can update.
     */
    public function update(User $user, History $history): bool
    {
        return $this->owns($user, $history);
    }

    /**
     * Only the owner or an admin can delete.
     */
    public function delete(User $user, History $history): bool
    {
        return $this->owns($user, $history);
    }

    protected function owns(User $user, History $history): bool
    {
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }

        return $user->id === (int) $history->user_id;
    }
}
