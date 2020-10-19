<template>
    <v-pagination
        v-model="currentPage"
        :length="countPages"
        :total-visible="countButtonsPagination"
        prev-icon="mdi-menu-left"
        next-icon="mdi-menu-right"
    ></v-pagination>
    <slot v-bind:page="page"></slot>
</template>

<script lang="ts">
import {Component, Prop, Vue} from "vue-property-decorator";

@Component
export default class Pagination extends Vue{
    currentPage: number = 1;

    @Prop({default: 10})
    elementsOnPage;

    @Prop({default: 7})
    countButtonsPagination;

    @Prop({required: true, type: Array})
    itemsId;

    get page () {
        if(this.itemsId && this.itemsId.length > 0) {
            return this.itemsId.slice((this.currentPage - 1) * this.elementsOnPage, this.currentPage * this.elementsOnPage);
        }
    }

    get countPages () {
        if(this.itemsId && this.itemsId.length > this.elementsOnPage) {
            return Math.ceil(this.itemsId.length/this.elementsOnPage);
        }
        return 0;
    }
}
</script>