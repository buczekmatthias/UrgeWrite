import Vuex from "vuex";

export default new Vuex.Store({
    state() {
        return {
            user: null,
            noteGruops: null,
            notes: [],
            taskGroups: null,
            tasks: [],
        };
    },
    getters: {
        getTask: (state) => (id) => {
            return state.tasks.find((task) => task.id === id);
        },
        getTasks: (state) => (name) => {
            return state.tasks[name];
        },
        getNotes: (state) => (name) => {
            return state.notes[name];
        },
        getNoteGroups: (state) => () => {
            return state.noteGruops;
        },
        getTaskGroups: (state) => () => {
            return state.taskGroups;
        },
        getUser: (state) => () => {
            return state.user;
        },
    },
    mutations: {
        setUser(state, payload) {
            state.user = payload.user;
        },
        setNoteGroups(state, payload) {
            state.noteGruops = payload.noteGruops;
        },
        setNotes(state, payload) {
            state.notes[payload.group] = payload.notes;
        },
        setTaskGroups(state, payload) {
            state.taskGroups = payload.taskGroups;
        },
        setTasks(state, payload) {
            state.taskGroups[payload.group] = payload.tasks;
        },
        setTaskDone(state, payload) {
            let task = state.getters.getTask(payload.id);
            task.isDone = payload.isDone;
        },
    },
});
