import { defineStore } from "pinia";

export const useTaskGroupsStore = defineStore("tasksGroups", {
    state: () => {
        return {
            taskGroups: [],
        };
    },
    getters: {
        getTasks: (state) => () => {
            return state.taskGroups;
        },
    },
    actions: {
        setTasks(payload) {
            this.taskGroups = [...this.taskGroups, ...payload.taskGroups];
        },
        addTaskGroup(payload) {
            this.taskGroups.push(payload.group);
        },
    },
});
