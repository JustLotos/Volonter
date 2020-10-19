<template>
    <v-card>
           <v-row>
               <v-card-actions>
                   <v-text-field
                       label="Поиск"
                       full-width
                       type="text"
                       v-model="term"
                       prepend-inner-icon="mdi-magnify"
                   />
               </v-card-actions>
           </v-row>
    </v-card>
</template>

<script>
    const TYPE_TEXT = 'text';
    export default {
        name: "Search",
        props: {
            items: {
                type: Object,
                required: true,
                default: {}
            },
            itemsId: {
                type: Array,
                required: true,
                default: []
            },
        },
        computed: {
            filtered: function () {
                let list = this.itemsId;
                if(this.term) {
                    return list.filter((id) => this.items[id][this.filter]
                        .toLowerCase()
                        .indexOf(this.term.toLowerCase()) >= 0);
                }
                return list;
            },
        },
        data: function () {
            return {
                type: TYPE_TEXT,
                term: ''
            }
        }
    }
</script>

<style scoped>

</style>