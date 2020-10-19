<template>
    <v-text-field label="Название" name="name" type="text" validate-on-blur
        v-model="value"
        :rules="rules"
        :error-messages="error"
    ></v-text-field>
</template>

<script>
    const EVENT_NAME = 'nameChange'.toLowerCase()
    export default {
        name: "ControlName",
        props: [ 'name', 'error'],
        model: { prop: 'name', event: EVENT_NAME },
        computed: {
            value: {
                get: function() { return this.name; },
                set: function(value) { this.$emit( EVENT_NAME, value); }
            }
        },
        data: function () {
            return { rules: [ v => !!v || 'Название обязательено для заполнения'] }
        }
    }
</script>