<?php

namespace App\Policies;

use App\Models\LexReflex;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LexReflexPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('manage_lexicon');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LexReflex $lexReflex): bool
    {
        return $user->can('manage_lexicon');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('manage_lexicon');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LexReflex $lexReflex): bool
    {
        return $user->can('manage_lexicon');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LexReflex $lexReflex): bool
    {
        return $user->can('manage_lexicon');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LexReflex $lexReflex): bool
    {
        return $user->can('manage_lexicon');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LexReflex $lexReflex): bool
    {
        return $user->can('manage_lexicon');
    }
}
