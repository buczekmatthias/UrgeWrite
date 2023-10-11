<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NotePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function access(User $user, Note $note)
    {
        return $user->username === $note->group->owner->username ? Response::allow()
            : Response::denyWithStatus(403, "You don't own the resource");
    }
}
