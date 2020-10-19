import { Action, getModule, Module, Mutation, VuexModule } from "vuex-module-decorators";
import { Store } from "../App/Store";
import AuthService from "./UserService";
import User from "./Entity/User";
import RefreshTokenResponse from "./Entity/API/RefreshToken/RefreshTokenResponse";
import LoginResponse from "./Entity/API/Login/LoginResponse";
import LoginRequest from "./Entity/API/Login/LoginRequest";
import RegisterResponse from "./Entity/API/Register/ByEmail/RegisterByEmailResponse";
import ResetByEmailRequest from "./Entity/API/Reset/ByEmail/ResetByEmailRequest";
import ResetByEmailResponse from "./Entity/API/Reset/ByEmail/ResetByEmailConfirm";
import RegisterByEmailRequest from "./Entity/API/Register/ByEmail/RegisterByEmailRequest";
import ResetByEmailConfirm from "./Entity/API/Reset/ByEmail/ResetByEmailConfirm";

@Module({dynamic: true, store: Store, name: 'UserModule'})
class VuexUser extends VuexModule {
    public user: User = (new User()).loadFromLocalStorage();
    loading: boolean = false;

    get isAuthenticated(): boolean { return !!this.user.accessToken }
    get isLoading(): boolean { return this.loading }

    @Mutation
    LOADING() { this.loading = true }

    @Mutation
    UNSET_LOADING() { this.loading = false }

    @Mutation
    public LOGOUT() { this.user.logout() }

    @Mutation
    private REFRESH_TOKEN (data: RefreshTokenResponse) {
        this.user.refresh(data);
        this.loading = false;
    }

    @Mutation
    private LOGIN (data: LoginResponse | RegisterResponse) {
        this.user.login(data);
        this.loading = false;
    }

    @Action({ rawError: true })
    public async login(payload: LoginRequest): Promise<LoginResponse> {
        this.LOADING();
        const response  = await AuthService.login(payload);
        this.LOGIN(response.data);
        this.UNSET_LOADING();
        return response.data;
    }

    @Action({ rawError: true })
    public async register(payload: RegisterByEmailRequest): Promise<RegisterResponse> {
        this.LOADING();
        const response  = await AuthService.registerByEmail(payload);
        this.LOGIN(response.data);
        this.UNSET_LOADING();
        return response.data;
    }

    @Action({ rawError: true })
    public async resetByEmailRequest(payload: ResetByEmailRequest): Promise<any> {
        this.LOADING();
        const response  = await AuthService.resetByEmailRequest(payload);
        this.UNSET_LOADING();
        return response.data;
    }

    @Action({ rawError: true })
    public async resetByEmailConfirm(payload: ResetByEmailConfirm): Promise<any> {
        this.LOADING();
        const response  = await AuthService.resetByEmailConfirm(payload);
        this.UNSET_LOADING();
        return response.data;
    }

    @Action({ rawError: true })
    public async refreshToken(): Promise<RefreshTokenResponse> {
        this.LOADING();
        // Достаю токен из модуля так как не всегда могу актуальный передать токен
        const response = await AuthService.refreshToken({refreshToken: this.user.refreshToken});
        this.REFRESH_TOKEN(response.data);
        this.UNSET_LOADING();
        return response.data;
    }
}

export const UserModule = getModule(VuexUser);