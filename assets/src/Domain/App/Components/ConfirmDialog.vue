<template>
    <v-flex v-if="!isVerify" xs10 class="justify-center">
        <v-card>
            <v-flex xs10 offset-xs1 class="text-center">
                <v-card-title primary-title class="justify-center">{{confirmationMessage}}</v-card-title>
                <v-card-actions class="justify-center">
                    <v-btn color="primary" @click="confirm">{{getConfirm}}</v-btn>
                    <v-btn color="primary" @click="deny">{{getDeny}}</v-btn>
                </v-card-actions>
            </v-flex>
        </v-card>
    </v-flex>
    <v-flex v-else xs10 class="justify-center">
        <v-card>
            <v-flex xs10 offset-xs1 class="text-center">
                <v-card-title primary-title class="justify-center">{{confirmationMessage}}</v-card-title>
                <v-card-subtitle primary-title class="justify-center">{{verify}}</v-card-subtitle>
                <v-form ref="confirmForm" @submit="confirmWithVerify">
                    <v-text-field label="Название" required validate-on-blur class="centered-input"
                        v-model="value"
                        :error-messages="message"
                    ></v-text-field>
                </v-form>
                <v-card-actions class="justify-center">
                    <v-btn color="primary" @click="confirmWithVerify">{{getConfirm}}</v-btn>
                    <v-btn color="primary" @click="deny">{{getDeny}}</v-btn>
                </v-card-actions>
            </v-flex>
        </v-card>
    </v-flex>
</template>

<script lang="ts">
    import {Component, Prop, Vue, Watch} from "vue-property-decorator";
    import {AppModule} from "../AppModule";

const SIMPLE = 'SIMPLE';
const VERIFY = 'VERIFY';
@Component
export default class ConfirmDialog extends Vue{
    value: string = '';
    message: string = '';

    @Prop({required: true }) confirmationMessage: string;
    @Prop({required: true }) confirmOperationPhrase: string;
    @Prop({required: true }) confirmDenyPhrase: string;
    @Prop({required: false}) verify: string;

    get getConfirm() { return this.confirmOperationPhrase || 'Yes' };
    get getDeny() { return this.confirmDenyPhrase || 'No' };

    get isVerify () {return this.verify || false};
    confirm () { this.$emit('confirm') };
    deny () { this.$emit('deny') };

    confirmWithVerify (event) {
        if(event.type === 'submit') {
            event.preventDefault()
        }
        if(this.value.toLowerCase() === this.verify.toLowerCase().trim()) {
            this.$emit('confirm');
            this.$refs.confirmForm.reset();
        } else {
            this.message = 'Value is not identical';
        }
    };
}
</script>