<template>
    <v-card class="elevation-10">
        <v-form ref="resetForm" @submit="submit">
            <v-row justify="center">
                <v-col cols="12" sm="9" class="text-center">
                    <v-sheet>Введите данные для восстановления</v-sheet>
                </v-col>
                <v-col cols="12" sm="9" class="pa1">
                    <control-email v-model="payloads.email" :error="getErrors.email" />
                </v-col>
            </v-row>
            <v-divider></v-divider>
            <v-card-actions class="justify-center">
                <v-btn class="pa2 text-center primary" @click="submit" :loading="loading">Восстановить</v-btn>
            </v-card-actions>
        </v-form>
    </v-card>
</template>


<script lang="ts">
import {Component, Prop, Vue} from "vue-property-decorator";
import {UserModule} from "../../UserModule";
import ControlEmail from "../../../App/Components/FormElements/ControlEmail.vue";
import ControlPassword from "../../../App/Components/FormElements/ControlPassword.vue";
import ControlConfirm from "../../../App/Components/FormElements/ControlConfirm.vue";
import ResetByEmailRequest from "../../Entity/API/Reset/ByEmail/ResetByEmailRequest";

@Component({components: { ControlEmail, ControlPassword, ControlConfirm}})
export default class ResetByEmailForm extends Vue  {
    @Prop() errors: ResetByEmailRequest;
    payloads: ResetByEmailRequest = { email: '' };

    get getErrors(): ResetByEmailRequest {
        return this.errors || { email: '' };
    }

    get loading() { return UserModule.isLoading }

    submit() {
        if(this.$refs.resetForm.validate()) {
            this.$emit('reset', this.payloads);
        }
    }
}
</script>