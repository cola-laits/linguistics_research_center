<template>
    <div>
    <b-modal ref="attach_gloss_modal" :title="is_new_gloss ? 'Attach Gloss' : 'Edit Gloss'" size="xxl">
        <div v-if="is_new_gloss">
            <div class='col-lg-12'>
                <label for="gloss_search_input">Search Gloss</label>
                <input placeholder="Search Gloss" class="form-control custom-keyboard"
                       @keyup="searchGlosses(modal_attached_gloss_search)"
                       name="gloss_search_input" type="text"
                       id="gloss_search_input"
                       v-model="modal_attached_gloss_search">
                <br/><br/>
            </div>
            <div id="gloss_search_result">
                <div v-for="g in modal_attached_gloss_search_results.glosses">
                    <a href="#" style="cursor:pointer;" @click.prevent="attach_gloss(g.id,gloss.glossed_text_id)" v-html="g.html"></a>
                </div>
            </div>

            <hr/>
            <h4>Or Add New Gloss</h4>
        </div>

        <form method="POST"
              action="/admin2/eieol_gloss"
              class="form">

            <div class='row'>
                <div class='form-group col-sm-2'>
                    <label>Surface Form</label>
                    <input-custom-keyboard placeholder="Surface Form"
                                           id="surface_form"
                                           name="surface_form"
                                           type="text"
                                           v-model="gloss.surface_form"
                                           :custom_keyboard="custom_keyboard"></input-custom-keyboard>
                    <div id ="surface_form_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['surface_form']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-2'>
                    <label>Part Of Speech</label>
                    <input-typeahead-ajax
                        placeholder="Part Of Speech"
                        v-model="gloss.element_1_part_of_speech"
                        :search_url="'/admin2/part_of_speech/filtered_list?language_id='+language.id+'&term=:query'"
                    ></input-typeahead-ajax>
                    <div id ="element_1_part_of_speech_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_1_part_of_speech']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-3'>
                    <label>Analysis</label>
                    <input-typeahead-ajax
                        placeholder="Analysis"
                        v-model="gloss.element_1_analysis"
                        :search_url="'/admin2/eieol_analysis/filtered_list?language_id='+language.id+'&term=:query'"
                    ></input-typeahead-ajax>
                    <div id ="element_1_analysis_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_1_analysis']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-2'>
                    <label>Head Word</label><br>
                    <div id="element_1_head_word_display" v-if="gloss.elements && gloss.elements[0]">
                        <span style='white-space: nowrap' :lang='lesson_lang_attribute'>{{gloss.elements[0].head_word.word}}</span>
                        {{gloss.elements[0].head_word.definition}}
                    </div>
                    <button class="btn btn-primary btn-sm pick_head_word_button"
                            type="button" @click="pick_head_word(0)">Pick Head Word
                    </button>
                    <button class="btn btn-primary btn-sm edit_head_word_button"
                            type="button" @click="edit_head_word(0)">Edit Head Word
                    </button>
                    <div id ="element_1_head_word_id_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_1_head_word_id']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-2'>
                    <label for="contextual_gloss">Contextual Gloss</label>
                    <input placeholder="Contextual Gloss" class="form-control" id="contextual_gloss" name="contextual_gloss" type="text"
                           v-model="gloss.contextual_gloss">
                    <div id ="contextual_gloss_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['contextual_gloss']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-1 bottom_button'>
                    <comment-icon :author_comment="gloss.author_comments"
                                  :admin_comment="gloss.admin_comments"
                                  :author_done="gloss.author_done"
                                  @click="comments_are_open = !comments_are_open"></comment-icon>
                    <br>
                    <input class="btn btn-sm btn-success" type="button" value="Save"
                           @click="new_gloss_form_submit()">
                </div>
            </div>

            <div class="row" v-if="!isAttachGlossElementOpen(2)">
                <div class="col-sm-2">
                    <a class="show_element" href="#" @click.prevent="toggleAttachGlossElementOpen(2)"><i class='fa fa-plus-square-o '></i></a>
                </div>
            </div>

            <div class='row' v-if="isAttachGlossElementOpen(2)">
                <div class='form-group col-sm-2'></div>

                <div class='form-group col-sm-2'>
                    <label>Part Of Speech</label>
                    <input-typeahead-ajax
                        placeholder="Part Of Speech"
                        v-model="gloss.element_2_part_of_speech"
                        :search_url="'/admin2/part_of_speech/filtered_list?language_id='+language.id+'&term=:query'"
                    ></input-typeahead-ajax>
                    <div id="element_2_part_of_speech_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_2_part_of_speech']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-3'>
                    <label>Analysis</label>
                    <input-typeahead-ajax
                        placeholder="Analysis"
                        v-model="gloss.element_2_analysis"
                        :search_url="'/admin2/eieol_analysis/filtered_list?language_id='+language.id+'&term=:query'"
                    ></input-typeahead-ajax>
                    <div id="element_2_analysis_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_2_analysis']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-2'>
                    <label>Head Word</label><br>
                    <div id="element_2_head_word_display" v-if="gloss.elements && gloss.elements[1]">
                        <span style='white-space: nowrap' :lang='lesson_lang_attribute'>{{gloss.elements[1].head_word.word}}</span>
                        {{gloss.elements[1].head_word.definition}}
                    </div>
                    <button class="btn btn-primary btn-sm pick_head_word_button"
                            type="button" @click="pick_head_word(1)">Pick Head Word
                    </button>
                    <button class="btn btn-primary btn-sm edit_head_word_button"
                            type="button" @click="edit_head_word(1)">Edit Head Word
                    </button>
                    <div id="element_2_head_word_id_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_2_head_word_id']">{{error}}</div></div>
                </div>
            </div>

            <div class="row" v-if="!isAttachGlossElementOpen(3)">
                <div class="col-sm-2">
                    <a class="show_element" href="#" @click.prevent="toggleAttachGlossElementOpen(3)"><i class='fa fa-plus-square-o '></i></a>
                </div>
            </div>

            <div class='row' v-if="isAttachGlossElementOpen(3)">
                <div class='form-group col-sm-2'></div>

                <div class='form-group col-sm-2'>
                    <label>Part Of Speech</label>
                    <input-typeahead-ajax
                        placeholder="Part Of Speech"
                        v-model="gloss.element_3_part_of_speech"
                        :search_url="'/admin2/part_of_speech/filtered_list?language_id='+language.id+'&term=:query'"
                    ></input-typeahead-ajax>
                    <div id="element_3_part_of_speech_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_3_part_of_speech']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-3'>
                    <label>Analysis</label>
                    <input-typeahead-ajax
                        placeholder="Analysis"
                        v-model="gloss.element_3_analysis"
                        :search_url="'/admin2/eieol_analysis/filtered_list?language_id='+language.id+'&term=:query'"
                    ></input-typeahead-ajax>
                    <div id="element_3_analysis_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_3_analysis']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-2'>
                    <label>Head Word</label><br>
                    <div id="element_3_head_word_display" v-if="gloss.elements && gloss.elements[2]">
                        <span style='white-space: nowrap' :lang='lesson_lang_attribute'>{{gloss.elements[2].head_word.word}}</span>
                        {{gloss.elements[2].head_word.definition}}
                    </div>
                    <button class="btn btn-primary btn-sm pick_head_word_button"
                            type="button" @click="pick_head_word(2)">Pick Head Word
                    </button>
                    <button class="btn btn-primary btn-sm edit_head_word_button"
                            type="button" @click="edit_head_word(2)">Edit Head Word
                    </button>
                    <div id="element_3_head_word_id_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_3_head_word_id']">{{error}}</div></div>
                </div>
            </div>

            <div class="row" v-if="!isAttachGlossElementOpen(4)">
                <div class="col-sm-2">
                    <a class="show_element" href="#" @click.prevent="toggleAttachGlossElementOpen(4)"><i class='fa fa-plus-square-o '></i></a>
                </div>
            </div>

            <div class='row' v-if="isAttachGlossElementOpen(4)">
                <div class='form-group col-sm-2'></div>

                <div class='form-group col-sm-2'>
                    <label>Part Of Speech</label>
                    <input-typeahead-ajax
                        placeholder="Part Of Speech"
                        v-model="gloss.element_4_part_of_speech"
                        :search_url="'/admin2/part_of_speech/filtered_list?language_id='+language.id+'&term=:query'"
                    ></input-typeahead-ajax>
                    <div id="element_4_part_of_speech_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_4_part_of_speech']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-3'>
                    <label>Analysis</label>
                    <input-typeahead-ajax
                        placeholder="Analysis"
                        v-model="gloss.element_4_analysis"
                        :search_url="'/admin2/eieol_analysis/filtered_list?language_id='+language.id+'&term=:query'"
                    ></input-typeahead-ajax>
                    <div id="element_4_analysis_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_4_analysis']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-2'>
                    <label>Head Word</label><br>
                    <div id="element_4_head_word_display" v-if="gloss.elements && gloss.elements[3]">
                        <span style='white-space: nowrap' :lang='lesson_lang_attribute'>{{gloss.elements[3].head_word.word}}</span>
                        {{gloss.elements[3].head_word.definition}}
                    </div>
                    <button class="btn btn-primary btn-sm pick_head_word_button"
                            type="button" @click="pick_head_word(3)">Pick Head Word
                    </button>
                    <button class="btn btn-primary btn-sm edit_head_word_button"
                            type="button" @click="edit_head_word(3)">Edit Head Word
                    </button>
                    <div id="element_4_head_word_id_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_4_head_word_id']">{{error}}</div></div>
                </div>
            </div>

            <div class="row" v-if="!isAttachGlossElementOpen(5)">
                <div class="col-sm-2">
                    <a class="show_element" href="#" @click.prevent="toggleAttachGlossElementOpen(5)"><i class='fa fa-plus-square-o '></i></a>
                </div>
            </div>

            <div class='row' v-if="isAttachGlossElementOpen(5)">
                <div class='form-group col-sm-2'></div>

                <div class='form-group col-sm-2'>
                    <label>Part Of Speech</label>
                    <input-typeahead-ajax
                        placeholder="Part Of Speech"
                        v-model="gloss.element_5_part_of_speech"
                        :search_url="'/admin2/part_of_speech/filtered_list?language_id='+language.id+'&term=:query'"
                    ></input-typeahead-ajax>
                    <div id="element_5_part_of_speech_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_5_part_of_speech']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-3'>
                    <label>Analysis</label>
                    <input-typeahead-ajax
                        placeholder="Analysis"
                        v-model="gloss.element_5_analysis"
                        :search_url="'/admin2/eieol_analysis/filtered_list?language_id='+language.id+'&term=:query'"
                    ></input-typeahead-ajax>
                    <div id="element_5_analysis_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_5_analysis']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-2'>
                    <label>Head Word</label><br>
                    <div id="element_5_head_word_display" v-if="gloss.elements && gloss.elements[4]">
                        <span style='white-space: nowrap' :lang='lesson_lang_attribute'>{{gloss.elements[4].head_word.word}}</span>
                        {{gloss.elements[4].head_word.definition}}
                    </div>
                    <button class="btn btn-primary btn-sm pick_head_word_button"
                            type="button" @click="pick_head_word(4)">Pick Head Word
                    </button>
                    <button class="btn btn-primary btn-sm edit_head_word_button"
                            type="button" @click="edit_head_word(4)">Edit Head Word
                    </button>
                    <div id="element_5_head_word_id_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_5_head_word_id']">{{error}}</div></div>
                </div>
            </div>

            <div class="row" v-if="!isAttachGlossElementOpen(6)">
                <div class="col-sm-2">
                    <a class="show_element" href="#" @click.prevent="toggleAttachGlossElementOpen(6)"><i class='fa fa-plus-square-o '></i></a>
                </div>
            </div>

            <div class='row' v-if="isAttachGlossElementOpen(6)">
                <div class='form-group col-sm-2'></div>

                <div class='form-group col-sm-2'>
                    <label>Part Of Speech</label>
                    <input-typeahead-ajax
                        placeholder="Part Of Speech"
                        v-model="gloss.element_6_part_of_speech"
                        :search_url="'/admin2/part_of_speech/filtered_list?language_id='+language.id+'&term=:query'"
                    ></input-typeahead-ajax>
                    <div id="element_6_part_of_speech_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_6_part_of_speech']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-3'>
                    <label>Analysis</label>
                    <input-typeahead-ajax
                        placeholder="Analysis"
                        v-model="gloss.element_6_analysis"
                        :search_url="'/admin2/eieol_analysis/filtered_list?language_id='+language.id+'&term=:query'"
                    ></input-typeahead-ajax>
                    <div id="element_6_analysis_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_6_analysis']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-2'>
                    <label>Head Word</label><br>
                    <div id="element_6_head_word_display" v-if="gloss.elements && gloss.elements[5]">
                        <span style='white-space: nowrap' :lang='lesson_lang_attribute'>{{gloss.elements[5].head_word.word}}</span>
                        {{gloss.elements[5].head_word.definition}}
                    </div>
                    <button class="btn btn-primary btn-sm pick_head_word_button"
                            type="button" @click="pick_head_word(5)">Pick Head Word
                    </button>
                    <button class="btn btn-primary btn-sm edit_head_word_button"
                            type="button" @click="edit_head_word(5)">Edit Head Word
                    </button>
                    <div id="element_6_head_word_id_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_6_head_word_id']">{{error}}</div></div>
                </div>
            </div>

            <div class='row'>
                <div class='form-group col-sm-12'>
                    <label for="comments">Comments</label>
                    <input-custom-keyboard placeholder="Comments"
                                           id="comments"
                                           name="comments"
                                           type="text"
                                           v-model="gloss.comments"
                                           :custom_keyboard="custom_keyboard"></input-custom-keyboard>
                    <div id="comments_gloss_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['comments']">{{error}}</div></div>
                </div>
            </div>

            <div class='row'>
                <div class='form-group col-sm-12'>
                    <label for="underlying_form">Underlying Form</label>
                    <input-custom-keyboard placeholder="Underlying Form"
                                           id="underlying_form"
                                           name="underlying_form"
                                           type="text"
                                           v-model="gloss.underlying_form"
                                           :custom_keyboard="custom_keyboard"></input-custom-keyboard>
                    <div id="underlying_form_gloss_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['underlying_form']">{{error}}</div></div>
                </div>
            </div>

            <div class='row'>
                <div class='col-sm-12'>
                    <comment-area v-model="gloss"
                                  :is_user_admin="is_user_admin"
                                  :show_comments_area="comments_are_open"
                    ></comment-area>
                </div>
            </div>

        </form>

        <div slot="modal-footer"><!-- no ok or cancel buttons --></div>
    </b-modal>

        <head-word-editor ref="head-word-editor"
                          :headword="headword_for_edit"
                          @input="headword_selected($event)"
                          :etymas="etymas"
                          :language="language"
                          :custom_keyboard="custom_keyboard"
        ></head-word-editor>
    </div>
