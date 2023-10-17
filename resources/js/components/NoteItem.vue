<template>
    <NoteFormEdit
        @closeForm="closeForm"
        :groupId="this.$route.params.group"
        :note="this.note"
        v-if="isEditingNote"
    />
    <div class="flex flex-col gap-4">
        <div class="flex justify-between items-center">
            <div class="flex flex-col gap-1">
                <p class="text-2xl font-semibold">{{ note.title }}</p>
                <p class="text-sm">{{ note.created_at }}</p>
            </div>
            <div class="flex gap-6">
                <IconEdit
                    class="h-6"
                    :class="isLoading ? 'loading' : ''"
                    @click="isEditingNote = true"
                />
                <IconDelete
                    class="h-6"
                    :class="isLoading ? 'loading' : ''"
                    @click="deleteNote"
                />
            </div>
        </div>
        <p>{{ note.content }}</p>
    </div>
</template>

<script>
import NoteFormEdit from "./NoteFormEdit.vue";

import { useUserStore } from "../stores/users";
import { useNotesStore } from "../stores/notes";
import { mapState } from "pinia";

import IconEdit from "./IconEdit.vue";
import IconDelete from "./IconDelete.vue";

export default {
    name: "NoteItem",
    data() {
        return {
            isLoading: false,
            isEditingNote: false,
            note: {},
        };
    },
    components: {
        NoteFormEdit,
        IconEdit,
        IconDelete,
    },
    emits: {
        noteDelete: null,
        noteEdit: null,
    },
    props: {
        noteId: String,
    },
    watch: {
        noteId: function (newVal, oldVal) {
            this.note = this.getNote(this.$route.params.group, newVal);
        },
    },
    computed: {
        ...mapState(useUserStore, ["getApiConfig"]),
        ...mapState(useNotesStore, ["getNote", "removeNote"]),
    },
    mounted() {
        this.note = this.getNote(this.$route.params.group, this.noteId);
    },
    methods: {
        deleteNote() {
            this.isLoading = true;
            axios
                .post(
                    `/api/notes/${this.$route.params.group}/${this.noteId}/delete`,
                    {},
                    this.getApiConfig()
                )
                .then((res) => {
                    this.removeNote({
                        groupId: this.$route.params.group,
                        id: this.noteId,
                    });
                    this.$emit("noteDelete");

                    this.isLoading = false;
                    this.$router.push({
                        name: "content",
                        params: {
                            type: "notes",
                            group: this.$route.params.group,
                        },
                    });
                })
                .catch((err) => {
                    this.isLoading = false;
                    console.error(err);
                    alert("Failed deleting your note");
                });
        },
        closeForm() {
            this.isEditingNote = false;
            this.note = this.getNote(this.$route.params.group, this.noteId);
            this.$emit("noteEdit");
        },
    },
};
</script>
