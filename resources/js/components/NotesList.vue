<template>
    <div class="max-xl:box w-full !p-0">
        <div
            class="px-4 py-5 cursor-pointer flex justify-between items-center"
            ref="header"
            @click="handleExpand()"
        >
            <p class="font-semibold text-xl">
                Notes from
                <i>
                    <b>"{{ chosenGroup }}"</b>
                </i>
                collection
            </p>
            <p class="text-sm text-link">
                {{ isExpanded ? "Hide" : "Expand" }}
            </p>
        </div>
        <div
            class="max-h-0 overflow-hidden flex flex-col gap-2"
            v-if="list.length > 0"
        >
            <router-link
                class="sub-link flex flex-col gap-2"
                :to="{
                    name: 'content',
                    params: {
                        type: $route.params.type,
                        group: item.group_id,
                        elementId: item.id,
                    },
                }"
                v-for="(item, i) in list"
                :key="i"
            >
                <p class="text-xl font-semibold">
                    {{ item.title ?? "Untiteled note" }}
                </p>
                <p class="font-light text-sm">{{ item.created_at }}</p>
            </router-link>
        </div>
        <div class="max-h-0 overflow-hidden flex flex-col gap-2" v-else>
            <p class="">There are no notes in this collection</p>
            <button>Create one</button>
        </div>
    </div>
    <div class="max-xl:box">
        <p v-if="!$route.params.elementId">Select note to see details of it</p>
        <NoteItem
            @note-delete="updateListAfterDelete"
            :noteId="$route.params.elementId"
            v-else
        />
    </div>
</template>

<script>
import NoteItem from "../components/NoteItem.vue";
import { useUserStore } from "../stores/users";
import { useNoteGroupsStore } from "../stores/noteGroups";
import { useNotesStore } from "../stores/notes";
import { mapState } from "pinia";

export default {
    name: "NotesList",
    components: {
        NoteItem,
    },
    data() {
        return {
            isExpanded: false,
            list: [],
        };
    },
    emits: {
        noteDeleted: null,
    },
    props: {
        group: String,
    },
    watch: {
        group: function (newVal, oldVal) {
            this.list = this.getNotes(newVal);
        },
    },
    computed: {
        chosenGroup() {
            return this.getNoteGroupName(this.group);
        },
        ...mapState(useUserStore, ["getApiConfig"]),
        ...mapState(useNoteGroupsStore, ["getNoteGroupName"]),
        ...mapState(useNotesStore, ["getNotes", "setNotes"]),
    },
    mounted() {
        let stored = this.getNotes(this.group);
        if (stored.length === 0) {
            this.fetchGroupNotes();
        } else {
            this.list = stored;
        }
    },
    methods: {
        handleExpand() {
            this.isExpanded = !this.isExpanded;
            this.$refs.header.parentElement.classList.toggle("expand");
        },
        fetchGroupNotes() {
            axios
                .post(`/api/notes/${this.group}`, {}, this.getApiConfig())
                .then((res) => {
                    this.list = res.data.notes;
                    this.setNotes({ notes: res.data.notes });
                })
                .catch((err) => {
                    console.log(err);
                    alert(
                        "Failed fetching your notes. Check console for error"
                    );
                });
        },
        updateListAfterDelete() {
            this.$emit("noteDeleted");
            this.list = this.getNotes(this.group);
        },
    },
};
</script>
