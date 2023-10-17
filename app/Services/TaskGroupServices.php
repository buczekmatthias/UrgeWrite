<?php

namespace App\Services;

use App\Models\TaskGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TaskGroupServices
{
    public static function getUserTaskGroups(User $user): Collection
    {
        return $user->taskGroups()->orderBy('created_at', 'ASC')->select('id', 'name')->withCount('tasks')->get();
    }

    public static function getTaskGroupTasks(TaskGroup $taskGroup): Collection
    {
        return $taskGroup->tasks()->orderBy('created_at', 'DESC')->select('id', 'isDone', 'content')->get();
    }

    public static function createTaskGroup(User $user, string $name): array
    {
        try {
            $group = $user->taskGroups()->create(['name' => $name]);

            return [['message' => 'Group successfully added to your collection', 'group' => ['id' => $group->id, 'name' => $group->name, 'tasks_count' => 0]], 'code' => 201];
        } catch (\Exception $e) {
            return ['message' => "Couldn't add group. Try again later.", 'code' => 500];
        }
    }
}
