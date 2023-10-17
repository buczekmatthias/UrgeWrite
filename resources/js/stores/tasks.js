import { defineStore } from "pinia";
import { useLocalStorage } from "@vueuse/core";

export const useTasksStore = defineStore("tasks", {
    state: () => {
        return {
            // TODO: Convert to object
            tasks: useLocalStorage("tasks", "[]"),
        };
    },
    getters: {
        getTasks: (state) => (groupId) => {
            return JSON.parse(state.tasks).filter(
                (task) => task.group_id === groupId
            );
        },
        getTask: (state) => (taskId) =>
            JSON.parse(state.tasks).find((task) => task.id === taskId),
    },
    actions: {
        setTasks(payload) {
            this.tasks = JSON.stringify([
                ...JSON.parse(this.tasks),
                ...payload.tasks,
            ]);
        },
        updateTaskContent(payload) {
            let task = this.getTasks(payload.groupId).find(
                (task) => task.id === payload.id
            );
            task.content = payload.content;
            task.isDone = payload.isDone;
            task.task_group_id = payload.groupId;
        },
        setTaskDone(payload) {
            this.getTasks(payload.groupId).find(
                (task) => task.id === payload.id
            ).isDone = payload.isDone;
        },
        resetTasks() {
            this.tasks = JSON.stringify([]);
        },
    },
});
