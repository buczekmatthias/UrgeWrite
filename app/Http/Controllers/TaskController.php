<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function getTask(TaskGroup $taskGroup, Task $task): JsonResponse
    {
        return response()->json(['task' => ['id' => $task->id, 'isDone' => $task->isDone, 'content' => $task->content, 'task_group_id' => $task->group->id]], 200);
    }

    public function addTask(Request $request, TaskGroup $taskGroup): JsonResponse
    {
        // TODO: Switch to policies later
        if ($taskGroup->owner->username !== auth()->user()->username) {
            return response()->json(['message' => "You don't own this collection"], 403);
        }

        $valid = $request->validate([
            'content' => 'required|string',
            'isDone' => 'required|boolean'
        ]);

        if ($valid) {
            try {
                $task = $taskGroup->tasks()->create($request->merge(['task_group_id' => $taskGroup->id])->toArray());

                return response()->json(['message' => 'Successfully created new task', 'task' => ['id' => $task->id, 'isDone' => $task->isDone, 'content' => $task->content, 'task_group_id' => $task->group->id]], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Something went wrong while creating new task. Try again later' . $e->getMessage()], 500);
            }
        }
    }

    public function deleteTask(TaskGroup $taskGroup, Task $task): JsonResponse
    {
        try {
            if ($taskGroup->owner->username === auth()->user()->username) {
                $task->delete();

                return response()->json(['message' => 'Task has been deleted successfully'], 200);
            } else {
                return response()->json(['message' => "You don't own this task."], 403);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong while deleting task. Try again later. ' . $e->getMessage()], 500);
        }
    }

    public function updateTask(Request $request, TaskGroup $taskGroup, Task $task): JsonResponse
    {
        $valid = $request->validate([
            'content' => 'required|string',
            'isDone' => 'required|boolean',
            'task_group_id' => 'required|string|exists:task_groups,id'
        ]);

        if ($valid) {
            try {
                $task->update($valid);

                return response()->json(['message' => 'Task has been successfully updated', 'task' => ['id' => $task->id, 'isDone' => $task->isDone, 'content' => $task->content, 'task_group_id' => $task->group->id]], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Something wen wrong while updating task. Try again later.'], 500);
            }
        }
    }
}
