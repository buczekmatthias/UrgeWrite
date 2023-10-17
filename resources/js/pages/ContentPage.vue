<template>
    <div
        ref="content"
        class="w-full flex flex-col gap-4"
        :class="
            $route.params.type === 'notes'
                ? 'max-w-screen-2xl'
                : 'max-w-screen-xl'
        "
    >
        <div class="flex flex-wrap justify-between items-baseline gap-5 box">
            <p class="text-xl">
                <span class="font-light">Urge</span>
                <span class="font-semibold">Write</span>
            </p>
            <div class="text-lg flex gap-3">
                <router-link
                    :to="{ name: 'content', params: { type: 'notes' } }"
                    class="p-1"
                    >Notes</router-link
                >
                <router-link
                    :to="{ name: 'content', params: { type: 'tasks' } }"
                    class="p-1"
                    >Tasks</router-link
                >
            </div>
            <div
                class="w-full -order-1 flex items-center justify-between md:order-3 md:w-fit md:gap-4"
            >
                <p class="">
                    Logged as, <b>{{ this.username }}</b>
                </p>
                <button
                    @click="handleLogout"
                    :class="isLoading ? 'loading' : ''"
                    class="hover:underline underline-offset-2 text-link font-semibold"
                >
                    Logout
                </button>
            </div>
        </div>

        <GroupsItem :tab="$route.params.type" />
    </div>
</template>

<script>
import { mapState } from "pinia";
import { useUserStore } from "../stores/users";
import { useNoteGroupsStore } from "../stores/noteGroups";
import { useNotesStore } from "../stores/notes";
import { useTaskGroupsStore } from "../stores/taskGroups";
import { useTasksStore } from "../stores/tasks";

import GroupsItem from "../components/GroupsItem.vue";

export default {
    name: "Homepage",
    data() {
        return {
            isLoading: false,
            username: "",
        };
    },
    components: {
        GroupsItem,
    },
    computed: {
        ...mapState(useUserStore, ["getUser", "getApiConfig", "logout"]),
        ...mapState(useNoteGroupsStore, [
            "getNoteGroups",
            "setNoteGroups",
            "resetNoteGroups",
        ]),
        ...mapState(useNotesStore, ["resetNotes"]),
        ...mapState(useTaskGroupsStore, ["resetTaskGroups"]),
        ...mapState(useTasksStore, ["resetTasks"]),
    },
    mounted() {
        this.$refs.content.parentElement.classList.remove("login", "register");
        let user = this.getUser();
        this.username = user.username;
    },
    methods: {
        handleLogout() {
            this.isLoading = true;
            axios
                .post("/api/auth/logout", {}, this.getApiConfig())
                .then(this.logoutActions)
                .catch(this.logoutActions);
        },
        logoutActions() {
            this.logout();
            this.resetNoteGroups();
            this.resetNotes();
            this.resetTaskGroups();
            this.resetTasks();

            this.isLoading = false;

            return this.$router.push({
                name: "login",
            });
        },
    },
};
</script>
