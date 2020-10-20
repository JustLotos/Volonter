<template>
    <v-fab-transition>
        <v-tooltip top v-if="getTooltip">
            <template v-slot:activator="{ on }">
                <v-btn v-on="on" @click="handler" color="primary" dark absolute top right fab>
                    <v-icon>mdi-plus</v-icon>
                </v-btn>
            </template>
            <span>{{getTooltip}}</span>
        </v-tooltip>
        <v-btn v-else @click="handler" color="primary" dark absolute top right fab>
            <v-icon>mdi-plus</v-icon>
        </v-btn>
    </v-fab-transition>
</template>

<script>
export default {
    name: "CircleButton",
    props: ['tooltip'],
    computed: {
        getTooltip() { return this.tooltip || false }
    },
    methods: {
        handler: function () { this.$emit('click'); },
    },
}
</script>