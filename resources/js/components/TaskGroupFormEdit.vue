<template>
    <div
        class="fixed top-0 left-0 h-screen w-full z-30 flex items-center justify-center bg-slate-900 bg-opacity-80 p-6"
    >
        <form
            @submit.prevent="handleUpdateFormSubmit"
            class="bg-white rounded-lg p-4 flex flex-col gap-6 w-full max-w-xl"
        >
            <p class="font-semibold text-3xl">Update group</p>
            <div class="flex flex-col gap-2">
                <label for="e-name">Group name</label>
                <input
                    type="text"
                    id="e-name"
                    class="input"
                    ref="input"
                    v-model="name"
                    required
                />
            </div>
            <div class="flex gap-4">
                <button
                    type="button"
                    class="rounded-md px-4 py-2 border-2 border-solid border-red-600 text-red-600 font-semibold flex-1"
                    :class="isLoading ? 'loading' : ''"
                    @click="closeForm"
                >
                    Cancel
                </button>
                <button
                    class="rounded-md px-4 py-2 border-2 border-solid border-emerald-600 bg-emerald-600 text-white font-semibold flex-1"
                    :class="isLoading ? 'loading' : ''"
                >
                    Update
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import { useUserStore } from "../stores/users";
import { useTaskGroupsStore } from "../stores/taskGroups";
import { mapState } from "pinia";

export default {
    name: "TaskGroupFromEdit",
    data() {
        return {
            isLoading: false,
            name: "",
        };
    },
    props: {
        groupId: String,
    },
    watch: {
        groupId: function (newVal, oldVal) {
            this.name = this.getTaskGroupName(newVal);
        },
    },
    emits: {
        closeForm: null,
    },
    computed: {
        ...mapState(useUserStore, ["getApiConfig"]),
        ...mapState(useTaskGroupsStore, [
            "getTaskGroupName",
            "updateGroupName",
        ]),
    },
    mounted() {
        this.name = this.getTaskGroupName(this.groupId);
    },
    methods: {
        handleUpdateFormSubmit() {
            this.isLoading = true;
            axios
                .post(
                    `/api/tasks/${this.groupId}/update`,
                    {
                        name: this.name,
                    },
                    this.getApiConfig()
                )
                .then((res) => {
                    this.updateGroupName({ id: this.groupId, name: this.name });
                    this.closeForm();
                    this.isLoading = false;
                })
                .catch((err) => {
                    this.isLoading = false;
                    console.error(err);
                    alert("Failed editing task group");
                });
        },
        closeForm() {
            this.$refs.input.value = "";
            this.$emit("closeForm");
        },
    },
};
</script>
