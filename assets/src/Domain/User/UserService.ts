import Axios from "../../Plugins/Axios";
import {RouterApi} from "../App/RouterAPI";
import LoginRequest from "./Entity/API/Login/LoginRequest";
import LoginResponse from "./Entity/API/Login/LoginResponse";
import RegisterByEmailRequest from "./Entity/API/Register/ByEmail/RegisterByEmailRequest";
import RegisterByEmailResponse from "./Entity/API/Register/ByEmail/RegisterByEmailResponse";
import RefreshTokenResponse from "./Entity/API/RefreshToken/RefreshTokenResponse";
import RefreshTokenRequest from "./Entity/API/RefreshToken/RefreshTokenRequest";
import ResetByEmailRequest from "./Entity/API/Reset/ByEmail/ResetByEmailRequest";
import ResetByEmailConfirm from "./Entity/API/Reset/ByEmail/ResetByEmailConfirm";

export default {
    async login(payloads: LoginRequest): AxiosResponse<LoginResponse> {
        return Axios.post( RouterApi.getUrlByName('login').path, payloads);
    },
    async registerByEmail(payloads: RegisterByEmailRequest): AxiosResponse<RegisterByEmailResponse> {
        return Axios.post( RouterApi.getUrlByName('registerByEmail').path, payloads);
    },
    async refreshToken(payloads: RefreshTokenRequest): AxiosResponse<RefreshTokenResponse> {
        return Axios.post( RouterApi.getUrlByName('refreshToken').path, payloads);
    },
    async resetByEmailRequest(payloads: ResetByEmailRequest): AxiosResponse<any> {
        return Axios.post( RouterApi.getUrlByName('resetByEmail').path, payloads);
    },
    async resetByEmailConfirm(payloads: ResetByEmailConfirm): AxiosResponse<any> {
        return Axios.post( RouterApi.getUrlByName('resetByEmailConfirm').path, payloads);
    }
};
