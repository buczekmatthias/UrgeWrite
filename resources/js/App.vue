<template>
    <router-view />
</template>

<script>
import { mapGetters } from "vuex";

export default {
    name: "App",
    computed: {
        ...mapGetters(["getUser"]),
    },
    mounted() {
        if (this.getUser() === null)
            return this.$router.push({ name: "login" });
    },
};
</script>
