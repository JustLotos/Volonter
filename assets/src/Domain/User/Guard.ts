import Router from "../App/Router";
import {AxiosError} from "axios";
import {RawLocation} from "vue-router/types/router";
import {UserModule} from "./UserModule";
import User from "./Entity/User";

Router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.auth)) {
        if (!UserModule.isAuthenticated) {
            next({name: "Login", query: { redirect: to.fullPath }});
        }
    }

    if(User.isResetByEmail(to)) {
        next({name: 'ResetByEmail', query: { isReset: true, token: to.query.token }});
    }
    next();
});

export function handle404Exception(error: AxiosError, route: RawLocation) {
    if( error.response?.status == 404) {
        console.log(error.response.data);
        return Router.push(route);
    }
}

export const router = Router;