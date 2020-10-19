<template>
  <v-container fluid fill-height>
    <v-layout align-center justify-center>
      <v-flex sm10 md8 lg6>
        <v-toolbar color="primary" dark flat class="text-center">
            <v-toolbar-title>Регистрация</v-toolbar-title>
        </v-toolbar>
        <register-form @register="handle" :errors="errors"></register-form>
        <v-card-actions class="mt-5">
          <v-row justify="center" class="flex-wrap">
            <v-card dark flat class="transparent">
              <span>Есть учетная запись?</span>
              <v-btn text link :to="{name: 'Login'}" color="primary">Войти</v-btn>
            </v-card>
          </v-row>
        </v-card-actions>
      </v-flex>
    </v-layout>

    <modal v-model="modal"><v-alert type="success">{{modalMessage}}</v-alert></modal>
    </v-container>
</template>
<script lang="ts">
import {Component, Vue, Watch} from 'vue-property-decorator';
import RegisterForm from "../Components/Forms/RegisterForm.vue";
import {AppModule} from "../../App/AppModule";
import Modal from "../../App/Components/Modal.vue";
import {UserModule} from "../UserModule";
import RegisterByEmailRequest from "../Entity/API/Register/ByEmail/RegisterByEmailRequest";

Component.registerHooks(['beforeRouteEnter']);
@Component({components: {Modal, RegisterForm}})
export default class RegisterPage extends Vue{
    modal: boolean = false;
    modalMessage: string = 'На вашу почту отправлено письмо с подтвеждением аккаунта!';

    errors: RegisterByEmailRequest = {email: '', password: '', plainPassword: ''};

    private handle(payloads: RegisterByEmailRequest) {
        UserModule.register(payloads)
            .then(() => { this.modal = true })
            .catch((error: AxiosError) => {
                this.errors = error.response?.data.errors
                console.log(error.toJSON());
                console.log(error.response);
            });
    }

    @Watch('modal')
    onModalClose(value: boolean) {
        if(!value) {
            this.$root.$router.push( AppModule.getRedirectToUnAuth );
        }
    }

    beforeRouteEnter (to, from, next) {
        UserModule.isAuthenticated ? next( AppModule.getRedirectToUnAuth ) : next();
    }
}
</script>