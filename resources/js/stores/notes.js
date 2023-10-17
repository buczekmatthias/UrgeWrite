import { defineStore } from "pinia";
import { useLocalStorage } from "@vueuse/core";

export const useNotesStore = defineStore("notes", {
    state: () => {
        return {
            notes: useLocalStorage("notes", "{}"),
        };
    },
    getters: {
        getNotes: (state) => (groupId) => JSON.parse(state.notes)[groupId],
        getNote: (state) => (groupId, noteId) =>
            JSON.parse(state.notes)[groupId].find((note) => note.id === noteId),
    },
    actions: {
        setNotes(payload) {
            let notes = JSON.parse(this.notes);

            if (notes[payload.groupId]) {
                notes[payload.groupId] = [
                    ...payload.notes,
                    ...notes[payload.groupId],
                ];
            } else {
                notes[payload.groupId] = payload.notes;
            }

            this.notes = JSON.stringify(notes);
        },
        updateNote(payload) {
            let notes = JSON.parse(this.notes);
            let note = notes[payload.groupId].find(
                (note) => note.id === payload.id
            );

            note.content = payload.content;
            note.title = payload.title;

            this.notes = JSON.stringify(notes);
        },
        resetNotes() {
            this.notes = JSON.stringify({});
        },
        removeNote(payload) {
            let notes = JSON.parse(this.notes);
            let group = notes[payload.groupId];
            let note = group.find((note) => note.id === payload.id);

            group.splice(group.indexOf(note), 1);

            this.notes = JSON.stringify(notes);
        },
        removeGroup(payload) {
            let notes = JSON.parse(this.notes);

            delete notes[payload.groupId];

            this.notes = JSON.stringify(notes);
        },
    },
});
