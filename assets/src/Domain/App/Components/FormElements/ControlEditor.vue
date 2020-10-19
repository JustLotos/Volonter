<template>
   <vue-editor  v-model="value"  :editor-toolbar="customToolbar"  />
</template>

<script>
import { VueEditor } from 'vue2-editor';
const EVENT_NAME = 'ControlEditor'.toLowerCase();
 export default {
     name: "ControlEditor",
     components: { VueEditor: VueEditor},
     props: ['editor', 'error'],
     model: { prop: 'editor', event: EVENT_NAME },
     computed: {
         value: {
             get: function() { return this.editor; },
             set: function(value) { this.$emit(EVENT_NAME, value)}
         }
     },
    mounted() {
       // this.$el.querySelector('#quill-container').classList.add('border-fixed-top');
    },
    methods: {
         // handleFocus: function () {
         //    this.$el.querySelector('.ql-toolbar').classList.add('d-block');
         //    this.$el.querySelector('.ql-toolbar').classList.remove('d-none');
         // },
         // handleBlur: function () {
         //    console.log('blur');
         //    this.$el.querySelector('.ql-toolbar').classList.add('d-none');
         //    this.$el.querySelector('.ql-toolbar').classList.remove('d-block');
         // }
    },
    data: () => ({
       customToolbar:  [
          ["bold", "italic", "underline"],
          [{ list: "ordered" }, { list: "bullet" }],
          ["image", "code-block"]
       ]
    })
 }
</script>

<style scoped>
   .d-none { display: none }
   .border-fixed-top { border-top: 1px solid #ccc !important; }
</style>