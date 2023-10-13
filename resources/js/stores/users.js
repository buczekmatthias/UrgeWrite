import { defineStore } from "pinia";
import { useLocalStorage } from "@vueuse/core";

export const useUserStore = defineStore("user", {
    state: () => {
        return {
            user: useLocalStorage("user", null),
        };
    },
    getters: {
        getUser: (state) => () => JSON.parse(state.user),
        getApiConfig: (state) => () => {
            let user = JSON.parse(state.user);
            return user !== null
                ? {
                      headers: {
                          Accept: "application/json",
                          Authorization: `Bearer ${user.token}`,
                      },
                  }
                : {
                      headers: {
                          Accept: "application/json",
                      },
                  };
        },
    },
    actions: {
        setUser(payload) {
            this.user = JSON.stringify(payload.user);
        },
        logout() {
            this.user = null;
        },
    },
});
