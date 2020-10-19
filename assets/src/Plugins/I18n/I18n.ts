import Vue from 'vue';
import VueI18n from 'vue-i18n';
import {AppModule} from "../../Domain/App/AppModule";
Vue.use(VueI18n);

const i18n = new VueI18n({
    locale: AppModule.app.locale.getLocale,
    messages: AppModule.app.locale.getMessages
});

export default i18n;