<template>
    <NoteFormCreate
        :groupId="$route.params.group"
        @close-form="closeForm"
        v-if="isCreatingNote"
    />
    <NoteFormEdit
        :groupId="$route.params.group"
        :noteId="$route.params.group?.elementId"
        @close-form="closeForm"
        v-if="isEditingNote"
    />
    <div class="max-xl:box w-full !p-0 xl:max-h-[80vh] xl:overflow-auto">
        <div
            class="px-4 py-5 cursor-pointer flex justify-between items-center xl:pointer-events-none xl:cursor-default xl:border-b xl:border-solid xl:border-primary"
            ref="header"
            @click="handleExpand()"
        >
            <p class="font-semibold text-xl">
                <i>
                    <b>{{ chosenGroup ?? "???" }}</b>
                </i>
            </p>
            <p class="text-sm text-link xl:hidden">
                {{ isExpanded ? "Hide" : "Expand" }}
            </p>
        </div>
        <IconLoader v-if="isLoading" class="fill-gray-900 mx-auto my-4" />
        <div
            class="max-h-0 overflow-hidden flex flex-col gap-2 xl:overflow-auto xl:max-h-fit xl:py-3"
            v-else-if="list.length > 0"
        >
            <ButtonCreate text="Create note" @click="isCreatingNote = true" />
            <router-link
                class="sub-link flex flex-col gap-2"
                :to="{
                    name: 'content',
                    params: {
                        type: 'notes',
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
        <div
            class="max-h-0 overflow-hidden flex flex-col gap-4 xl:overflow-auto xl:max-h-fit xl:py-3"
            v-else
        >
            <ButtonCreate text="Create note" @click="isCreatingNote = true" />
            <p class="text-center">There are no notes in this collection</p>
        </div>
    </div>
    <div class="max-xl:box">
        <p v-if="!$route.params.elementId">Select note to see details of it</p>
        <NoteItem
            @note-edit="updateListAfterEdit"
            @note-delete="updateListAfterDelete"
            :noteId="$route.params.elementId"
            v-else
        />
    </div>
</template>

<script>
import IconLoader from "./IconLoader.vue";
import NoteFormCreate from "./NoteFormCreate.vue";
import NoteFormEdit from "./NoteFormEdit.vue";
import ButtonCreate from "./ButtonCreate.vue";
import NoteItem from "../components/NoteItem.vue";
import { useUserStore } from "../stores/users";
import { useNoteGroupsStore } from "../stores/noteGroups";
import { useNotesStore } from "../stores/notes";
import { mapState } from "pinia";

export default {
    name: "NotesList",
    components: {
        NoteItem,
        ButtonCreate,
        NoteFormCreate,
        NoteFormEdit,
        IconLoader,
    },
    data() {
        return {
            isEditingNote: false,
            isCreatingNote: false,
            isExpanded: false,
            list: [],
            isLoading: false,
        };
    },
    emits: {
        noteDeleted: null,
        noteCreated: null,
    },
    props: {
        group: String,
    },
    watch: {
        group: function (newVal, oldVal) {
            this.pullNotes();
        },
    },
    computed: {
        chosenGroup() {
            return this.getNoteGroupName(this.group);
        },
        ...mapState(useUserStore, ["getApiConfig"]),
        ...mapState(useNoteGroupsStore, ["getNoteGroupName"]),
        ...mapState(useNotesStore, ["hasNotes", "getNotes", "setNotes"]),
    },
    mounted() {
        this.pullNotes();
    },
    methods: {
        pullNotes() {
            let stored = this.getNotes(this.group);

            if (stored === undefined) {
                this.fetchGroupNotes();
            } else {
                this.list = stored;
            }
        },
        handleExpand() {
            this.isExpanded = !this.isExpanded;
            this.$refs.header.parentElement.classList.toggle("expand");
        },
        fetchGroupNotes() {
            this.isLoading = true;
            axios
                .post(`/api/notes/${this.group}`, {}, this.getApiConfig())
                .then((res) => {
                    this.list = res.data.notes;
                    this.setNotes({
                        groupId: this.group,
                        notes: res.data.notes,
                    });
                    this.isLoading = false;
                })
                .catch((err) => {
                    this.isLoading = false;
                    console.error(err);
                    alert(
                        "Failed fetching your notes. Check console for error"
                    );
                });
        },
        updateListAfterDelete() {
            this.$emit("noteDeleted");
            this.list = this.getNotes(this.group);
        },
        updateListAfterEdit() {
            this.list = this.getNotes(this.group);
        },
        closeForm() {
            this.$emit("noteCreated");
            this.isCreatingNote = false;
            this.isEditingNote = false;

            this.list = this.getNotes(this.group);
        },
    },
};
</script>
