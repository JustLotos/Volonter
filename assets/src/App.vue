<template>
    <component :is="this.$root.$route.meta.layout || 'div'">
        <router-view/>
    </component>
</template>
<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { AppModule } from "./Domain/App/AppModule";
Component.registerHooks(['beforeRouteEnter']);
@Component
export default class App extends Vue {
    mounted() {
        AppModule.INIT(this.$router.routes);
    }
}
</script>
<style>
    .centered-input >>> input { text-align: center }
    .text--white {
        color: #FFFFFF !important;
    }
</style>