<template>
    <div
        class="fixed top-0 left-0 h-screen w-full z-30 flex items-center justify-center bg-slate-900 bg-opacity-80 p-6"
    >
        <form
            @submit.prevent="handleUpdateFormSubmit"
            class="bg-white rounded-lg p-4 flex flex-col gap-6 w-full max-w-xl"
        >
            <p class="font-semibold text-3xl">Update note</p>
            <div class="flex flex-col gap-2">
                <label for="e-title">Title</label>
                <input
                    type="text"
                    id="e-title"
                    class="input"
                    ref="title"
                    v-model="title"
                />
            </div>
            <div class="flex flex-col gap-2">
                <label for="e-content">Content</label>
                <textarea
                    type="text"
                    id="e-content"
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
                    Update
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
    name: "NoteFromEdit",
    data() {
        return {
            isLoading: false,
            title: "",
            content: "",
        };
    },
    props: {
        groupId: String,
        note: Object,
    },
    emits: {
        closeForm: null,
    },
    computed: {
        ...mapState(useUserStore, ["getApiConfig"]),
        ...mapState(useNotesStore, ["updateNote"]),
    },
    mounted() {
        this.title = this.note.title;
        this.content = this.note.content;
    },
    methods: {
        handleUpdateFormSubmit() {
            this.isLoading = true;
            axios
                .post(
                    `/api/notes/${this.groupId}/${this.note.id}/update`,
                    {
                        title: this.title,
                        content: this.content,
                    },
                    this.getApiConfig()
                )
                .then((res) => {
                    this.updateNote({
                        id: this.note.id,
                        groupId: this.groupId,
                        title: this.title,
                        content: this.content,
                    });
                    this.isLoading = false;
                    this.closeForm();
                })
                .catch((err) => {
                    this.isLoading = false;
                    console.error(err);
                    alert("Failed editing note group");
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
