<template>
    <v-flex v-if="getItemsId.length > 0 && getItems">
        <v-list class="pa-0 ma-0 transparent">
            <v-list-item v-for="id in getItemsId" :key="id" class="pa-0 mt-3 transparent">
                <slot v-if="getItems[id]" name="item" :item="getItems[id]"></slot>
            </v-list-item>
        </v-list>
    </v-flex>
    <v-flex v-else>
        <v-row justify="center">
            <v-col cols="12"><slot name="empty">Элементов нет</slot></v-col>
        </v-row>
    </v-flex>
</template>
<script lang="ts">
import {Component, Prop, Vue} from "vue-property-decorator";
@Component
export default class ListObjects extends Vue{
    @Prop({required: true }) items;
    @Prop({required: true }) itemsId: Array<number>;

    get getItems() { return this.items }
    get getItemsId(): Array<number> {return this.itemsId || []}
}
</script>