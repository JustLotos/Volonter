<template>
    <v-layout align-center justify-center>
        <v-flex sm10 md8 lg6>
            <v-toolbar color="primary" dark flat>
                <v-toolbar-title>Восстановление</v-toolbar-title>
            </v-toolbar>
            <reset-by-email-form v-if="!isReset" @reset="handleRequest" :errors="errorsRequest" />
            <reset-by-email-confirm-form v-else @reset="handleConfirm" :errors="errorsResponse" />
            <v-card-actions class="mt-5">
                <v-row justify="center" class="flex-wrap">
                    <v-card flat class="transparent">
                        <span class="text--white">Есть учетная запись?</span>
                        <v-btn text link :to="{name: 'Login'}" color="primary">Войти</v-btn>
                    </v-card>
                </v-row>
            </v-card-actions>
        </v-flex>
        <modal v-model="modal"><v-alert type="success">{{modalMessage}}</v-alert></modal>
    </v-layout>
</template>

<script lang="ts">
import {Component, Vue} from 'vue-property-decorator';
import ResetByEmailForm from "../Components/Forms/ResetByEmailRequestForm.vue";
import ResetByEmailConfirmForm from "../Components/Forms/ResetByEmailConfirmForm.vue";
import {AppModule} from "../../App/AppModule";
import Modal from "../../App/Components/Modal.vue"
import {UserModule} from "../UserModule";
import ResetByEmailRequest from "../Entity/API/Reset/ByEmail/ResetByEmailRequest";
import ResetByEmailConfirm from "../Entity/API/Reset/ByEmail/ResetByEmailConfirm";
import Router from "../../App/Router";

@Component({components: {ResetByEmailConfirmForm, ResetByEmailForm, Modal}})
export default class ResetByEmailPage extends Vue{
    modal: boolean = false;
    modalMessage: string = '';
    errorsRequest: ResetByEmailRequest = {email: '' };
    errorsResponse: ResetByEmailConfirm = {password: '', plainPassword: '', token: ''};
    isReset: boolean = !!localStorage.getItem('temporaryToken');

    beforeRouteEnter (to, from, next) {
        UserModule.isAuthenticated ? next(AppModule.getRedirectOnUnguardedPath) : next();
        if (to.query && to.query.isReset && to.query.token) {
            localStorage.setItem('temporaryToken', to.query.token);
        } else {
            localStorage.removeItem('temporaryToken');
        }
    }

    private handleConfirm(payloads: ResetByEmailConfirm) {
        payloads.token = <string>localStorage.getItem('temporaryToken');
        if(!payloads.token) {
            this.modal = !this.modal;
            this.modalMessage = 'Токен не установлен';
        }

        UserModule.resetByEmailConfirm(payloads)
            .then(() => {
              this.modalMessage = 'Пароль успешно изменен';
              this.modal = !this.modal;
              setTimeout(function () {
                  Router.push({name: "Login"});
              }, 3000);
            })
            .catch((error: AxiosError) => {
                if(error.response?.data.errors) {
                    if(error.response?.data.errors.token) {
                        this.modal = !this.modal;
                        this.modalMessage = error.response?.data.errors.token;
                    } else {
                        this.errorsResponse = error.response?.data.errors;
                    }
                }

                console.log(error.response);
                console.log(error.toJSON());
            });
    }

    private handleRequest(payloads: ResetByEmailRequest) {
        UserModule.resetByEmailRequest(payloads)
            .then(() => {
                this.modal = !this.modal;
                this.modalMessage = 'Письмо отправелно!, поверьте ваш email';
            })
            .catch((error: AxiosError) => {
                if(error.response?.data.errors) {
                    this.errorsRequest = error.response?.data.errors;
                }
                console.log(error.response);
                console.log(error.toJSON());
            });
    }
}
</script>