<template>
    <div
        class="fixed top-0 left-0 h-screen w-full z-30 flex items-center justify-center bg-slate-900 bg-opacity-80 p-6"
    >
        <form
            @submit.prevent="handleCreateFormSubmit"
            class="bg-white rounded-lg p-4 flex flex-col gap-6 w-full max-w-xl"
        >
            <p class="font-semibold text-3xl">Create new task</p>
            <div class="flex flex-col gap-2">
                <label for="c-content">Content</label>
                <input
                    type="text"
                    id="c-content"
                    class="input"
                    ref="content"
                    v-model="content"
                />
            </div>
            <label class="cursor-pointer flex items-center gap-2">
                <input
                    type="checkbox"
                    name="isDone"
                    ref="isDone"
                    v-model="isDone"
                />
                <span>Set task as finished</span>
            </label>
            <div class="flex gap-4">
                <button
                    type="button"
                    class="rounded-md px-4 py-2 border-2 border-solid border-red-600 text-red-600 font-semibold flex-1"
                    @click="closeForm"
                >
                    Cancel
                </button>
                <button
                    class="rounded-md px-4 py-2 border-2 border-solid border-emerald-600 bg-emerald-600 text-white font-semibold flex-1"
                    :class="isLoading ? 'loading' : ''"
                >
                    Create
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import { useUserStore } from "../stores/users";
import { useTasksStore } from "../stores/tasks";
import { mapState } from "pinia";

export default {
    name: "NoteFromCreate",
    data() {
        return {
            isLoading: false,
            content: "",
            isDone: false,
        };
    },
    props: {
        groupId: String,
    },
    emits: {
        closeForm: null,
    },
    computed: {
        ...mapState(useUserStore, ["getApiConfig"]),
        ...mapState(useTasksStore, ["setTasks"]),
    },
    methods: {
        handleCreateFormSubmit() {
            this.isLoading = true;
            axios
                .post(
                    `/api/tasks/${this.groupId}/create`,
                    {
                        content: this.content,
                        isDone: this.isDone,
                    },
                    this.getApiConfig()
                )
                .then((res) => {
                    this.setTasks({
                        groupId: this.groupId,
                        tasks: [res.data.task],
                    });

                    this.isLoading = false;
                    this.closeForm();
                })
                .catch((err) => {
                    this.isLoading = false;
                    console.error(err);
                    alert("Failed adding your task.");
                });
        },
        closeForm() {
            this.$refs.content.value = "";
            this.$refs.isDone.checked = false;
            this.$emit("closeForm");
        },
    },
};
</script>
