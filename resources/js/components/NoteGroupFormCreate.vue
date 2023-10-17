<template>
    <div
        class="fixed top-0 left-0 h-screen w-full z-30 flex items-center justify-center bg-slate-900 bg-opacity-80 p-6"
    >
        <form
            @submit.prevent="handleCreateFormSubmit"
            class="bg-white rounded-lg p-4 flex flex-col gap-6 w-full"
        >
            <p class="font-semibold text-3xl">Create new group</p>
            <div class="flex flex-col gap-2">
                <label for="c-name">Group name</label>
                <input
                    type="text"
                    id="c-name"
                    class="input"
                    ref="input"
                    v-model="name"
                />
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
                    :class="isLoading ? 'loading' : ''"
                    class="rounded-md px-4 py-2 border-2 border-solid border-emerald-600 bg-emerald-600 text-white font-semibold flex-1"
                >
                    Create
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import { useUserStore } from "../stores/users";
import { useNoteGroupsStore } from "../stores/noteGroups";
import { mapState } from "pinia";

export default {
    name: "NoteGroupFromCreate",
    data() {
        return {
            name: "",
            isLoading: false,
        };
    },
    emits: {
        closeForm: null,
    },
    computed: {
        ...mapState(useUserStore, ["getApiConfig"]),
        ...mapState(useNoteGroupsStore, ["setNoteGroups"]),
    },
    methods: {
        handleCreateFormSubmit() {
            this.isLoading = true;
            axios
                .post(
                    `/api/notes/add-new-group`,
                    {
                        name: this.name,
                    },
                    this.getApiConfig()
                )
                .then((res) => {
                    this.setNoteGroups({ groups: [res.data.group] });

                    this.isLoading = false;
                    this.closeForm();
                    this.$refs.input.value = "";
                })
                .catch((err) => {
                    this.isLoading = false;
                    console.error(err);
                    alert("Failed adding note group.");
                });
        },
        closeForm() {
            this.$emit("closeForm");
        },
    },
};
</script>
