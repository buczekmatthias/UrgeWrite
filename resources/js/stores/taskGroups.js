import { defineStore } from "pinia";
import { useLocalStorage } from "@vueuse/core";

export const useTaskGroupsStore = defineStore("tasksGroups", {
    state: () => {
        return {
            taskGroups: useLocalStorage("taskGroups", "[]"),
        };
    },
    getters: {
        getTaskGroups: (state) => () => {
            return JSON.parse(state.taskGroups);
        },
    },
    actions: {
        setTaskGroups(payload) {
            this.taskGroups = JSON.stringify([
                ...this.getTaskGroups(),
                ...payload.groups,
            ]);
        },
        resetTaskGroups() {
            this.taskGroups = JSON.stringify([]);
        },
    },
});
