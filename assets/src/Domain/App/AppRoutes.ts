import {RouteConfig} from "vue-router";
import HomePage from "./Pages/HomePage.vue";
import BaseLayout from "./Layouts/BaseLayout.vue";
import Dashboard from "./Pages/Dashboard.vue";

export const AppRoutes: Array<RouteConfig> = [
    {
        path: '/', name: 'Home', component: HomePage,
        meta: { label: 'FlashBack', icon: 'mdi-flash', menu: false, auth: false, layout: BaseLayout},
    },
    {
        path: '/dashboard', name: 'Dashboard', component: Dashboard,
        meta: { label: 'FlashBack', icon: 'mdi-flash', menu: false, auth: true, layout: BaseLayout}
    },
];