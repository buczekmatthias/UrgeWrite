<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TaskGroup;
use App\Services\TaskGroupServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskGroupController extends Controller
{
    public function getTaskGroups(): JsonResponse
    {
        return response()->json(['taskGroups' => TaskGroupServices::getUserTaskGroups(auth()->user())], 200);
    }

    public function getTaskGroup(TaskGroup $taskGroup): JsonResponse
    {
        return response()->json(['tasks' => TaskGroupServices::getTaskGroupTasks($taskGroup)], 200);
    }

    public function addTaskGroup(Request $request): JsonResponse
    {
        $valid = $request->validate([
            'name' => 'required|string|unique:task_groups,name'
        ]);

        if ($valid) {
            $response = TaskGroupServices::createTaskGroup(auth()->user(), $valid['name']);

            if (isset($response[0]['group'])) {
                return response()->json($response[0], $response['code']);
            } else {
                return response()->json(['message' => $response['message']], $response['code']);
            }
        }

        return response()->json(['message' => 'Provided input is invalid'], 400);
    }

    public function deleteTaskGroup(TaskGroup $taskGroup): JsonResponse
    {
        try {
            $taskGroup->tasks()->delete();
            $taskGroup->delete();

            return response()->json(['message' => 'Group and related tasks has been deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong while deleting group. Try again later. ' . $e->getMessage()], 500);
        }
    }

    public function updateTaskGroup(Request $request, TaskGroup $taskGroup): JsonResponse
    {
        $valid = $request->validate([
            'name' => 'string|required|unique:task_groups,name'
        ]);

        if ($valid) {
            try {
                $taskGroup->update($valid);

                return response()->json(['message' => 'Group has been successfully updated', 'group' => ['id' => $taskGroup->id, 'name' => $taskGroup->name]], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Something wen wrong while updating group. Try again later.'], 500);
            }
        }

        return response()->json(['message' => 'Provided input is invalid'], 400);
    }
}
