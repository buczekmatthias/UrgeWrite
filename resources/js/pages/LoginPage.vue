<template>
    <form
        class="bg-white rounded-lg px-6 py-8 w-full max-w-lg flex flex-col items-center gap-10"
        @submit.prevent="handleLogin"
        ref="form"
    >
        <div class="flex flex-col items-center gap-3">
            <p class="uppercase text-lg font-extralight">UrgeWrite</p>
            <p class="text-5xl font-semibold">Login</p>
        </div>
        <div class="w-full flex flex-col gap-5">
            <div class="w-full flex flex-col gap-1">
                <label for="l-username">Username</label>
                <input
                    autofocus
                    v-model="username"
                    type="text"
                    id="l-username"
                    name="username"
                    class="input"
                    required
                />
            </div>
            <div class="w-full flex flex-col gap-1">
                <label for="l-password">Password</label>
                <input
                    v-model="password"
                    type="password"
                    id="l-password"
                    name="password"
                    class="input"
                    required
                />
            </div>
            <button
                class="w-full bg-emerald-700 hover:bg-emerald-600 text-white rounded-md py-3 flex justify-center"
                :class="
                    isLoading === true
                        ? 'pointer-events-none bg-emerald-900'
                        : ''
                "
                ref="submit"
            >
                <span v-if="!isLoading">Sign in</span>
                <Loader v-else />
            </button>
            <p
                class="w-full border-2 border-red-600 border-solid text-red-600 bg-red-600 bg-opacity-20 capitalize rounded-md px-4 py-3 font-semibold"
                v-for="(error, i) in errors"
                :key="i"
            >
                {{ error }}
            </p>
        </div>
        <p class="flex gap-1">
            <span>Are you new here?</span>
            <router-link
                :to="{ name: 'register' }"
                class="text-link font-semibold"
                >Join now!</router-link
            >
        </p>
    </form>
</template>

<script>
import { mapState } from "pinia";
import { useUserStore } from "../stores/users";
import { default as Loader } from "../components/IconLoader.vue";

export default {
    name: "LoginPage",
    data() {
        return {
            isLoading: false,
            errors: [],
            username: null,
            password: null,
        };
    },
    components: {
        Loader,
    },
    computed: {
        ...mapState(useUserStore, ["getUser", "setUser"]),
    },
    mounted() {
        if (this.getUser() !== null) {
            return this.$router.push({
                name: "content",
                params: { type: "notes" },
            });
        }

        this.$refs.form.parentElement.classList.add("login");
    },
    methods: {
        handleLogin() {
            this.errors = [];
            this.isLoading = true;

            axios
                .post(
                    "/api/auth/login",
                    {
                        username: this.username,
                        password: this.password,
                    },
                    {}
                )
                .then((res) => {
                    this.setUser({ user: res.data });
                    this.isLoading = false;

                    return this.$router.push({
                        name: "content",
                        params: { type: "notes" },
                    });
                })
                .catch((err) => {
                    this.errors.push(err.response.data.message);
                    this.isLoading = false;
                });
        },
    },
};
</script>

<style lang="postcss">
#app.login {
    @apply justify-center;
}
</style>
