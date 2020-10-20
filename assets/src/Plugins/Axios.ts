import axios, {AxiosRequestConfig} from "axios";
import { router as Router } from "../Domain/User/Guard";
import { UserModule } from "../Domain/User/UserModule";
import {RouterApi} from "../Domain/App/RouterAPI";

let Axios = axios.create({
    headers: { "Content-type": "application/json" }
});

Axios.interceptors.response.use(
    (response) => {
        return response
    },

    function (error) {
        UserModule.UNSET_LOADING();
        const originalRequest: AxiosRequestConfig = error.config;

        if (error.response?.status === 401 && originalRequest.url === RouterApi.getUrlByName('refreshToken').path) {
            UserModule.LOGOUT();
            return Router.push({name: 'Login'});
        }

        if ( error.response?.status === 401 &&
            !originalRequest._retry &&
            RouterApi.isNotAuthRequiredRoute({ path: <string>originalRequest.url})
        ) {
            originalRequest._retry = true;
            UserModule.refreshToken().then(() => {
                originalRequest.headers.common['Authorization'] = 'Bearer' + UserModule.user.accessToken;
                return axios(originalRequest);
            });
        }

        Logger.logAPIError(error);
        //debugger;
        return Promise.reject(error);
    }
);

Axios.interceptors.request.use(
    config => {
        if (UserModule.isAuthenticated) {
            config.headers['Authorization'] = 'Bearer ' + UserModule.user.accessToken;
        }
        return config;
    },
    error => Promise.reject(error)
);

export default Axios;