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
        getNoteGroupName: (state) => (id) =>
            JSON.parse(state.noteGroups).find((group) => group.id === id).name,
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
        decreaseNoteCount(payload) {
            let groups = this.getNoteGroups();

            groups.find((group) => group.id === payload.id).notes_count -= 1;

            this.noteGroups = JSON.stringify(groups);
        },
    },
});
