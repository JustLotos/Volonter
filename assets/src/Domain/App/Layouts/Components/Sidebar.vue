<template>
  <v-navigation-drawer v-model="getSidebar" app absolute clipped temporary color="primary">
    <v-list nav dense>
      <v-list-item v-for="item in menu" :key="item.name" link>
        <router-link :to="item.path" class="sidebar-link" @click="sidebar = false">
          <v-icon class="mr-5">{{ item.meta.icon }}</v-icon>{{ item.meta.label }}
        </router-link>
      </v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator';
import { RouteConfig } from "vue-router";
import { AppModule } from "../../AppModule";

@Component
export default class Sidebar extends Vue {
    @Prop() sidebar: boolean;

    get menu(): Array<RouteConfig>{ return AppModule.getApp.menu.getNavMenu }
    get getSidebar() { return this.sidebar }
    set getSidebar( value: boolean ) { this.$emit('change',  value)}
}
</script>

<style>
  .sidebar-link{
    color: white !important;
    text-decoration: none;
    font-size: 16px;
  }
</style>