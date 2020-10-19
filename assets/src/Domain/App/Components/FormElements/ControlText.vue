<template>
    <v-textarea auto-grow row-height="24" rows="2"
            v-model="value"
            :label="label"
    ></v-textarea>
</template>

<script>
    const EVENT_NAME = 'ControlTextChange'.toLowerCase();
    export default {
        name: "ControlText",
        props: ['text', 'error', 'label'],
        model: {prop: 'text', event: EVENT_NAME},
        computed: {
            value: {
                get: function() {return this.text},
                set: function(value) {this.$emit(EVENT_NAME, value)}
            },
        },
    }
</script>