<template>
    <v-text-field validate-on-blur prepend-icon="mdi-lock"
            :label="$t('component.control.password.label')"
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
                v => !!v || this.$t('component.control.password.required'),
                v => (v && v.length >= PASSWORD_LENGTH) ||
                    this.$t('component.control.password.required').replace('6', PASSWORD_LENGTH),
            ],
        }
    }
}
</script>