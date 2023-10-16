<template>
    <div class="flex flex-col gap-4">
        <div class="flex justify-between items-center">
            <div class="flex flex-col gap-1">
                <p class="text-2xl font-semibold">{{ note.title }}</p>
                <p class="text-sm">{{ note.created_at }}</p>
            </div>
            <div class="flex gap-6">
                <Edit class="h-6" />
                <Delete class="h-6" @click="deleteNote" />
            </div>
        </div>
        <p>{{ note.content }}</p>
    </div>
</template>

<script>
import { useUserStore } from "../stores/users";
import { useNotesStore } from "../stores/notes";
import { mapState } from "pinia";

import { default as Edit } from "./IconEdit.vue";
import { default as Delete } from "./IconDelete.vue";

export default {
    name: "NoteItem",
    data() {
        return {
            note: {},
        };
    },
    components: {
        Edit,
        Delete,
    },
    emits: {
        noteDelete: null,
    },
    props: {
        noteId: String,
    },
    watch: {
        noteId: function (newVal, oldVal) {
            this.note = this.getNote(newVal);
        },
    },
    computed: {
        ...mapState(useUserStore, ["getApiConfig"]),
        ...mapState(useNotesStore, ["getNote", "removeNote"]),
    },
    mounted() {
        this.note = this.getNote(this.noteId);
    },
    methods: {
        deleteNote() {
            axios
                .post(
                    `/api/notes/${this.$route.params.group}/${this.noteId}/delete`,
                    {},
                    this.getApiConfig()
                )
                .then((res) => {
                    this.removeNote({ id: this.noteId });
                    this.$emit("noteDelete");

                    this.$router.push({
                        name: "content",
                        params: {
                            type: "notes",
                            group: this.$route.params.group,
                        },
                    });
                })
                .catch((err) => {
                    console.error(err);
                    alert("Failed deleting your note");
                });
        },
    },
};
</script>
