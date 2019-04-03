<template>
    <vue-bootstrap-typeahead
        :data="choices"
        v-model="choices_search"
        :placeholder="placeholder"
        @hit="choice_selected($event)"
        @input="handle_change()"
        ref="typeahead"
    ></vue-bootstrap-typeahead>
</template>

<script>
    import _ from 'lodash';

    export default {
        props: ['search_url','placeholder','value'],
        data() {
            return {
                choices:[],
                choices_search: '',
            }
        },
        methods: {
            choice_selected(choice) {
                this.$emit('input', choice);
            },
            handle_change() {
                this.$emit('input', this.choices_search);
            },
            ajaxCall(text) {
                axios.get(this.search_url.replace(':query', text))
                    .then(response => {
                        this.choices = response.data;
                    });
            }
        },
        mounted() {
            this.$refs.typeahead.inputValue = this.value;
        },
        watch: {
            value: function(val) {this.$refs.typeahead.inputValue = val;},
            choices_search: _.debounce(function (c) {this.ajaxCall(c)}, 500)
        }
    }
</script>
