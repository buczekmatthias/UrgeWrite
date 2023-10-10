import { defineStore } from "pinia";

export const useNotesStore = defineStore("notes", {
    state: () => {
        return {
            notes: {},
        };
    },
    getters: {
        getNotes: (state) => (name) => {
            return state.notes[name];
        },
    },
    actions: {
        setNotes(payload) {
            let group = payload.group;

            if (group in this.notes) {
                this.notes[group] = [...this.notes[group], ...payload.notes];
            } else {
                this.notes = { ...this.notes, group: payload.notes };
            }
        },
        updateNoteContent(payload) {
            this.notes[payload.group].find(
                (note) => note.id === payload.id
            ).content = payload.content;
        },
    },
});
