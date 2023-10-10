import { defineStore } from "pinia";

export const useTasksStore = defineStore("tasks", {
    state: () => {
        return {
            tasks: {},
        };
    },
    getters: {
        getTasks: (state) => (name) => {
            return state.tasks[name];
        },
    },
    actions: {
        setTasks(payload) {
            let group = payload.group;

            if (group in this.tasks) {
                this.tasks[group] = [...this.tasks[group], ...payload.tasks];
            } else {
                this.tasks = { ...this.tasks, group: payload.tasks };
            }
        },
        updateTaskContent(payload) {
            this.tasks[payload.group].find(
                (task) => task.id === payload.id
            ).content = payload.content;
        },
        setTaskDone(payload) {
            this.tasks[payload.group].find(
                (task) => task.id === payload.id
            ).isDone = payload.isDone;
        },
    },
});
