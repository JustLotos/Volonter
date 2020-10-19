import * as constant from "./Types/Constants";
import {Status} from "./Types/Status";
import {Role} from "./Types/Role";
import RefreshTokenResponse from "./API/RefreshToken/RefreshTokenResponse";
import LoginResponse from "./API/Login/LoginResponse";
import RegisterResponse from "./API/Register/ByEmail/RegisterByEmailResponse";
import {ACCESS_TOKEN, EMAIL, ID, REFRESH_TOKEN, ROLE, STATUS} from "./Types/Constants";

export default class User {
    private _id: string;
    private _email: string;
    private _status: Status;
    private _role: Role;

    public _accessToken: string;
    private _refreshToken: string;

    public static isResetByEmail(route) {
        return route.query.hasOwnProperty('resetByEmailGetForm');
    }

    public login(data: LoginResponse | RegisterResponse) {
        this.accessToken = data.accessToken;
        this.refreshToken = data.refreshToken;
        this.status = <Status>data.status;
        this.role = <Role>data.role;
        this.email = data.email;
        this.id = data.id
        this.saveToLocalStorage();
    }
    logout() {
        this.refreshToken = '';
        this.accessToken = '';
        this.status = Status.DEFAULT;
        this.role = Role.DEFAULT;
        this.email = '';
        this.unsetDataFromLocalStorage();
    }

    public refresh (data: RefreshTokenResponse) {
        this.accessToken = data.accessToken;
        this.refreshToken = data.refreshToken;
        localStorage.setItem(ACCESS_TOKEN, this.accessToken);
        localStorage.setItem(REFRESH_TOKEN, this.refreshToken);
    }
    saveToLocalStorage () {
        localStorage.setItem(REFRESH_TOKEN, this.refreshToken);
        localStorage.setItem(ACCESS_TOKEN, this.accessToken);
        localStorage.setItem(STATUS, this.status);
        localStorage.setItem(ROLE, this.role);
        localStorage.setItem(EMAIL, this.email);
        localStorage.setItem(ID, this.id);
    }
    loadFromLocalStorage (): User {
        this.accessToken = <string>localStorage.getItem(ACCESS_TOKEN);
        this.refreshToken = <string>localStorage.getItem(REFRESH_TOKEN);
        this.status = <Status>localStorage.getItem(STATUS);
        this.role = <Role>localStorage.getItem(ROLE);
        this.email = <string>localStorage.getItem(EMAIL);
        this.id = <string>localStorage.getItem(ID);
        return this;
    }
    unsetDataFromLocalStorage() {
        localStorage.removeItem(ACCESS_TOKEN);
        localStorage.removeItem(REFRESH_TOKEN);
        localStorage.removeItem(STATUS);
        localStorage.removeItem(ROLE);
        localStorage.removeItem(EMAIL);
        localStorage.removeItem(ID);
    }

    public get id(): string {
        return this._id;
    }
    public set id(value: string) {
        this._id = value;
        return this;
    }
    public get email(): string {
        return this._email;
    }
    public set email(value: string) {
        this._email = value;
        return this._email;
    }
    public get status(): Status {
        return this._status
    }
    public set status(value: Status) {
        this._status = value;
        return this._status;
    }
    public get role(): Role {
        return this._role;
    }
    public set role(value: Role) {
        this._role = value;
        return this;
    }

    public get refreshToken(): string {
        if(this._refreshToken) {
            return this._refreshToken;
        }
        if(localStorage.getItem(constant.REFRESH_TOKEN)) {
            return <string>localStorage.getItem(constant.REFRESH_TOKEN);
        }
        return this._refreshToken;
    }
    public set refreshToken(value: string) {
        this._refreshToken = value;
        return this;
    }
    public get accessToken(): string {
        if(this._accessToken) {
            return this._accessToken;
        }
        if(localStorage.getItem(constant.ACCESS_TOKEN)) {
            return <string>localStorage.getItem(constant.ACCESS_TOKEN);
        }
        return this._accessToken;
    }
    public set accessToken(value: string) {
        this._accessToken = value;
        return this;
    }
}