</template>

<script>
    export default {
        props: ['gloss',
            'lesson_lang_attribute',
            'language',
            'etymas',
            'is_user_admin',
            'custom_keyboard'
        ],
        data: function() { return {
            modal_attached_gloss_search: '',
            modal_attached_gloss_search_results: [],
            modal_attached_gloss_elements_open: [],
            modal_attached_gloss_errors: {},
            comments_are_open: false,
            headword_for_edit: {},
            element_index_for_headword_edit: 0,
        }},
        computed: {
            is_new_gloss() {
                return !this.gloss.id;
            }
        },
        methods: {
            show() {
                Vue.nextTick(function() {
                    this.modal_attached_gloss_search = '';
                    this.modal_attached_gloss_search_results = [];
                    this.modal_attached_gloss_elements_open = [];
                    this.modal_attached_gloss_errors = {};
                    if (this.gloss.element_2_part_of_speech) {this.modal_attached_gloss_elements_open.push(2)}
                    if (this.gloss.element_3_part_of_speech) {this.modal_attached_gloss_elements_open.push(3)}
                    if (this.gloss.element_4_part_of_speech) {this.modal_attached_gloss_elements_open.push(4)}
                    if (this.gloss.element_5_part_of_speech) {this.modal_attached_gloss_elements_open.push(5)}
                    if (this.gloss.element_6_part_of_speech) {this.modal_attached_gloss_elements_open.push(6)}
                    this.comments_are_open = false;
                    this.$refs['attach_gloss_modal'].show();
                }, this);
            },
            hide() {
                this.$refs['attach_gloss_modal'].hide();
            },
            toggleAttachGlossElementOpen(id) {
                let ix = this.modal_attached_gloss_elements_open.indexOf(id);
                if (ix !== -1) {
                    this.modal_attached_gloss_elements_open.splice(ix, 1);
                } else {
                    this.modal_attached_gloss_elements_open.push(id);
                }
            },
            isAttachGlossElementOpen(id) {
                return this.modal_attached_gloss_elements_open.indexOf(id) !== -1;
            },
            searchGlosses(gloss_text) {
                let app = this;
                if (gloss_text.length===0) {
                    return;
                }
                axios.get("/admin2/eieol_gloss/filtered_list?gloss="+gloss_text+"&language="+this.gloss.language_id)
                    .then(function(response) {
                        app.modal_attached_gloss_search_results = response.data;
                    });
            },
            attach_gloss(gloss_id, glossed_text_id) {
                let app = this;
                axios.post('/admin2/eieol_glossed_text_gloss/copy_gloss', {
                    existing_gloss_id: gloss_id,
                    glossed_text_id: glossed_text_id
                }).then(function(response) {
                    let json = response.data;
                    if(json['fail']) {
                        alert('Ajax Error: ' + json['msg']);
                    }  //json fail

                    if(json['success']) {
                        app.$emit('saved', json['glossed_text'].glosses, glossed_text_id);
                    }
                });
            },
            new_gloss_form_submit() {
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
                            app.$emit('saved', json['glossed_text'].glosses, app.gloss.glossed_text_id);
                        }
                    });
            },
            pick_head_word(ix) {
                this.headword_for_edit = {};
                this.element_index_for_headword_edit = ix;
                this.$refs['head-word-editor'].show();
            },
            edit_head_word(ix) {
                if (this.gloss.elements[ix]) {
                    this.headword_for_edit = this.gloss.elements[ix].head_word;
                } else {
                    this.headword_for_edit = {id:'',language_id:this.language.id};
                }
                this.element_index_for_headword_edit = ix;
                this.$refs['head-word-editor'].show();
            },
            headword_selected(evt) {
                if (!this.gloss.elements) {
                    Vue.set(this.gloss,'elements',[]);
                }
                if (!this.gloss.elements[this.element_index_for_headword_edit]) {
                    this.gloss.elements.splice(this.element_index_for_headword_edit, 1, {});
                }
                Vue.set(this.gloss.elements[this.element_index_for_headword_edit], 'head_word', evt);
                Vue.set(this.gloss.elements[this.element_index_for_headword_edit], 'head_word_id', evt.id);
                if (this.element_index_for_headword_edit===0) {
                    this.gloss.element_1_head_word_id = evt.id;
                }
                if (this.element_index_for_headword_edit===1) {
                    this.gloss.element_2_head_word_id = evt.id;
                }
                if (this.element_index_for_headword_edit===2) {
                    this.gloss.element_3_head_word_id = evt.id;
                }
                if (this.element_index_for_headword_edit===3) {
                    this.gloss.element_4_head_word_id = evt.id;
                }
                if (this.element_index_for_headword_edit===4) {
                    this.gloss.element_5_head_word_id = evt.id;
                }
                if (this.element_index_for_headword_edit===5) {
                    this.gloss.element_6_head_word_id = evt.id;
                }
                this.$refs['head-word-editor'].hide();
            }
        },
        mounted() {
        }
    }
</script>

