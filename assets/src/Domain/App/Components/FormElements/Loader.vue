<template>
    <v-row justify="center">
        <v-col cols="1">
            <v-progress-circular :size="70" :width="7" indeterminate color="#E3F2FD" ></v-progress-circular>
        </v-col>
    </v-row>
</template>

<script>
    export default { name: "Loader"}
</script>