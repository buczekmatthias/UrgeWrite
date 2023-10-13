<template>
    <router-view />
</template>

<script>
import { useUserStore } from "./stores/users";
import { useRouter } from "vue-router";

export default {
    name: "App",
    setup() {
        const store = useUserStore();
        const router = useRouter();

        if (store.getUser() === null) {
            router.push({ name: "login" });
        }
    },
};
</script>
