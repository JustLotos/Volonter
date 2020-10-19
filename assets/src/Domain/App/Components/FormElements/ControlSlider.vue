<template>
    <div v-if="ticks">
        <v-btn light @click="toggle" elevation="0" block >
            <slot name="label" :value="value"></slot>
        </v-btn>
        <v-slider class="pa-3" color="primary" persistent-hint
            v-model="value"
            v-show="show"
            :hint="hint"
            :min="min || 1"
            :max="max || 100"
            :step="ticks.step"
            :ticks="ticks.name"
            :tick-labels="ticks.labels"
            :tick-size="ticks.size"
        >
            <template v-slot:prepend><v-icon @click="decrement">mdi-minus</v-icon></template>
            <template v-slot:append><v-icon @click="increment">mdi-plus</v-icon></template>
        </v-slider>
    </div>
    <div v-else>
        <v-btn light @click="toggle" elevation="0" block >
            <slot name="label" :value="value"></slot>
        </v-btn>
        <v-slider class="pa-3" color="primary" thumb-label="always" :thumb-size="20" persistent-hint
              v-model="value"
              v-show="show"
              :hint="hint"
              :min="min || 1"
              :max="max || 100">
            <template v-slot:prepend><v-icon @click="decrement">mdi-minus</v-icon></template>
            <template v-slot:append><v-icon @click="increment">mdi-plus</v-icon></template>
        </v-slider>
    </div>

</template>

<script>
    const EVENT_NAME = 'sliderChange'.toLowerCase();
    export default {
        name: "ControlSlider",
        props: [ 'slider', 'hint', 'min', 'max', 'errorMessage', 'ticks'],
        model: { prop: 'slider', event: EVENT_NAME },
        computed: {
            value: {
                get: function() { return this.defaultSlider || 1 } ,
                set: function(value) {
                    this.defaultSlider = value;
                    return this.$emit(EVENT_NAME, value);
                }
            },
        },
        data: function () { return {
            show: false,
            defaultSlider: this.slider || 1
        }},
        methods: {
            toggle:     function() { this.show = !this.show },
            increment:  function() { this.value++ },
            decrement:  function() { this.value-- }
        }
    }
</script>