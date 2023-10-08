import "./bootstrap";
import { createApp } from "vue";
import { default as store } from "./store";
import router from "./router";
import App from "./App.vue";

createApp(App).use(router).use(store).mount("#app");
