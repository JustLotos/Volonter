<template>
    <v-footer app padless>
        <v-row class="flex primary">
            <v-col class="text-center">
                <v-btn v-for="item in footerMenu" :key="item.path" :to="item.path" class="mx-4" icon text color="white">
                    <v-icon size="24px">{{ item.meta.icon }}</v-icon>
                </v-btn>
                <label for="selectLocale"></label>
                <select id="selectLocale" style="color: white" v-model="selectLocale" @change="setLocale">
                    <option style="color: black"
                            v-for="(lang, i) in localeList"
                            :key="`Lang${i}`"
                            :value="lang.value"
                    >{{ lang.label }}</option>
                </select>
            </v-col>
        </v-row>
    </v-footer>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { AppModule } from "../../AppModule";
import { RouteConfig } from "vue-router";
@Component
export default class BaseFooter extends Vue {
    footerMenu: Array<RouteConfig> = AppModule.getApp.menu.getFooterMenu;
    localeList: Array<Object> = AppModule.getApp.locale.getLocaleList;
    selectLocale: string = this.$root.$i18n.locale;

    public setLocale() {
        this.$root.$i18n.locale = this.selectLocale;
        AppModule.getApp.locale.language = this.selectLocale;
        this.$router.go(0);
    }
}
</script>