<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function access(User $user, Task $task)
    {
        return $user->username === $task->group->owner->username ? Response::allow()
            : Response::denyWithStatus(403, "You don't own the resource");
    }
}
