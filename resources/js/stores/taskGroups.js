import { defineStore } from "pinia";
import { useLocalStorage } from "@vueuse/core";

export const useTaskGroupsStore = defineStore("tasksGroups", {
    state: () => {
        return {
            taskGroups: useLocalStorage("taskGroups", "[]"),
        };
    },
    getters: {
        getTaskGroups: (state) => () => JSON.parse(state.taskGroups),
        getTaskGroupName: (state) => (id) =>
            JSON.parse(state.taskGroups).find((group) => group.id === id)?.name,
    },
    actions: {
        setTaskGroups(payload) {
            this.taskGroups = JSON.stringify([
                ...this.getTaskGroups(),
                ...payload.groups,
            ]);
        },
        deleteTaskGroup(payload) {
            let groups = this.getTaskGroups();
            let groupIndex = groups.findIndex(
                (group) => group.id === payload.id
            );

            groups.splice(groupIndex, 1);

            this.taskGroups = JSON.stringify(groups);
        },
        updateGroupName(payload) {
            let groups = this.getTaskGroups();

            groups.find((g) => g.id === payload.id).name = payload.name;

            this.taskGroups = JSON.stringify(groups);
        },
        resetTaskGroups() {
            this.taskGroups = JSON.stringify([]);
        },
        decreaseTaskCount(payload) {
            let groups = this.getTaskGroups();

            groups.find((group) => group.id === payload.id).tasks_count -= 1;

            this.taskGroups = JSON.stringify(groups);
        },
        increaseTaskCount(payload) {
            let groups = this.getTaskGroups();

            groups.find((group) => group.id === payload.id).tasks_count += 1;

            this.taskGroups = JSON.stringify(groups);
        },
    },
});
