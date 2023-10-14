import { defineStore } from "pinia";
import { useLocalStorage } from "@vueuse/core";

export const useNotesStore = defineStore("notes", {
    state: () => {
        return {
            notes: useLocalStorage("notes", " []"),
        };
    },
    getters: {
        getNotes: (state) => (groupId) =>
            JSON.parse(state.notes).filter((note) => note.group_id === groupId),
        getNote: (state) => (noteId) =>
            JSON.parse(state.notes).find((note) => note.id === noteId),
    },
    actions: {
        setNotes(payload) {
            this.notes = JSON.stringify([
                ...JSON.parse(this.notes),
                ...payload.notes,
            ]);
        },
        updateNoteContent(payload) {
            let note = this.getNotes(payload.groupId).find(
                (note) => note.id === payload.id
            );
            note.content = payload.content;
            note.title = payload.title;
            note.note_group_id = payload.groupId;
        },
        resetNotes() {
            this.notes = JSON.stringify([]);
        },
        removeNote(payload) {
            let notes = JSON.parse(this.notes);
            let noteIndex = notes.findIndex((note) => note.id === payload.id);

            notes.splice(noteIndex, 1);

            this.notes = JSON.stringify(notes);
        },
    },
});
