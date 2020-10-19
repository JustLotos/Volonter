<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center>
            <h1 class="text-center text--white">{{ $t("welcome") }}</h1>
        </v-layout>
    </v-container>
</template>
<script lang="ts">
import { Component, Vue } from "vue-property-decorator";
@Component
export default class HomePage extends Vue{}
</script>