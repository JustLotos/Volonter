import Vue from "vue";
import Vuex from "vuex";
import {AppModule} from "./AppModule";
import {UserModule} from "../User/UserModule";

Vue.use(Vuex);

export interface IRootState {
    UserModule: UserModule;
    AppModule: AppModule;
}

export const Store = new Vuex.Store<IRootState>({})
