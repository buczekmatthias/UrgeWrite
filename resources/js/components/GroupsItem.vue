<template>
    <NoteGroupsList :list="list" v-if="isNotesTab" />
    <TaskGroupsList :list="list" v-else />
</template>

<script>
import { useUserStore } from "../stores/users";
import { useNoteGroupsStore } from "../stores/noteGroups";
import { useTaskGroupsStore } from "../stores/taskGroups";
import { mapState } from "pinia";

import NoteGroupsList from "./NoteGroupsList.vue";
import TaskGroupsList from "./TaskGroupsList.vue";

export default {
    name: "GroupList",
    props: {
        tab: { type: String, default: "notes" },
    },
    components: {
        NoteGroupsList,
        TaskGroupsList,
    },
    data() {
        return {
            list: [],
        };
    },
    watch: {
        tab: (newTab, oldTab) => {},
    },
    computed: {
        isNotesTab() {
            return this.tab === "notes";
        },
        ...mapState(useUserStore, ["getApiConfig"]),
        ...mapState(useNoteGroupsStore, ["getNoteGroups", "setNoteGroups"]),
        ...mapState(useTaskGroupsStore, ["getTaskGroups", "setTaskGroups"]),
    },
    mounted() {
        let listStore = this.isNotesTab
            ? this.getNoteGroups()
            : this.getTaskGroups();

        if (listStore.length === 0) {
            this.isNotesTab ? this.fetchNoteGroups() : this.fetchTaskGroups();
        } else {
            this.list = listStore;
        }
    },
    methods: {
        fetchNoteGroups() {
            axios
                .post("/api/notes/", {}, this.getApiConfig())
                .then((res) => {
                    this.list = res.data.noteGroups;
                    this.setNoteGroups({ groups: res.data.noteGroups });
                })
                .catch((err) => {
                    console.error(err);
                    alert(
                        "Failed fetching your note groups. Check console for error"
                    );
                });
        },
        fetchTaskGroups() {
            axios
                .post("/api/tasks/", {}, this.getApiConfig())
                .then((res) => {
                    this.list = res.data.taskGroups;
                    this.setTaskGroups({ groups: res.data.taskGroups });
                })
                .catch((err) => {
                    console.error(err);
                    alert(
                        "Failed fetching your task groups. Check console for error"
                    );
                });
        },
    },
};
</script>
