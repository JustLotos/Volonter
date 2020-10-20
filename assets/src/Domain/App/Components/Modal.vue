<template>
    <v-dialog v-model="modal" :max-width="950" @click:outside="modalToggle" @keydown="handleKeyDown">
        <v-container>
            <v-layout justify-center align-center style="position: relative">
                <slot></slot>
                <v-btn absolute top right icon dark @click="modalToggle">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-layout>
        </v-container>
    </v-dialog>
</template>

<script>
    const SHORT = 'short';
    const WIDE = 'wide';
    const EVENT_NAME = 'modalChange'.toLowerCase();
    export default {
        name: "Modal",
        props: {
            modal: { type: Boolean, required: true },
            type: { type: String, default: SHORT }
        },
        model: { prop: 'modal', event: EVENT_NAME },
        computed: {
            value: {
                get: function() {
                    return this.modal || this.localModal
                },
                set: function(value) {
                    this.$emit(EVENT_NAME, value)
                }
            },
        },
        methods: {
            modalToggle: function () {
                this.value =  !this.value;
                if(!this.value) {
                  this.$emit('close');
                }
            },
            handleKeyDown: function (event) {
                if(event.code === 'Escape') {
                    this.modalToggle();
                }
            },
            width: function () {
             //   console.log(this.$el.querySelector('.container'));
                return 700+'px';
            },
        },
        data: function () {
            return {
                localModal: false
            }
        }
    }
</script>