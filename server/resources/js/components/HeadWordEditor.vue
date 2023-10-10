<template>
    <b-modal ref="attach_headword_modal" :title="is_new_headword ? 'Attach Head Word' : 'Edit Head Word'" size="xl"
        hide-footer
    >
        <div v-if="is_new_headword">
            <div class='col-lg-12'>
                <label>Search Head Word</label>
                <input-custom-keyboard placeholder="Search Head Words"
                                       name="head_word_search_input"
                                       id="head_word_search_input"
                                       type="text"
                                       @input="searchHeadwords(modal_attached_headword_search)"
                                       v-model="modal_attached_headword_search"
                                       :custom_keyboard="custom_keyboard"></input-custom-keyboard>
                <br/><br/>
            </div>
            <div id="head_word_search_result">
                <div v-for="h in modal_attached_headword_search_results.headwords">
                    <a href="#" style="cursor:pointer;" @click.prevent="attach_headword(h)">
                        <span style='white-space: nowrap' :lang='h.language.lang_attribute'>{{h.word}}</span>
                        {{ h.definition }}
                    </a>
                </div>
            </div>

            <hr/>
            <h4>Or Add New Head Word</h4>
        </div>

        <div class='row'>
            <div class='col-sm-12'>
                <form>

                <div class='form-group'>
                    <label>Word</label>
                    <input-custom-keyboard placeholder="Word"
                                           name="word"
                                           id="word"
                                           type="text"
                                           v-model="headword.word"
                                           :custom_keyboard="custom_keyboard"></input-custom-keyboard>
                    <div class="alert-danger errors">{{get_error_message_html('word')}}</div>
                </div>

                <div class='form-group'>
                    <label for="definition">Definition</label>
                    <input placeholder="Definition" class="form-control" id="definition" name="definition" type="text"
                        v-model="headword.definition"
                    >
                    <div class="alert-danger errors">{{get_error_message_html('definition')}}</div>
                </div>

                <div class='form-group'>
                    <label for="etyma_id">Etyma</label>
                    <b-form-select id="etyma_id" name="etyma_id"
                                   v-model="headword.etyma_id"
                                   :options="etymas"
                    >
                        <option :value="null">Select an etymon</option>
                    </b-form-select>
                    <div class="alert-danger errors">{{get_error_message_html('etyma_id')}}</div>
                </div>

                <div class='form-group'>
                    <label for="keywords">Keywords</label>
                    <tags-input
                        :add-on-key="[13,',']"
                        :autocomplete-items="autocomplete_items"
                        :tags="format_tags_from_csv(headword.keywords)"
                        v-model="new_tag"
                        @tags-changed="newTags => format_tags_to_csv(newTags)"
                        :separators="[',']"
                    >

                    </tags-input>
                    <div class="alert-warning">Up/down in list to choose an existing tag.  'Enter' or comma after typing to enter a new tag.</div>
                    <div class="alert-danger errors">{{get_error_message_html('keywords')}}</div>
                </div>

                <div class='form-group bottom_button'>
                    <input class="btn btn-xs btn-success" type="button" @click="save()" value="Save">
                </div>
                </form>
            </div>
        </div>
    </b-modal>
</template>

<script>
import InputCustomKeyboard from './InputCustomKeyboard'

    export default {
        components: {
            'input-custom-keyboard': InputCustomKeyboard,
        },
        props: ['headword','custom_keyboard','etymas','language'],
        data: function() { return {
            modal_attached_headword_search: '',
            modal_attached_headword_search_results: [],
            modal_attached_headword_errors: {},
            keyword_choices: [],
            new_tag: '',
        }},
        computed: {
            is_new_headword() {
                return !this.headword.id;
            },
            autocomplete_items() {
                return this.keyword_choices.filter(tag => {
                    return tag.text.toUpperCase().indexOf(this.new_tag.toUpperCase()) !== -1;
                });
            }
        },
        methods: {
            show() {
                axios.get('/admin2/eieol_head_word_keyword/filtered_list?language='+this.language.id)
                    .then((response) => {
                        this.keyword_choices = response.data.map((tag) => {return {'text':tag}});
                    });
                this.$nextTick(function() {
                    this.modal_attached_headword_search = '';
                    this.modal_attached_headword_search_results = [];
                    this.modal_attached_headword_errors = {};
                    this.$refs['attach_headword_modal'].show();
                }, this);
            },
            hide() {
                this.$refs['attach_headword_modal'].hide();
            },
            get_error_message_html(key) {
                if (!this.modal_attached_headword_errors[key]) {
                    return null;
                }
                return this.modal_attached_headword_errors[key].join("<br>");
            },
            format_tags_from_csv() {
                if (!this.headword.keywords) {
                    return [];
                }
                return this.headword.keywords.split(',').map((tag) => {return {'text':tag}});
            },
            format_tags_to_csv(newTags) {
                this.headword.keywords = newTags.map(obj => obj.text.toUpperCase()).join(',');
            },
            searchHeadwords(search_text) {
                let app = this;
                if (search_text.length===0) {
                    return;
                }
                axios.get("/admin2/eieol_head_word/filtered_list?head_word="+search_text+"&language="+this.language.id)
                    .then(function(response) {
                        app.modal_attached_headword_search_results = response.data;
                    });
            },
            attach_headword(headword) {
                this.$emit('input',headword);
            },
            save() {
                $(".spinner").show();
                let url = this.headword.id ? '/admin2/eieol_head_word/'+this.headword.id : '/admin2/eieol_head_word';
                let payload = this.headword.id ? Object.assign(this.headword, {_method:'PUT'}) : this.headword;
                axios.post(url, payload)
                    .then((response) => {
                        $(".spinner").hide();
                        if (response.data.fail) {
                            this.modal_attached_headword_errors = response.data.errors;
                        } else {
                            this.modal_attached_headword_errors = {};
                            this.headword.id = response.data.head_word_id;
                            this.$emit('input',this.headword);
                        }
                    });
            },
        },
        mounted() {
        }
    }
</script>
