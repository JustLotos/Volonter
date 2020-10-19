<template></template>
<script lang="ts">
import {Component, Vue} from "vue-property-decorator";
import {UserModule} from "../UserModule";

@Component
export default class LogoutPage extends Vue {
    beforeRouteEnter (to, from, next) {
        UserModule.LOGOUT();
        next({name: "Home"})
    }
}
</script>