<template>
    <v-container fluid fill-height>
        <v-layout align-center justify-center>
          <div>
            <h1 class="text-center text--white">{{$t("welcome")}}</h1>
            <p class="text-center text--white">Это главная вкладка приложения и скоро тут появятся виджеты</p>
          </div>
        </v-layout>
    </v-container>
</template>
<script lang="ts">
import {Component, Vue} from "vue-property-decorator";
@Component
export default class Dashboard extends Vue{}
</script>