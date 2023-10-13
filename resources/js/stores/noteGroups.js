import { defineStore } from "pinia";
import { useLocalStorage } from "@vueuse/core";

export const useNoteGroupsStore = defineStore("notesGroups", {
    state: () => {
        return {
            noteGroups: useLocalStorage("noteGroups", "[]"),
        };
    },
    getters: {
        getNoteGroups: (state) => () => {
            return JSON.parse(state.noteGroups);
        },
    },
    actions: {
        setNoteGroups(payload) {
            this.noteGroups = JSON.stringify([
                ...this.getNoteGroups(),
                ...payload.groups,
            ]);
        },
        resetNoteGroups() {
            this.noteGroups = JSON.stringify([]);
        },
    },
});
