import {RouteConfig} from "vue-router";
import BaseLayout from "../App/Layouts/BaseLayout.vue";
import RegisterPage from "./Pages/RegisterPage.vue";
import LogoutPage from "./Pages/LogoutPage.vue";
import LoginPage from "./Pages/LoginPage.vue";
import ResetByEmailPage from "./Pages/ResetByEmailPage.vue";
import i18n from "../../Plugins/I18n/I18n";

export const UserRoutes: Array<RouteConfig> = [
    {
        path: '/login/', name: 'Login', component: LoginPage,
        meta: { label: i18n.t('menu.main.login'), icon: 'mdi-login', menu: true, auth: false, layout: BaseLayout}
    },
    {
        path: '/register/', name: 'Register', component: RegisterPage,
        meta: { label: i18n.t('menu.main.register'), icon: 'mdi-account-multiple-plus', menu: true, auth: false, layout: BaseLayout}
    },
    {
        path: '/logout/', name: 'Logout', component: LogoutPage,
        meta: { label: i18n.t('menu.main.logout'), icon: 'mdi-logout', menu: true, auth: true, layout: BaseLayout},
    },
    {
        path: '/reset/email/', name: 'ResetByEmail', component: ResetByEmailPage,
        meta: { label: 'Восстановить пароль', icon: 'mdi-logout', menu: false, auth: false, layout: BaseLayout},
    },
];