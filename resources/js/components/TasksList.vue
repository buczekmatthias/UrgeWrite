<template>
    <TaskFormCreate
        v-if="isCreatingTask"
        :groupId="taskGroupId"
        @closeForm="closeForm"
    />
    <div class="max-xl:box">
        <p
            class="italic font-extrabold text-xl border-b border-solid border-primary py-2"
        >
            {{ groupName ?? "??" }}
        </p>
        <div class="py-3 flex flex-col gap-2" v-if="list.length > 0">
            <ButtonCreate
                text="Create new task"
                @click="isCreatingTask = true"
            />
            <TaskItem
                v-for="(item, i) in list"
                :key="i"
                :task="item"
                :isDone="item.isDone"
                @deleteTask="taskDeleted"
                @setIsDone="updateDone"
            />
        </div>
        <div class="flex flex-col gap-4 py-3" v-else>
            <ButtonCreate
                text="Create new task"
                @click="isCreatingTask = true"
            />
            <p class="text-center">This group has no tasks</p>
        </div>
    </div>
</template>

<script>
import TaskItem from "./TaskItem.vue";
import ButtonCreate from "./ButtonCreate.vue";
import TaskFormCreate from "./TaskFormCreate.vue";

import { useUserStore } from "../stores/users";
import { useTasksStore } from "../stores/tasks";
import { useTaskGroupsStore } from "../stores/taskGroups";
import { mapState } from "pinia";

export default {
    name: "TasksList",
    components: {
        TaskItem,
        ButtonCreate,
        TaskFormCreate,
    },
    props: {
        taskGroupId: String,
    },
    emits: {
        taskCreated: null,
        taskDeleted: null,
    },
    watch: {
        taskGroupId: function (newVal, oldVal) {
            this.pullNotes();
        },
    },
    computed: {
        groupName() {
            return this.getTaskGroupName(this.taskGroupId);
        },
        ...mapState(useUserStore, ["getApiConfig"]),
        ...mapState(useTasksStore, [
            "getGroupTasks",
            "setTasks",
            "removeTask",
            "updateTask",
        ]),
        ...mapState(useTaskGroupsStore, ["getTaskGroupName"]),
    },
    data() {
        return {
            list: [],
            isCreatingTask: false,
            isLoading: false,
        };
    },
    mounted() {
        this.pullNotes();
    },
    methods: {
        pullNotes() {
            let stored = this.getGroupTasks(this.taskGroupId);

            if (stored === undefined) {
                this.fetchGroupTasks();
            } else {
                this.list = stored;
            }
        },
        fetchGroupTasks() {
            this.isLoading = true;
            axios
                .post(`/api/tasks/${this.taskGroupId}`, {}, this.getApiConfig())
                .then((res) => {
                    this.list = res.data.tasks;
                    this.setTasks({
                        groupId: this.taskGroupId,
                        tasks: res.data.tasks,
                    });
                    this.isLoading = false;
                })
                .catch((err) => {
                    this.isLoading = false;
                    console.error(err);
                    alert(
                        "Failed fetching your tasks. Check console for error"
                    );
                });
        },
        closeForm() {
            this.$emit("taskCreated");
            this.isCreatingTask = false;

            this.list = this.getGroupTasks(this.taskGroupId);
        },
        taskDeleted(taskId) {
            this.isLoading = true;
            axios
                .post(
                    `/api/tasks/${this.taskGroupId}/${taskId}/delete`,
                    {},
                    this.getApiConfig()
                )
                .then((res) => {
                    this.removeTask({
                        groupId: this.taskGroupId,
                        id: taskId,
                    });

                    this.$emit("taskDeleted");
                    this.list = this.getGroupTasks(this.taskGroupId);
                    this.isLoading = false;
                })
                .catch((err) => {
                    this.isLoading = false;
                    console.error(err);
                    alert("Failed deleting your task");
                });
        },
        updateDone(taskId, isDone) {
            this.isLoading = true;
            axios
                .post(
                    `/api/tasks/${this.taskGroupId}/${taskId}/update`,
                    {
                        isDone: isDone,
                    },
                    this.getApiConfig()
                )
                .then((res) => {
                    this.updateTask({
                        groupId: this.taskGroupId,
                        id: taskId,
                        isDone: isDone,
                    });

                    this.list = this.getGroupTasks(this.taskGroupId);
                    this.isLoading = false;
                })
                .catch((err) => {
                    this.isLoading = false;
                    console.error(err);
                    alert("Failed deleting your task");
                });
        },
    },
};
</script>
