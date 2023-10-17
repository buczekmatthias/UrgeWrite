<template>
    <div
        class="fixed top-0 left-0 h-screen w-full z-30 flex items-center justify-center bg-slate-900 bg-opacity-80 p-6"
    >
        <form
            @submit.prevent="handleCreateFormSubmit"
            class="bg-white rounded-lg p-4 flex flex-col gap-6 w-full max-w-xl"
        >
            <p class="font-semibold text-3xl">Create new note</p>
            <div class="flex flex-col gap-2">
                <label for="c-title">Title</label>
                <input
                    type="text"
                    id="c-title"
                    class="input"
                    ref="title"
                    v-model="title"
                />
            </div>
            <div class="flex flex-col gap-2">
                <label for="c-content">Content</label>
                <textarea
                    type="text"
                    id="c-content"
                    class="input h-72 resize-none"
                    ref="content"
                    v-model="content"
                    required
                ></textarea>
            </div>
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
import { useNotesStore } from "../stores/notes";
import { mapState } from "pinia";

export default {
    name: "NoteFromCreate",
    data() {
        return {
            isLoading: false,
            title: "",
            content: "",
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
        ...mapState(useNotesStore, ["setNotes"]),
    },
    methods: {
        handleCreateFormSubmit() {
            this.isLoading = true;
            axios
                .post(
                    `/api/notes/${this.groupId}/create`,
                    {
                        title: this.title,
                        content: this.content,
                    },
                    this.getApiConfig()
                )
                .then((res) => {
                    this.setNotes({
                        groupId: this.groupId,
                        notes: [res.data.note],
                    });

                    this.isLoading = false;
                    this.closeForm();
                    this.$refs.title.value = "";
                    this.$refs.content.value = "";
                })
                .catch((err) => {
                    this.isLoading = false;
                    console.error(err);
                    alert("Failed adding your note.");
                });
        },
        closeForm() {
            this.$refs.title.value = "";
            this.$refs.content.value = "";
            this.$emit("closeForm");
        },
    },
};
</script>
