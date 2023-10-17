<template>
    <div
        class="flex flex-col gap-4 xl:box xl:grid xl:grid-cols-3 xl:gap-3 2xl:gap-6 xl:max-h-[85vh]"
    >
        <TaskGroupFormCreate @close-form="closeForm" v-if="isCreatingGroup" />
        <TaskGroupFormEdit
            :groupId="$route.params?.group"
            @close-form="closeForm"
            v-if="isEditingGroup"
        />
        <div class="max-xl:box w-full !p-0 xl:max-h-[80vh] xl:overflow-auto">
            <div
                class="px-4 py-5 cursor-pointer flex justify-between items-center xl:pointer-events-none xl:cursor-default xl:border-b xl:border-solid xl:border-primary"
                ref="header"
                @click="handleExpand()"
            >
                <p class="font-semibold text-xl">Task groups</p>
                <p class="text-sm text-link xl:hidden">
                    {{ isExpanded ? "Hide" : "Expand" }}
                </p>
            </div>
            <div
                class="max-h-0 overflow-hidden flex flex-col gap-2 xl:overflow-auto xl:max-h-fit xl:py-3"
            >
                <ButtonCreate
                    text="Add new group"
                    @click="isCreatingGroup = true"
                />
                <router-link
                    class="sub-link flex justify-between items-center gap-2"
                    :to="{
                        name: 'content',
                        params: { type: 'tasks', group: item.id },
                    }"
                    v-for="(item, i) in groupsList"
                    :key="i"
                    v-slot="{ isActive, isExactActive }"
                >
                    <div class="flex flex-col gap-2">
                        <p class="text-2xl">{{ item.name }}</p>
                        <p class="text-sm font-normal">
                            {{ item.tasks_count }} tasks
                        </p>
                    </div>
                    <div
                        class="flex gap-6"
                        v-if="
                            (isActive || isExactActive) && item.name !== 'Unset'
                        "
                    >
                        <IconEdit
                            :class="isLoading ? 'loading' : ''"
                            @click="isEditingGroup = true"
                        />
                        <IconDelete
                            :class="isLoading ? 'loading' : ''"
                            @click="deleteGroup(item.id)"
                        />
                    </div>
                </router-link>
            </div>
        </div>
        <TasksList
            @task-created="increaseCount"
            @task-deleted="decreaseCount"
            :taskGroupId="$route.params.group"
            v-if="this.groupsList.length > 0 && $route.params.group"
        />
        <div class="max-xl:box" v-else>
            <p class="text-lg">You need to select group to show tasks</p>
        </div>
    </div>
</template>

<script>
import TasksList from "./TasksList.vue";
import ButtonCreate from "./ButtonCreate.vue";
import TaskGroupFormCreate from "./TaskGroupFormCreate.vue";
import TaskGroupFormEdit from "./TaskGroupFormEdit.vue";
import IconEdit from "./IconEdit.vue";
import IconDelete from "./IconDelete.vue";
import { useUserStore } from "../stores/users";
import { useTaskGroupsStore } from "../stores/taskGroups";
import { useTasksStore } from "../stores/tasks";
import { mapState } from "pinia";

export default {
    name: "TaskGroupsList",
    props: {
        list: Array,
    },
    components: {
        TasksList,
        IconDelete,
        IconEdit,
        TaskGroupFormCreate,
        TaskGroupFormEdit,
        ButtonCreate,
    },
    data() {
        return {
            isEditingGroup: false,
            isCreatingGroup: false,
            isExpanded: false,
            groupsList: [],
            isLoading: false,
        };
    },
    watch: {
        list: function (newVal, oldVal) {
            this.groupsList = newVal;
        },
    },
    computed: {
        ...mapState(useUserStore, ["getApiConfig"]),
        ...mapState(useTaskGroupsStore, [
            "getTaskGroups",
            "decreaseTaskCount",
            "deleteTaskGroup",
            "increaseTaskCount",
        ]),
        ...mapState(useTasksStore, ["removeGroup"]),
    },
    mounted() {
        this.groupsList = this.list;
    },
    methods: {
        handleExpand() {
            this.isExpanded = !this.isExpanded;
            this.$refs.header.parentElement.classList.toggle("expand");
        },
        decreaseCount() {
            this.decreaseTaskCount({ id: this.$route.params.group });
            this.groupsList = this.getTaskGroups();
        },
        deleteGroup(groupId) {
            this.isLoading = true;
            axios
                .post(`/api/tasks/${groupId}/delete`, {}, this.getApiConfig())
                .then((res) => {
                    this.deleteTaskGroup({ id: groupId });
                    this.removeGroup({ groupId: groupId });
                    this.groupsList = this.getTaskGroups();

                    this.isLoading = false;
                    this.$router.push({
                        name: "content",
                        params: {
                            type: "tasks",
                        },
                    });
                })
                .catch((err) => {
                    this.isLoading = false;
                    console.error(err);
                    alert("Failed deleting task group");
                });
        },
        increaseCount() {
            this.increaseTaskCount({ id: this.$route.params.group });
            this.groupsList = this.getTaskGroups();
        },
        closeForm() {
            this.isCreatingGroup = false;
            this.isEditingGroup = false;

            this.groupsList = this.getTaskGroups();
        },
    },
};
</script>
