<template>
    <div id="related_languages" class='row'>

        <div class='form-group col-sm-4'>

            <h2>Related Languages</h2>

            <basic-select
                :options="dropdown_options"
                :selected-option="dropdown_selected"
                placeholder="choose language"
                @select="selectLanguage">
            </basic-select>

            <br/>

            <button @click.prevent="addLanguage()"
                    :disabled="dropdown_selected.value == ''"
                    class="btn btn-xs btn-primary">Attach</button>

        </div>

        <div class='col-sm-4'>

            <ul>

                <li v-for="language in languages">
                    {{ language['text'] }}
                    &nbsp;<a @click.prevent="removeLanguage(language)" href="#">remove</a>
                </li>

            </ul>

        </div>

    </div>
</template>

<script>
    export default {
        props: ['series_id'],
        data() {
            return {
            languages:[],
            dropdown_options: [],
            dropdown_selected: {value:'',text:''},
        };
        },

        created() {

            this.fetchlanguages();
            this.fetchdropdownOptions();

        },

        methods: {

            fetchdropdownOptions() {

                const self = this;
                axios.get('/admin2/related_languages/all_languages').then(function(response){

                    self.dropdown_options = response.data

                }).catch(function(error){console.log(error);});

            },

            selectLanguage(item) {

                this.dropdown_selected = item;

            },

            addLanguage() {

                if (this.dropdown_selected.value != '') {

                    var postData = {
                        'id':this.series_id,
                        'lang':this.dropdown_selected.value,
                        'display':this.dropdown_selected.text
                    };

                    const self = this;
                    axios.post('/admin2/related_languages/attach_language', postData).then(function(response){

                        self.dropdown_selected = {value:'',text:''};
                        self.fetchlanguages();

                    }).catch(function(error){console.log(error);});

                }

            },

            removeLanguage(l) {

                const self = this;
                axios.post('/admin2/related_languages/' + this.series_id + '/detach_language/' + l.value).then(function(response){

                    self.fetchlanguages();

                }).catch(function(error){console.log(error);});

            },

            fetchlanguages() {

                const self = this;
                axios.get('/admin2/related_languages/attached_languages/' + this.series_id).then(function(response){

                    self.languages = response.data

                }).catch(function(error){console.log(error);});

            },

        }
    }
</script>
