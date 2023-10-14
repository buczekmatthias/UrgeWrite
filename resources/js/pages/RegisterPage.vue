<template>
    <form
        class="bg-white rounded-lg px-6 py-8 w-full max-w-lg flex flex-col items-center gap-10"
        @submit="handleRegister"
        ref="form"
    >
        <div class="flex flex-col items-center gap-3">
            <p class="uppercase text-lg font-extralight">UrgeWrite</p>
            <p class="text-5xl font-semibold">Register</p>
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
                    class="outline-transparent rounded-md px-2 py-2 text-sm border-2 border-solid border-primary focus:border-focus"
                    required
                />
            </div>
            <div class="w-full flex flex-col gap-1">
                <label for="l-email">Email</label>
                <input
                    v-model="email"
                    type="email"
                    id="l-email"
                    name="email"
                    class="outline-transparent rounded-md px-2 py-2 text-sm border-2 border-solid border-primary focus:border-focus"
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
                    class="outline-transparent rounded-md px-2 py-2 text-sm border-2 border-solid border-primary focus:border-focus"
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
                <span v-if="!isLoading">Create account</span>
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
            <span>Are you a member?</span>
            <router-link :to="{ name: 'login' }" class="text-link font-semibold"
                >Sign in</router-link
            >
        </p>
    </form>
</template>

<script>
import axios from "axios";
import { mapState } from "pinia";
import { useUserStore } from "../stores/users";
import { default as Loader } from "../components/IconLoader.vue";

export default {
    name: "RegisterPage",
    data() {
        return {
            isLoading: false,
            errors: [],
            username: null,
            email: null,
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

        this.$refs.form.parentElement.classList.add("register");
    },
    methods: {
        handleRegister(e) {
            e.preventDefault();
            this.errors = [];
            this.isLoading = true;

            axios
                .post(
                    "/api/auth/register",
                    {
                        username: this.username,
                        email: this.email,
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
#app.login,
#app.register {
    @apply justify-center;
}
</style>
