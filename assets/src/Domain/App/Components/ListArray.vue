<template>
    <v-flex>
<!--        <v-text-field-->
<!--            v-if="filter"-->
<!--            v-model="term"-->
<!--            prepend-inner-icon="mdi-magnify"-->
<!--            label="Поиск"-->
<!--        ></v-text-field>-->

        <v-list class="pa0 m-0">
            <v-list-item v-for="item in visibleItems" :key="item.id">
                <slot name="item" :item="item">{{item}}</slot>
            </v-list-item>
        </v-list>

        <v-pagination
                v-if="pagination && !!countPages"
                v-model="currentPage"
                :length="countPages"
                :total-visible="buttonsCount"
                prev-icon="mdi-menu-left"
                next-icon="mdi-menu-right"
        ></v-pagination>
    </v-flex>
</template>

<script>
    export default {
        name: "ListArray",
        props: [
            'items',
            'filter',
            'pagination'
        ],
        computed: {
            // filtered: function () {
            //     let list = this.items;
            //     if(this.term) {
            //         return list.filter((item) => item[this.filter]
            //             .toLowerCase()
            //             .indexOf(this.term.toLowerCase()) >= 0);
            //     }
            //     return list;
            // },
            visibleItems () {
                if(this.items && this.items.length > 0) {
                    console.log(this.items.slice((this.currentPage - 1)* this.perPage, this.currentPage* this.perPage));
                    return this.items.slice((this.currentPage - 1)* this.perPage, this.currentPage* this.perPage);
                }
                return [];
            },
            countPages: function () {
                if(this.items && this.items.length > this.perPage) {
                    return Math.ceil(this.items.length/this.perPage)
                } else {
                    return 0;
                }
            }
        },
        data: function () {
            return {
                term: '',
                currentPage: 1,
                perPage: 10,
                buttonsCount: 9
            }
        }
    }
</script>

<style scoped>

</style>