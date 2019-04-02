<template>
    <b-modal ref="attach_gloss_modal" :title="is_new_gloss ? 'Attach Gloss' : 'Edit Gloss'" size="xxl">
        <div v-if="is_new_gloss">
            <div class='col-lg-12'>
                <label for="gloss_search_input">Search Gloss</label>
                <input placeholder="Search Gloss" class="form-control custom-keyboard"
                       @keyup="searchGlosses(modal_attached_gloss_search)" name="gloss_search_input" type="text"
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
                    <label for="surface_form">Surface Form</label>
                    <input placeholder="Surface Form" class="form-control custom-keyboard" id="surface_form" name="surface_form" type="text"
                           v-model="gloss.surface_form">
                    <div id ="surface_form_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['surface_form']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-2'>
                    <label for="element_1_part_of_speech">Part Of Speech</label>
                    <input placeholder="Part Of Speech" class="form-control part_of_speech" name="element_1_part_of_speech" type="text" id="element_1_part_of_speech"
                           v-model="gloss.element_1_part_of_speech">
                    <div id ="element_1_part_of_speech_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_1_part_of_speech']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-3'>
                    <label for="element_1_analysis">Analysis</label>
                    <textarea class="form-control analysis" name="element_1_analysis" cols="10" rows="2" id="element_1_analysis"
                              v-model="gloss.element_1_analysis"></textarea>
                    <div id ="element_1_analysis_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_1_analysis']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-2'>
                    <label for="element_1_head_word_id">Head Word</label>
                    <input id="element_1_head_word_id" name="element_1_head_word_id" type="hidden">
                    <div id="element_1_head_word_display"></div>
                    <button class="btn btn-primary btn-sm pick_head_word_button"
                            type="button">Pick Head Word
                    </button>
                    <button class="btn btn-primary btn-sm edit_head_word_button"
                            type="button">Edit Head Word
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
                    <label for="element_2_part_of_speech">Part Of Speech</label>
                    <input placeholder="Part Of Speech" class="form-control part_of_speech"
                           name="element_2_part_of_speech" type="text" id="element_2_part_of_speech"
                           v-model="gloss.element_2_part_of_speech">
                    <div id="element_2_part_of_speech_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_2_part_of_speech']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-3'>
                    <label for="element_2_analysis">Analysis</label>
                    <textarea class="form-control analysis" name="element_2_analysis" cols="10" rows="2"
                              id="element_2_analysis"
                              v-model="gloss.element_2_analysis"></textarea>
                    <div id="element_2_analysis_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_2_analysis']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-2'>
                    <label for="element_2_head_word_id">Head Word</label>
                    <input id="element_2_head_word_id" name="element_2_head_word_id" type="hidden">
                    <div id="element_2_head_word_display"></div>
                    <button class="btn btn-primary btn-sm pick_head_word_button"
                            type="button">Pick Head Word
                    </button>
                    <button class="btn btn-primary btn-sm edit_head_word_button"
                            type="button">Edit Head Word
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
                    <label for="element_3_part_of_speech">Part Of Speech</label>
                    <input placeholder="Part Of Speech" class="form-control part_of_speech"
                           name="element_3_part_of_speech" type="text" id="element_3_part_of_speech"
                           v-model="gloss.element_3_part_of_speech">
                    <div id="element_3_part_of_speech_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_3_part_of_speech']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-3'>
                    <label for="element_3_analysis">Analysis</label>
                    <textarea class="form-control analysis" name="element_3_analysis" cols="10" rows="2"
                              id="element_3_analysis"
                              v-model="gloss.element_3_analysis"></textarea>
                    <div id="element_3_analysis_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_3_analysis']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-2'>
                    <label for="element_3_head_word_id">Head Word</label>
                    <input id="element_3_head_word_id" name="element_3_head_word_id" type="hidden">
                    <div id="element_3_head_word_display"></div>
                    <button class="btn btn-primary btn-sm pick_head_word_button"
                            type="button">Pick Head Word
                    </button>
                    <button class="btn btn-primary btn-sm edit_head_word_button"
                            type="button">Edit Head Word
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
                    <label for="element_4_part_of_speech">Part Of Speech</label>
                    <input placeholder="Part Of Speech" class="form-control part_of_speech"
                           name="element_4_part_of_speech" type="text" id="element_4_part_of_speech"
                           v-model="gloss.element_4_part_of_speech">
                    <div id="element_4_part_of_speech_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_4_part_of_speech']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-3'>
                    <label for="element_4_analysis">Analysis</label>
                    <textarea class="form-control analysis" name="element_4_analysis" cols="10" rows="2"
                              id="element_4_analysis"
                              v-model="gloss.element_4_analysis"></textarea>
                    <div id="element_4_analysis_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_4_analysis']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-2'>
                    <label for="element_4_head_word_id">Head Word</label>
                    <input id="element_4_head_word_id" name="element_4_head_word_id" type="hidden">
                    <div id="element_4_head_word_display"></div>
                    <button class="btn btn-primary btn-sm pick_head_word_button"
                            type="button">Pick Head Word
                    </button>
                    <button class="btn btn-primary btn-sm edit_head_word_button"
                            type="button">Edit Head Word
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
                    <label for="element_5_part_of_speech">Part Of Speech</label>
                    <input placeholder="Part Of Speech" class="form-control part_of_speech"
                           name="element_5_part_of_speech" type="text" id="element_5_part_of_speech"
                           v-model="gloss.element_5_part_of_speech">
                    <div id="element_5_part_of_speech_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_5_part_of_speech']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-3'>
                    <label for="element_5_analysis">Analysis</label>
                    <textarea class="form-control analysis" name="element_5_analysis" cols="10" rows="2"
                              id="element_5_analysis"
                              v-model="gloss.element_5_analysis"></textarea>
                    <div id="element_5_analysis_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_5_analysis']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-2'>
                    <label for="element_5_head_word_id">Head Word</label>
                    <input id="element_5_head_word_id" name="element_5_head_word_id" type="hidden">
                    <div id="element_5_head_word_display"></div>
                    <button class="btn btn-primary btn-sm pick_head_word_button"
                            type="button">Pick Head Word
                    </button>
                    <button class="btn btn-primary btn-sm edit_head_word_button"
                            type="button">Edit Head Word
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
                    <label for="element_6_part_of_speech">Part Of Speech</label>
                    <input placeholder="Part Of Speech" class="form-control part_of_speech"
                           name="element_6_part_of_speech" type="text" id="element_6_part_of_speech"
                           v-model="gloss.element_6_part_of_speech">
                    <div id="element_6_part_of_speech_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_6_part_of_speech']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-3'>
                    <label for="element_6_analysis">Analysis</label>
                    <textarea class="form-control analysis" name="element_6_analysis" cols="10" rows="2"
                              id="element_6_analysis"
                              v-model="gloss.element_6_analysis"></textarea>
                    <div id="element_6_analysis_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_6_analysis']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-2'>
                    <label for="element_6_head_word_id">Head Word</label>
                    <input id="element_6_head_word_id" name="element_6_head_word_id" type="hidden">
                    <div id="element_6_head_word_display"></div>
                    <button class="btn btn-primary btn-sm pick_head_word_button"
                            type="button">Pick Head Word
                    </button>
                    <button class="btn btn-primary btn-sm edit_head_word_button"
                            type="button">Edit Head Word
                    </button>
                    <div id="element_6_head_word_id_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_6_head_word_id']">{{error}}</div></div>
                </div>
            </div>

            <div class='row'>
                <div class='form-group col-sm-12'>
                    <label for="comments">Comments</label>
                    <input placeholder="Comments" class="form-control" id="comments" name="comments"
                           type="text"
                           v-model="gloss.comments">
                    <div id="comments_gloss_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['comments']">{{error}}</div></div>
                </div>
            </div>

            <div class='row'>
                <div class='form-group col-sm-12'>
                    <label for="underlying_form">Underlying Form</label>
                    <input placeholder="Underlying Form" class="form-control" id="underlying_form"
                           name="underlying_form" type="text"
                           v-model="gloss.underlying_form">
                    <div id="underlying_form_gloss_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['underlying_form']">{{error}}</div></div>
                </div>
            </div>

        </form>

        <div slot="modal-footer"><!-- no ok or cancel buttons --></div>
    </b-modal>
</template>

<script>
    export default {
        props: ['gloss'],
        data: function() { return {
            modal_attached_gloss_search: '',
            modal_attached_gloss_search_results: [],
            modal_attached_gloss_elements_open: [],
            modal_attached_gloss_errors: {},
        }},
        computed: {
            is_new_gloss() {
                return !this.gloss.id;
            }
        },
        methods: {
            show() {
                this.$refs['attach_gloss_modal'].show();
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
                axios.post('/admin2/eieol_gloss', this.gloss)
                    .then(function(response) {
                        let json = response.data;
                        if (json['fail']) {
                            app.modal_attached_gloss_errors = json['errors'];
                        }

                        if (json['success']) {
                            app.$emit('saved', json['glossed_text'].glosses, this.gloss.glossed_text_id);
                        }
                    });
            },
        },
        mounted() {
        }
    }
</script>

