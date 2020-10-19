import { VuexModule, Module, Mutation, getModule } from 'vuex-module-decorators'
import {Store} from "./Store";
import {Application} from "./Entity/Application";
import {RouteConfig} from "vue-router";

@Module({dynamic: true, store: Store, name: 'AppModule' , namespaced: true})
class VuexApplication extends VuexModule {
    private app: Application = new Application();

    get getApp(): Application {
        return this.app;
    }
    get isResetValidation() {
        return false;
    }
    get getRedirectToUnAuth() {
        return {name: 'Login'};
    }
    get getRedirectToAuth() {
        return {name: 'Dashboard'};
    }

    @Mutation
    public INIT(routes: Array<RouteConfig>) {
        this.app.menu.setRoutes(routes);
    }
}

export const AppModule = getModule(VuexApplication);