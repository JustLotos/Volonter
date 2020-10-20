<template>
    <v-speed-dial v-model="fab" direction="left">
        <template v-slot:activator>
            <v-hover open-delay="0.3s" v-slot:default="{hover}">
                <v-btn v-model="fab" :elevation="hover ? 12 : 0" :color="hover ? 'primary' : 'light'">
                    <v-icon v-if="fab">mdi-close</v-icon>
                    <v-icon v-else>mdi-dots-horizontal</v-icon>
                </v-btn>
            </v-hover>
        </template>
        <slot></slot>
    </v-speed-dial>
</template>

<script lang="ts">
import {Component, Vue} from "vue-property-decorator";

@Component
export default class DialButton extends Vue {
    fab: boolean = false;
}
</script>