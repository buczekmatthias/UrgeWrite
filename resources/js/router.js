import { createRouter, createWebHistory } from "vue-router";

import LoginPage from "./pages/LoginPage.vue";
import RegisterPage from "./pages/RegisterPage.vue";
import ContentPage from "./pages/ContentPage.vue";

const routes = [
    {
        path: "/",
        redirect: "/notes",
    },
    {
        path: "/login",
        name: "login",
        component: LoginPage,
    },
    {
        path: "/register",
        name: "register",
        component: RegisterPage,
    },
    {
        path: "/:type/:group?/:elementId?",
        name: "content",
        component: ContentPage,
        beforeEnter: (to, from) => {
            if (!["notes", "tasks"].includes(to.params.type)) {
                return { name: "content", params: { type: "notes" } };
            }
        },
    },
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes,
});
export default router;
