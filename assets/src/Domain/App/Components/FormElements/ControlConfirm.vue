<template>
    <v-text-field validate-on-blur prepend-icon="mdi-lock"
        :label="label"
        v-model="value"
        :rules="rules"
        :type="show ? 'text' : 'password'"
        :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
        :error-messages="error"
        @click:append="show = !show"
    ></v-text-field>
</template>

<script>
    const EVENT_NAME = 'ControlConfirmChange'.toLowerCase();
    export default {
        name: "ControlConfirm",
        props: ['confirmField', 'field', 'error', 'label'],
        model: {prop: 'confirmField', event: EVENT_NAME},
        computed: {
            value: {
                get: function() {return this.confirmField},
                set: function(value) { this.$emit(EVENT_NAME, value)}
            }
        },
        data: function () {
            return {
                show: false,
                rules: [
                    v => !!v || 'Это поле обязательно для заполнения',
                    v => v === this.field || 'Поля не совпадают',
                ],
            }
        },
    }
</script>