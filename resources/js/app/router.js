import {createRouter, createWebHistory} from "vue-router";

import MainPage from "./Pages/MainPage";

const routes = [
    {
        path: '/',
        component: MainPage,
        name: 'main'
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
