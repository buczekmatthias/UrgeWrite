<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\NoteGroup;
use App\Services\NoteGroupServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NoteGroupController extends Controller
{
    public function getNoteGroups(): JsonResponse
    {
        return response()->json(['noteGroups' => NoteGroupServices::getUserNoteGroups(auth()->user())], 200);
    }

    public function getNoteGroup(NoteGroup $noteGroup): JsonResponse
    {
        return response()->json(['notes' => NoteGroupServices::getNoteGroupNotes($noteGroup)], 200);
    }

    public function addNoteGroup(Request $request): JsonResponse
    {
        $valid = $request->validate([
            'name' => 'required|string|unique:note_groups,name'
        ]);

        if ($valid) {
            $response = NoteGroupServices::createNoteGroup(auth()->user(), $valid['name']);

            if (isset($response[0]['group'])) {
                return response()->json($response[0], $response['code']);
            } else {
                return response()->json(['message' => $response['message']], $response['code']);
            }
        }

        return response()->json(['message' => 'Provided input is invalid'], 400);
    }

    public function deleteNoteGroup(NoteGroup $noteGroup): JsonResponse
    {
        try {
            $noteGroup->notes()->delete();
            $noteGroup->delete();

            return response()->json(['message' => 'Group and related notes has been deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong while deleting group. Try again later. ' . $e->getMessage()], 500);
        }
    }

    public function updateNoteGroup(Request $request, NoteGroup $noteGroup): JsonResponse
    {
        $valid = $request->validate([
            'name' => 'string|required|unique:note_groups,name'
        ]);

        if ($valid) {
            try {
                $noteGroup->update($valid);

                return response()->json(['message' => 'Group has been successfully updated', 'group' => ['id' => $noteGroup->id, 'name' => $noteGroup->name]], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Something wen wrong while updating group. Try again later.'], 500);
            }
        }

        return response()->json(['message' => 'Provided input is invalid'], 400);
    }
}
