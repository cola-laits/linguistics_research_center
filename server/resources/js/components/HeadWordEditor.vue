<!-- FIXME add custom keyboard to search head word, word -->
<template>
    <b-modal ref="attach_headword_modal" :title="is_new_headword ? 'Attach Head Word' : 'Edit Head Word'" size="xl">
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
                    <a href="#" style="cursor:pointer;" @click.prevent="attach_headword(h)" v-html="h.html"></a>
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
                    <div id="word_error" class="alert-danger errors"></div>
                </div>

                <div class='form-group'>
                    <label for="definition">Definition</label>
                    <input placeholder="Definition" class="form-control" id="definition" name="definition" type="text"
                        v-model="headword.definition"
                    >
                    <div id="definition_error" class="alert-danger errors"></div>
                </div>

                <div class='form-group'>
                    <label for="etyma_id">Etyma</label>
                    <b-form-select id="etyma_id" name="etyma_id"
                                   v-model="headword.etyma_id"
                                   :options="etymas"
                    >
                        <option :value="null">Select an etymon</option>
                    </b-form-select>
                    <div id="etyma_id_error" class="alert-danger errors"></div>
                </div>

                <div class='form-group'>
                    <label for="keywords">Keywords</label>
                    <input class="form-control keywords" id="keywords" name="keywords" type="text">
                    <div class="alert-warning">Separate with commas</div>
                    <div id="keywords_error" class="alert-danger errors"></div>
                </div>

                <div class='form-group bottom_button'>
                    <input class="btn btn-xs btn-success" type="submit" value="Save">
                </div>
                </form>
            </div>
        </div>

        <div slot="modal-footer"><!-- no ok or cancel buttons --></div>
    </b-modal>
</template>

<script>
    export default {
        props: ['headword','custom_keyboard','etymas','language'],
        data: function() { return {
            modal_attached_headword_search: '',
            modal_attached_headword_search_results: [],
            modal_attached_headword_errors: {},
        }},
        computed: {
            is_new_headword() {
                return !this.headword.id;
            }
        },
        methods: {
            show() {
                Vue.nextTick(function() {
                    this.modal_attached_headword_search = '';
                    this.modal_attached_headword_search_results = [];
                    this.modal_attached_headword_errors = {};
                    this.$refs['attach_headword_modal'].show();
                }, this);
            },
            hide() {
                this.$refs['attach_headword_modal'].hide();
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
            new_headword_form_submit() {
                /*
                let app = this;
                this.modal_attached_gloss_errors = {};
                let update_promise = null;
                if (this.is_new_gloss) {
                    update_promise = axios.post('/admin2/eieol_gloss', this.gloss)
                } else {
                    update_promise = axios.put('/admin2/eieol_gloss/'+this.gloss.id, this.gloss)
                }
                update_promise
                    .then(function(response) {
                        let json = response.data;
                        if (json['fail']) {
                            app.modal_attached_gloss_errors = json['errors'];
                        }

                        if (json['success']) {
                            app.$emit('saved', json['glossed_text'].glosses, this.gloss.glossed_text_id);
                        }
                    });
                 */ alert("FIXME");
            },
        },
        mounted() {
        }
    }
</script>

