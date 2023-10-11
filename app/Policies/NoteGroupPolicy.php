<?php

namespace App\Policies;

use App\Models\NoteGroup;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NoteGroupPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }

    public function access(User $user, NoteGroup $noteGroup)
    {
        return $user->username === $noteGroup->owner->username ? Response::allow()
            : Response::denyWithStatus(403, "You don't own the resource");
    }
}
