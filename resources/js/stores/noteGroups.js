import { defineStore } from "pinia";

export const useNoteGroupsStore = defineStore("notesGroups", {
    state: () => {
        return {
            noteGroups: [],
        };
    },
    getters: {
        getNoteGroup: (state) => () => {
            return state.noteGroups;
        },
    },
    actions: {
        setNoteGroup(payload) {
            this.noteGroups = [...this.noteGroups, ...payload.noteGroups];
        },
        addNoteGroup(payload) {
            this.noteGroups.push(payload.group);
        },
    },
});
