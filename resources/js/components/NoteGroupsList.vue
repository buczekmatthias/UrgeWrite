<template>
    <div
        class="flex flex-col gap-4 xl:box xl:grid xl:grid-cols-3 xl:gap-3 2xl:gap-6 xl:max-h-[85vh]"
    >
        <NoteGroupFormCreate @close-form="closeForm" v-if="isCreatingGroup" />
        <NoteGroupFormEdit
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
                <p class="font-semibold text-xl">Note groups</p>
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
                        params: { type: $route.params.type, group: item.id },
                    }"
                    v-for="(item, i) in groupsList"
                    :key="i"
                    v-slot="{ isActive, isExactActive }"
                >
                    <div class="flex flex-col gap-2">
                        <p class="text-2xl">{{ item.name }}</p>
                        <p class="text-sm font-normal">
                            {{ item.notes_count }} notes
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
        <NotesList
            @note-created="increaseCount"
            @note-deleted="decreaseCount"
            :group="$route.params.group"
            v-if="$route.params.group"
        />
        <div class="max-xl:box" v-else>
            <p class="text-lg">You need to select group to show notes</p>
        </div>
    </div>
</template>

<script>
import ButtonCreate from "./ButtonCreate.vue";
import NoteGroupFormCreate from "./NoteGroupFormCreate.vue";
import NoteGroupFormEdit from "./NoteGroupFormEdit.vue";
import IconEdit from "./IconEdit.vue";
import IconDelete from "./IconDelete.vue";
import NotesList from "./NotesList.vue";
import { useUserStore } from "../stores/users";
import { useNoteGroupsStore } from "../stores/noteGroups";
import { useNotesStore } from "../stores/notes";
import { mapState } from "pinia";

export default {
    name: "NoteGroupsList",
    props: {
        list: Array,
    },
    components: {
        NotesList,
        IconDelete,
        IconEdit,
        NoteGroupFormCreate,
        NoteGroupFormEdit,
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
        ...mapState(useNoteGroupsStore, [
            "getNoteGroups",
            "decreaseNoteCount",
            "deleteNoteGroup",
            "increaseNoteCount",
        ]),
        ...mapState(useNotesStore, ["removeGroup"]),
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
            this.decreaseNoteCount({ id: this.$route.params.group });
            this.groupsList = this.getNoteGroups();
        },
        deleteGroup(groupId) {
            this.isLoading = true;
            axios
                .post(`/api/notes/${groupId}/delete`, {}, this.getApiConfig())
                .then((res) => {
                    this.deleteNoteGroup({ id: groupId });
                    this.removeGroup({ groupId: groupId });
                    this.groupsList = this.getNoteGroups();

                    this.isLoading = false;
                    this.$router.push({
                        name: "content",
                        params: {
                            type: "notes",
                        },
                    });
                })
                .catch((err) => {
                    this.isLoading = false;
                    console.error(err);
                    alert("Failed deleting note group");
                });
        },
        increaseCount() {
            this.increaseNoteCount({ id: this.$route.params.group });
            this.groupsList = this.getNoteGroups();
        },
        closeForm() {
            this.isCreatingGroup = false;
            this.isEditingGroup = false;

            this.groupsList = this.getNoteGroups();
        },
    },
};
</script>
