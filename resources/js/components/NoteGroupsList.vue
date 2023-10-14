<template>
    <div class="flex flex-col gap-4">
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
                <router-link
                    class="sub-link flex flex-col gap-2"
                    :to="{
                        name: 'content',
                        params: { type: $route.params.type, group: item.id },
                    }"
                    v-for="(item, i) in groupsList"
                    :key="i"
                >
                    <p class="text-2xl">{{ item.name }}</p>
                    <p class="text-sm font-normal">
                        {{ item.notes_count }} notes
                    </p>
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
import NotesList from "./NotesList.vue";
import { useNoteGroupsStore } from "../stores/noteGroups";
import { mapState } from "pinia";

export default {
    name: "NoteGroupsList",
    props: {
        list: Array,
    },
    components: {
        NotesList,
    },
    data() {
        return {
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
        ...mapState(useNoteGroupsStore, ["getNoteGroups", "decreaseNoteCount"]),
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
    },
};
</script>
