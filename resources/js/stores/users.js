import { defineStore } from "pinia";

export const useUserStore = defineStore("user", {
    state: () => {
        return {
            user: null,
        };
    },
    getters: {
        getUser: (state) => () => {
            return state.user;
        },
        getToken: (state) => () => {
            return state.user.token;
        },
    },
    actions: {
        setUser(payload) {
            this.user = payload.user;
        },
    },
});
