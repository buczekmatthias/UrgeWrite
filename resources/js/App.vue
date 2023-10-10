<template>
    <router-view />
</template>

<script>
import { useUserStore } from "./stores/users";
import { mapState } from "pinia";

export default {
    name: "App",
    computed: {
        ...mapState(useUserStore, ["getUser"]),
    },
    mounted() {
        if (this.getUser() === null)
            return this.$router.push({ name: "login" });
    },
};
</script>
