import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: "/login",
        component: () => import("../Auth/LoginPages.vue"),
    },
    {
        path: "/SignIn",
        component: () => import("../Auth/SignInPages.vue"),
    },
];
export default createRouter({
    history: createWebHistory(),
    routes,
});
