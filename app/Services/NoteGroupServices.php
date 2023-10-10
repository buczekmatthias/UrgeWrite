<?php

namespace App\Services;

use App\Models\NoteGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class NoteGroupServices
{
    public static function getUserNoteGroups(User $user): Collection
    {
        return $user->noteGroups()->orderBy('created_at', 'ASC')->select('id', 'name')->withCount('notes')->get();
    }

    public static function getNoteGroupNotes(NoteGroup $noteGroup): Collection
    {
        return $noteGroup->notes()->orderBy('created_at', 'DESC')->select('id', 'title', 'content', 'note_group_id')->get();
    }

    public static function createNoteGroup(User $user, string $name): array
    {
        try {
            $group = $user->noteGroups()->create(['name' => $name]);

            return [['message' => 'Group successfully added to your collection', 'group' => ['id' => $group->id, 'name' => $group->name]], 'code' => 201];
        } catch (\Exception $e) {
            return ['message' => "Couldn't add group. Try again later.", 'code' => 500];
        }
    }
}
