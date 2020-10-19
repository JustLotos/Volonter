<template>
  <v-card class="elevation-10">
    <v-form ref="registerForm">
      <v-row justify="center" align="center" style="flex-direction: column">
        <v-col cols="10" sm="8" md="10" class="text-center">
          <v-sheet>Введите данные для регистрации</v-sheet>
        </v-col>
        <v-col cols="10" sm="8" md="8"  class="pt-0 mt-0">
          <control-email v-model="data.email" :error="getErrors.email"/>
        </v-col>
        <v-col cols="10" sm="8" md="8" class="pt-0 mt-0">
          <control-password v-model="data.password" :error="getErrors.password"/>
        </v-col>
        <v-col cols="10" sm="8" md="8"  class="pa1">
          <control-confirm
            v-model="data.plainPassword"
            :error="getErrors.plainPassword"
            :field="data.password"
            label="Подтверждение пароля"
          />
        </v-col>
      </v-row>
      <v-divider/>
      <v-card-actions class="justify-center pb-3 pt-3">
        <v-btn
          class="pa2 text-center primary"
          @click="register"
          :loading="loading"
          x-large
          elevation="24"
        >Зарегистрироваться</v-btn>
      </v-card-actions>
    </v-form>
  </v-card>
</template>

<script lang="ts">
import {Component, Prop, Vue} from "vue-property-decorator";
import ControlEmail from "../../../App/Components/FormElements/ControlEmail.vue";
import ControlPassword from "../../../App/Components/FormElements/ControlPassword.vue";
import ControlConfirm from "../../../App/Components/FormElements/ControlConfirm.vue";
import {UserModule} from "../../UserModule";
import RegisterByEmailRequest from "../../Entity/API/Register/ByEmail/RegisterByEmailRequest";

@Component({components: { ControlEmail, ControlPassword, ControlConfirm}})
export default class RegisterForm extends Vue  {
    @Prop() errors: RegisterByEmailRequest;

    private data: RegisterByEmailRequest = { email: '', password: '', plainPassword: ''};

    get loading() { return UserModule.isLoading }
    get getErrors(): RegisterByEmailRequest {
        return this.errors || { email: '', password: '', plainPassword: '' }
    }
    register() {
        if(this.$refs.registerForm.validate()) {
            this.$emit('register', this.data);
        }
    }
}
</script>