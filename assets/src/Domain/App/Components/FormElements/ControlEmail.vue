<template>
  <v-text-field
    validate-on-blur
    type="email"
    prepend-icon="mdi-email"
    :label="$t('component.control.email.label')"
    v-model="value"
    :rules="rules"
    :error-messages="error"
  ></v-text-field>
</template>

<script>
const EVENT_NAME = 'emailChange'.toLowerCase();
export default {
    name: "ControlEmail",
    props: ['email' , 'error'],
    model: {prop: 'email', event: EVENT_NAME },
    computed: {
        value: {
            get: function() { return this.email},
            set: function(value) { this.$emit(EVENT_NAME, value) }
        }
    },
    data: function () {
        return {
            rules: [
                v => !!v || this.$t('component.control.email.required'),
                v => /.+@.+\..+/.test(v) || this.$t('component.control.email.valid'),
            ],
        }
    }
}
</script>