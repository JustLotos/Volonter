<template>
    <v-text-field validate-on-blur prepend-icon="mdi-lock"
            label="Пароль"
            v-model="value"
            :rules="rules"
            :type="show ? 'text' : 'password'"
            :append-icon="show ? 'mdi-eye' : 'mdi-eye-off'"
            :error-messages="error"
            @click:append="show = !show"
    ></v-text-field>
</template>

<script>
const EVENT_NAME = 'passwordChange'.toLowerCase();
const PASSWORD_LENGTH = 8;
export default {
    name: "ControlPassword",
    props: [ 'password', 'error' ],
    model: { prop: 'password', event: EVENT_NAME },
    computed: {
        value: {
            get: function() { return this.password },
            set: function(value) { this.$emit(EVENT_NAME, value) }
        }
    },
    data: function () {
        return {
            show: false,
            rules: [
                v => !!v || 'Пароль обязателен для заполнения',
                v => (v && v.length >= PASSWORD_LENGTH) || 'Пароль должен быть больше '+PASSWORD_LENGTH+' символов',
            ],
        }
    }
}
</script>