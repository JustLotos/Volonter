<template>
    <v-app-bar app clipped-left dark color="primary">
        <sidebar :sidebar="sidebar" @change="toggleSideBar"/>
        <v-app-bar-nav-icon class="hidden-md-and-up" @click.stop="openSideBar"/>
        <div class="text-center">
            <v-btn large  color="primary" @click="handleLogoRedirect">
                <v-icon left>{{ logo.meta.icon }}</v-icon> {{ logo.meta.label }}
            </v-btn>
        </div>
        <v-spacer/>
        <navbar/>
    </v-app-bar>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import Navbar from './Navbar.vue';
import { AppModule } from "../../AppModule";
import { UserModule } from "../../../User/UserModule";
import { RouteConfig } from "vue-router";
import Sidebar from "./Sidebar.vue";

@Component({components: {Sidebar, Navbar}})
export default class BaseHeader extends Vue {
    logo: RouteConfig = AppModule.getApp.logo;
    sidebar: boolean = false;

    handleLogoRedirect() {
        if (this.$route.name !== this.$router.resolve({name: 'Home'}).resolved.name &&
            this.$route.name !== this.$router.resolve({name: 'Dashboard'}).resolved.name
        ) {
            if(UserModule.isAuthenticated) { return this.$router.push({name: 'Dashboard'}) }
            return this.$router.push({name: 'Home'});
        }
    }

    private openSideBar() { this.sidebar = true }
    private toggleSideBar(value: boolean) { this.sidebar = value}
}
</script>