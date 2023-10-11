<?php

namespace App\Policies;

use App\Models\TaskGroup;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskGroupPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function access(User $user, TaskGroup $taskGroup)
    {
        return $user->username === $taskGroup->owner->username ? Response::allow()
            : Response::denyWithStatus(403, "You don't own the resource");
    }
}
