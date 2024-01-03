<template>
    <typeahead
        :items="choices"
        v-model="choice"
        :placeholder="placeholder"
    ></typeahead>
</template>

<script>
    import Typeahead from './Typeahead.vue'

    export default {
        props: ['search_url','placeholder','value'],
        data() {
            return {
                choice: '',
            }
        },
        components: {
            'typeahead': Typeahead,
        },
        methods: {
            choices(query) {
                if (!query) return;
                return fetch(this.search_url.replace(':query', query)).then(res => {
                    return res.json();
                });
            }
        },
    }
</script>
