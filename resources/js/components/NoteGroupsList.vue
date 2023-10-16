<template>
    <div class="flex flex-col gap-4">
        <NoteGroupFormCreate @close-form="closeForm" v-if="isCreatingGroup" />
        <NoteGroupFormEdit
            :groupId="$route.params?.group"
            @close-form="closeForm"
            v-if="isEditingGroup"
        />
        <div class="max-xl:box w-full !p-0">
            <div
                class="px-4 py-5 cursor-pointer flex justify-between items-center"
                ref="header"
                @click="handleExpand()"
            >
                <p class="font-semibold text-xl">Note groups</p>
                <p class="text-sm text-link">
                    {{ isExpanded ? "Hide" : "Expand" }}
                </p>
            </div>
            <div class="max-h-0 overflow-hidden flex flex-col gap-2">
                <button
                    class="flex items-center justify-center gap-3 bg-emerald-600 bg-opacity-20 rounded-md p-4 hover:bg-opacity-30"
                    @click="isCreatingGroup = true"
                >
                    <IconCreate />
                    <span class="text-lg font-semibold text-emerald-600">
                        Add new group
                    </span>
                </button>
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
                        <IconEdit @click="isEditingGroup = true" />
                        <IconDelete @click="deleteGroup(item.id)" />
                    </div>
                </router-link>
            </div>
        </div>
        <NotesList
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
import NoteGroupFormCreate from "./NoteGroupFormCreate.vue";
import NoteGroupFormEdit from "./NoteGroupFormEdit.vue";
import IconCreate from "./IconCreate.vue";
import IconEdit from "./IconEdit.vue";
import IconDelete from "./IconDelete.vue";
import NotesList from "./NotesList.vue";
import { useUserStore } from "../stores/users";
import { useNoteGroupsStore } from "../stores/noteGroups";
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
        IconCreate,
        NoteGroupFormCreate,
        NoteGroupFormEdit,
    },
    data() {
        return {
            isEditingGroup: false,
            isCreatingGroup: false,
            isExpanded: false,
            groupsList: [],
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
        ]),
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
            axios
                .post(`/api/notes/${groupId}/delete`, {}, this.getApiConfig())
                .then((res) => {
                    this.deleteNoteGroup({ id: groupId });
                    this.groupsList = this.getNoteGroups();

                    this.$router.push({
                        name: "content",
                        params: {
                            type: "notes",
                        },
                    });
                })
                .catch((err) => {
                    console.error(err);
                    alert("Failed deleting note group");
                });
        },
        closeForm() {
            this.isCreatingGroup = false;
            this.isEditingGroup = false;

            this.groupsList = this.getNoteGroups();
        },
    },
};
</script>
