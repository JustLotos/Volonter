<template>
  <v-card class="elevation-10">
    <v-form ref="loginForm">
      <v-row justify="center" align="center" style="flex-direction: column">
        <v-col cols="10" sm="8" md="10">
          <v-sheet class="text-center">Введите данные для авторизации</v-sheet>
          <v-alert v-if="notFound" type="error" transition="fade-transition">Пользователь не найден</v-alert>
        </v-col>
        <v-col cols="10" sm="8" md="8" class="pt-0 mt-0">
          <control-email v-model="data.email" :error="getError.email"/>
        </v-col>
        <v-col cols="10" sm="8" md="8">
          <control-password v-model="data.password" :error="getError.password"/>
        </v-col>
        <v-col cols="10" sm="8" md="8" class="pt-0 mt-0 pb-2">
          <v-layout justify-center>
            <v-checkbox
              class="pt-0 mt-0 align-self-center"
              hide-details
              v-model="data.rememberMe"
              :error="getError.rememberMe"
              label="Запомнить меня"
            />
          </v-layout>
        </v-col>
      </v-row>

      <v-divider></v-divider>
      <v-card-actions class="justify-center pb-3 pt-3">
        <v-btn
          class="pa2"
          color="primary"
          @click="submit"
          :loading="loading"
          x-large
          elevation="24"
        >Войти</v-btn>
      </v-card-actions>
    </v-form>
  </v-card>
</template>

<script lang="ts">
import {Component, Prop, Vue} from 'vue-property-decorator';
import ControlEmail from "../../../App/Components/FormElements/ControlEmail.vue";
import ControlPassword from "../../../App/Components/FormElements/ControlPassword.vue";
import LoginRequest from "../../Entity/API/Login/LoginRequest";
import {UserModule} from "../../UserModule";

@Component({components: { ControlEmail, ControlPassword}})
export default class LoginForm extends Vue {
    @Prop() error: LoginRequest;
    @Prop() notFound: boolean;

    private data: LoginRequest =  {
        email: '',
        password: '',
        rememberMe: false
    };

    get getError(): LoginRequest {
      return this.error || { email: '', password: '', rememberMe: false }
    }
    get loading() { return UserModule.isLoading }

    public submit() {
        if(this.$refs.loginForm.validate()) {
            this.$emit('submit', this.data);
        }
    }
}
</script>