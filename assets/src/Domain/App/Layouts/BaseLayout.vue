<template>
    <v-app id="inspire" >
        <base-header/>
        <transition name="fade" duration="2000">
            <v-content class="bg">
                <v-container fill-height>
                    <slot/>
                </v-container>
            </v-content>
        </transition>
        <base-footer/>
    </v-app>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import BaseHeader from "./Components/BaseHeader.vue";
import BaseFooter from "./Components/BaseFooter.vue";
import Sidebar from "./Components/Sidebar.vue";

@Component({components: {BaseHeader, BaseFooter, Sidebar}})
export default class BaseLayout extends Vue {}
</script>

<style scoped>
    .bg{
        background: #0c5460 url('/build/vbanner.jpg') no-repeat fixed center center;
    }
</style>