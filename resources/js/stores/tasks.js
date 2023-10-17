import { defineStore } from "pinia";
import { useLocalStorage } from "@vueuse/core";

export const useTasksStore = defineStore("tasks", {
    state: () => {
        return {
            tasks: useLocalStorage("tasks", "{}"),
        };
    },
    getters: {
        getGroupTasks: (state) => (groupId) => JSON.parse(state.tasks)[groupId],
    },
    actions: {
        setTasks(payload) {
            let tasks = JSON.parse(this.tasks);

            if (tasks[payload.groupId]) {
                tasks[payload.groupId] = [
                    ...payload.tasks,
                    ...tasks[payload.groupId],
                ];
            } else {
                tasks[payload.groupId] = payload.tasks;
            }

            this.tasks = JSON.stringify(tasks);
        },
        updateTask(payload) {
            let tasks = JSON.parse(this.tasks);
            let task = tasks[payload.groupId].find(
                (task) => task.id === payload.id
            );

            task.isDone = payload.isDone;

            this.tasks = JSON.stringify(tasks);
        },
        setTaskDone(payload) {
            let tasks = JSON.parse(this.tasks);
            let task = tasks[payload.groupId].find(
                (task) => task.id === payload.id
            );

            task.isDone = payload.isDone;

            this.tasks = JSON.stringify(tasks);
        },
        removeTask(payload) {
            let tasks = JSON.parse(this.tasks);
            let group = tasks[payload.groupId];
            let task = group.find((task) => task.id === payload.id);

            group.splice(group.indexOf(task), 1);

            this.tasks = JSON.stringify(tasks);
        },
        resetTasks() {
            this.tasks = JSON.stringify([]);
        },
        removeGroup(payload) {
            let tasks = JSON.parse(this.tasks);

            delete tasks[payload.groupId];

            this.tasks = JSON.stringify(tasks);
        },
    },
});
