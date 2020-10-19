<template>
  <v-layout align-center justify-center>
    <v-flex sm10 md8 lg6>
      <v-toolbar color="primary" dark flat class="d-flex justify-center">
        <v-toolbar-title>{{ $t('page.login.header')}}</v-toolbar-title>
      </v-toolbar>
      <login-form @submit="handle" :error="error" :not-found="notFound" />
      <v-card-actions class="mt-5">
        <v-row class="text--white">
          <v-col cols="12" class="pa1 text-center">
            <span class="text--white">{{ $t('page.login.registerLabel')}}</span>
            <v-btn text link :to="{ name: 'Register' }" color="white">{{$t('menu.main.register')}}!</v-btn>
          </v-col>
          <v-col v-if="notFound" cols="12" class="pa1 text-center">
            <span>{{ $t('page.login.resetPassLabel') }}</span>
            <v-btn text link :to="{ name: 'ResetPassword' }" color="white">{{ $t('link.reset') }}!</v-btn>
          </v-col>
        </v-row>
      </v-card-actions>
    </v-flex>
  </v-layout>
</template>
<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import { AppModule } from "../../App/AppModule";
import { UserModule } from "../UserModule";
import LoginForm from "../Components/Forms/LoginForm.vue";
import LoginRequest from "../Entity/API/Login/LoginRequest";

@Component({ components: { LoginForm } })
export default class LoginPage extends Vue{
    error: string = '';
    notFound: boolean = false;

    private handle(payloads: LoginRequest) {
        UserModule.login(payloads).then(() => {
            this.$router.push(AppModule.getRedirectToAuth);
        }).catch((error: AxiosError)=>{
            if(error.response?.data.errors && error.response?.data.errors.auth) {
                this.notFound = !!error.response?.data.errors.auth;
            }
            console.log(error.toJSON());
            console.log(error.response);
        });
    }

    beforeRouteEnter (to, from, next) {
        UserModule.isAuthenticated ? next(AppModule.getRedirectToAuth) : next();
    }
}
</script>