<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\NoteGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function getNote(NoteGroup $noteGroup, Note $note): JsonResponse
    {
        return response()->json(['note' => ['id' => $note->id, 'title' => $note->title, 'content' => $note->content, 'created_at' => $note->created_at]], 200);
    }

    public function addNote(Request $request, NoteGroup $noteGroup): JsonResponse
    {
        $valid = $request->validate([
            'title' => 'string',
            'content' => 'required|string',
        ]);

        if ($valid) {
            try {
                $note = $noteGroup->notes()->create($request->merge(['note_group_id' => $noteGroup->id])->toArray());

                return response()->json(['message' => 'Successfully created new note', 'note' => ['id' => $note->id, 'title' => $note->title, 'content' => $note->content, 'created_at' => $note->created_at]], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Something went wrong while creating new note. Try again later' . $e->getMessage()], 500);
            }
        }
    }

    public function deleteNote(NoteGroup $noteGroup, Note $note): JsonResponse
    {
        try {
            $note->delete();

            return response()->json(['message' => 'Note has been deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong while deleting note. Try again later. ' . $e->getMessage()], 500);
        }
    }

    public function updateNote(Request $request, NoteGroup $noteGroup, Note $note): JsonResponse
    {
        $valid = $request->validate([
            'title' => 'string',
            'content' => 'required|string',
        ]);

        if ($valid) {
            try {
                $note->update($valid);

                return response()->json(['message' => 'Note has been successfully updated', 'note' => ['id' => $note->id, 'title' => $note->title, 'content' => $note->content, 'created_at' => $note->created_at]], 200);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Something wen wrong while updating note. Try again later.'], 500);
            }
        }
    }
}
