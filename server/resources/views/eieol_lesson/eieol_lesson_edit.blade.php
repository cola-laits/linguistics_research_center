@extends('admin_layout')
 
@section('title') Edit Lesson @stop

@section('head_extra')
    <script type="text/javascript">
        window.lesson_language_id = {{$lesson->language_id}};
        @if (Auth::user()->isAdmin())
            window.isAdmin = true;
        @else
            window.isAdmin = false;
        @endif

        window.admin_app_initial_state = {
            'lesson':{!! json_encode($lesson) !!},
            'languages':{!! json_encode($languages) !!},
            'glossed_texts':{!! json_encode($glossed_texts) !!},
            'grammars':{!! json_encode($grammars) !!},
            'ckeditor_customization':{
                language_list :
                [
                    @foreach ($series_languages as $series_language)
                        '{{$series_language}}',
                    @endforeach
                ],
                language_lang : '{{$lesson->language->lang_attribute}}',
                specialChars : [ {!! $lesson->language->custom_keyboard_layout !!}]
            },
            'custom_keyboard_layout': [ {!! $lesson->language->custom_keyboard_layout !!} ],
            'dirty_form_ids': [],
            'open_comment_ids': [],
            'toggled_gloss_ids': [],
            'is_user_admin': {{Auth::user()->isAdmin()}},
            'modal_flash_title': '',
            'modal_flash_body': '',
            'delete_grammar_confirm_grammar_id': '',
            'delete_glossed_text_confirm_glossed_text_id': '',
            'delete_glossed_text_gloss_confirm_glossed_text_gloss_id': '',

            // 'attach gloss' modal
            'modal_attached_gloss': {},
            'modal_attached_gloss_search': '',
            'modal_attached_gloss_search_results': [],
            'modal_attached_gloss_elements_open': [],
            'modal_attached_gloss_errors': {}
        };

        window.admin_app_computed = {
            lesson_text: function() {
                var lesson_text = '';
                this.glossed_texts.forEach(function(gt) {
                    if (gt.glossed_text) {
                        lesson_text += gt.glossed_text.replace('<p>', '').replace('</p>', '') + ' ';
                    }
                });
                return lesson_text;
            },
        };

        window.admin_app_methods = {
            flash_modal(msg) {
                this.modal_flash_title = 'Update Confirmation';
                this.modal_flash_body = msg;
                this.$refs['flash_modal'].show();
                var app = this;
                setTimeout(function() {
                    app.$refs['flash_modal'].hide();
                }, 2000);
            },
            getElementsForDisplay(gloss) {
                return gloss.elements
                    .map(function(el) {
                        return el.part_of_speech + '; ' +
                            el.analysis + ' ' +
                            "<span style='white-space: nowrap' lang='" + gloss.language.lang_attribute + "'> &lt;" +
                            el.head_word.word.substring(1,el.head_word.word.length-1) +
                            "&gt;</span> " + el.head_word.definition;
                    })
                    .join(' + ');
            },
            markFormDirty(id) {
                if (this.dirty_form_ids.indexOf(id) !== -1) {
                    return;
                }
                this.dirty_form_ids.push(id);
            },
            isFormDirty(id) {
                return this.dirty_form_ids.indexOf(id) !== -1;
            },
            // FIXME use https://bootstrap-vue.js.org/docs/components/collapse/ for this
            toggleCommentsOpen(id) {
                var ix = this.open_comment_ids.indexOf(id);
                if (ix !== -1) {
                    this.open_comment_ids.splice(ix, 1);
                } else {
                    this.open_comment_ids.push(id);
                }
            },
            areCommentsOpen(id) {
                return this.open_comment_ids.indexOf(id) !== -1;
            },
            delete_grammar(id) {
                this.delete_grammar_confirm_grammar_id = id;
                this.$refs['delete_grammar_confirm'].show();
            },
            delete_grammar_after_confirmation(id) {
                var app = this;
                axios.post('/admin2/eieol_grammar/'+id, {'_method':'delete'})
                    .then(function(response) {
                        app.grammars = app.grammars.filter(function(grammar) {
                            return grammar.id !== id;
                        });
                        $("#delete_grammar_confirm").modal('hide');

                        app.flash_modal('Grammar has been deleted.');
                    });
            },
            add_grammar() {
                var next_grammar_order = 0;
                this.grammars.forEach(function(grammar) {
                    var order = parseInt(grammar.order);
                    if(order > next_grammar_order) {
                        next_grammar_order = order;
                    }
                });
                next_grammar_order += 10;

                this.grammars.push({
                    id:"",
                    order:next_grammar_order
                });
            },
            delete_glossed_text(id) {
                this.delete_glossed_text_confirm_glossed_text_id = id;
                this.$refs['delete_glossed_text_confirm'].show();
            },
            delete_glossed_text_after_confirmation(id) {
                var app = this;
                axios.post('/admin2/eieol_glossed_text/'+id, {'_method':'delete'})
                    .then(function(response) {
                        app.glossed_texts = app.glossed_texts.filter(function(glossed_text) {
                            return glossed_text.id !== id;
                        });
                        $("#delete_glossed_text_confirm").modal('hide');

                        app.flash_modal('Glossed Text has been deleted.');
                    });
            },
            add_glossed_text() {
                //calculate next order by finding the highest order in the form and adding 10
                var next_glosssed_text_order = 0;
                this.glossed_texts.forEach(function(gt) {
                    var order = parseInt(gt.order);
                    if(order > next_glosssed_text_order) {
                        next_glosssed_text_order = order;
                    }
                });
                next_glosssed_text_order += 10;
                this.glossed_texts.push({
                    id:"",
                    order:next_glosssed_text_order
                });
            },
            delete_glossed_text_gloss(id) {
                this.delete_glossed_text_gloss_confirm_glossed_text_gloss_id = id;
                this.$refs['delete_glossed_text_gloss_confirm'].show();
            },
            delete_glossed_text_gloss_after_confirmation(id) {
                var app = this;
                axios.post('/admin2/eieol_glossed_text_gloss/'+id, {'_method':'delete'})
                    .then(function(response) {
                        app.glossed_texts.forEach(function (glossed_text) {
                            glossed_text.glosses = glossed_text.glosses.filter(function(gloss) {
                                return gloss.id !== id;
                            });
                        });
                        $("#delete_glossed_text_gloss_confirm").modal('hide');

                        app.flash_modal('Gloss has been unattached.');
                    });
            },
            open_attach_gloss_modal(glossed_text_id) {
                this.modal_attached_gloss = {
                    'glossed_text_id': glossed_text_id,
                    'language_id': this.lesson.language_id
                };
                this.modal_attached_gloss_search = '';
                this.modal_attached_gloss_search_results = [];
                this.modal_attached_gloss_elements_open = [];
                this.modal_attached_gloss_errors = {};
                this.$refs['attach_gloss_modal'].show();
            },
            searchGlosses(gloss) {
                var app = this;
                if (gloss.length===0) {
                    return;
                }
                axios.get("/admin2/eieol_gloss/filtered_list?gloss="+gloss+"&language="+app.lesson.language_id)
                    .then(function(response) {
                        app.modal_attached_gloss_search_results = response.data;
                    });
            },
            attach_gloss(gloss_id, glossed_text_id) {
                var app = this;
                axios.post('/admin2/eieol_glossed_text_gloss/copy_gloss', {
                    existing_gloss_id: gloss_id,
                    glossed_text_id: glossed_text_id
                }).then(function(response) {
                    var json = response.data;
                    if(json['fail']) {
                        alert('Ajax Error: ' + json['msg']);
                    }  //json fail

                    if(json['success']) {
                        app.glossed_texts.forEach(function(gt) {
                            if (gt.id===json['glossed_text'].id) {
                                gt.glosses = json['glossed_text'].glosses;
                            }
                        });

                        app.$refs['attach_gloss_modal'].hide();
                        app.flash_modal('Gloss successfully added.');
                    }
                });
            },
            toggleAttachGlossElementOpen(id) {
                var ix = this.modal_attached_gloss_elements_open.indexOf(id);
                if (ix !== -1) {
                    this.modal_attached_gloss_elements_open.splice(ix, 1);
                } else {
                    this.modal_attached_gloss_elements_open.push(id);
                }
            },
            isAttachGlossElementOpen(id) {
                return this.modal_attached_gloss_elements_open.indexOf(id) !== -1;
            },
            new_gloss_form_submit() {
                var app = this;
                this.modal_attached_gloss_errors = {};
                axios.post('/admin2/eieol_gloss', this.modal_attached_gloss)
                    .then(function(response) {
                        var json = response.data;
                        if (json['fail']) {
                            app.modal_attached_gloss_errors = json['errors'];
                        }

                        if (json['success']) {
                            app.glossed_texts.forEach(function(gt) {
                                if (gt.id===json['glossed_text'].id) {
                                    gt.glosses = json['glossed_text'].glosses;
                                }
                            });

                            app.$refs['attach_gloss_modal'].hide();
                            app.flash_modal('Gloss successfully added.');
                        }
                    });
            },
            open_edit_gloss_modal(gloss_id) {
                //load form with data for the record they want to edit
                $.ajax({
                    type: "GET",
                    url: "/admin2/eieol_gloss/" + gloss_id,
                    data: null,
                    dataType: "json",

                    success: function (data) {
                        //clear old values out
                        $('#edit_gloss_form')[0].reset();
                        //for some reason the reset doesn't reset all the fields
                        for (i = 1; i <= 6; i++) {
                            $('#element_' + i + '_head_word_id', '#edit_gloss_form').val('');
                        }

                        //clear comment divs out
                        $("#gloss_author_comments").html('');
                        $("#gloss_admin_comments").html('');

                        if (!window.isAdmin || data['author_comments'] || data['author_done']) {
                            //only show if you are not an admin, or if they were filled in.
                            $("#gloss_author_comments").html('<div class="form-group col-sm-9 offset-1">\
						    <label for="author_comments">Author Comments</label>\
						    <textarea class="form-control comment_textarea author_comments" name="author_comments" cols="100" rows="2" id="author_comments"></textarea>\
						</div>\
						<div class="form-group col-sm-1">\
						    <label for="author_done">Done</label>\
						    \<input class="form-control author_done" id="gloss_author_done" \
						        name="author_done" type="checkbox" value="1"\
						        checked=' + (data.author_done ? 'checked' : '') + '>\
						</div>');
                        }

                        if (window.isAdmin) {
                            $("#gloss_admin_comments").html('<div class="form-group col-sm-9 offset-1">\
							    <label for="admin_comment">Admin Comments</label>\
					    		<textarea class="form-control comment_textarea admin_comments" name="admin_comments" cols="100" rows="2"></textarea>\
							</div>\
							<div class="form-group col-sm-1">\
						        <input class="btn btn-sm btn-warning comment_clear" type="submit" value="Clear">\
							</div>');
                        } else {
                            if (data['admin_comments']) {
                                //Only show admin comments to authors if they exist
                                $("#gloss_admin_comments").html('<div class="form-group col-sm-9 offset-1">\
								<label for="admin_comment">Admin Comments</label>\
								<input class="form-control" name="admin_comments" type="hidden">\
								<div class="card"><div class="card-body" style="white-space: pre-wrap" >' + data['admin_comments'] + '</div></div>\
							</div>');
                            }
                        }

                        //load form
                        $.each(data, function (key, value) {
                            if (key === 'author_done') { //checkboxes behave differently
                                if (value == 1) {
                                    $("#gloss_author_done").prop('checked', true);
                                }
                            } else {
                                $('[name=' + key + ']', '#edit_gloss_form').val(value);
                            }
                        });

                        for (i = 1; i <= 6; i++) {
                            $('#element_' + i + '_head_word_display', '#edit_gloss_form').text(''); //we only get ones that already exist, so reset it first
                            $('#element_' + i + '_head_word_display', '#edit_gloss_form').html(data['element_' + i + '_head_word_display']);
                        }

                        for (i = 2; i <= 6; i++) {
                            if (data.hasOwnProperty('element_' + i + '_id')) {
                                $('#element_' + i).show();
                            } else {
                                $('#element_' + i).hide();
                            }
                        }

                        $("#gloss_lessons").html("<strong>This is used by the following lessons:</strong> " + data['lessons']);
                        $("#edit_gloss_form").attr("action", "/admin2/eieol_gloss/" + data['id']);
                        $(".errors", "#edit_gloss_form").empty(); //reset gloss form error divs
                        $("#edit_gloss_modal").modal("show");
                        $('#edit_gloss_form').removeAttr("dirty");
                        $("#surface_form", "#edit_gloss_form").focus(); //put cursor in first field

                        $("#gloss_comments").hide(); //close comments box in case they left it open on previous editing

                    }, //success

                    error: function (xml_http_request, text_status, error_thrown) {
                        alert('Ajax Error: ' + text_status + '/ ' + xml_http_request + '/ ' + error_thrown);
                    } //error

                }); //ajax call
            }
        };
    </script>
@endsection

@section('foot_extra')
    <script src="/js/lesson_edit.js"></script>
@endsection

@section('content')

<div class="spinner">
    <img src="/images/ajax_loader_red_350.gif" alt="Loading" width="150" height="150" style="border:0;">
    <br/>Please Wait...
</div>

@include('eieol_lesson.modal_attach_gloss')
@include('eieol_lesson.modal_edit_gloss')
@include('eieol_lesson.modal_attach_head_word')
@include('eieol_lesson.modal_edit_head_word')

@verbatim
<!-- ---------------------------------------------------------------------------------------- -->

<b-modal ref="flash_modal" :title="modal_flash_title" :ok-only="true" size="md"><div v-html="modal_flash_body"></div></b-modal>

<b-modal ref="delete_grammar_confirm" title="Delete Confirmation" @ok="delete_grammar_after_confirmation(delete_grammar_confirm_grammar_id)">
    Are you sure you want to delete this Grammar lesson? <br/><br/>
    <div class="alert alert-warning">
        This action can not be undone later. The contents of this grammar will be deleted.
    </div>
</b-modal>

<b-modal ref="delete_glossed_text_confirm" title="Delete Confirmation" @ok="delete_glossed_text_after_confirmation(delete_glossed_text_confirm_glossed_text_id)">
    Are you sure you want to delete this Glossed Text? <br/><br/>
    <div class="alert alert-warning">
        This action can not be undone later. The contents of this glossed text will be deleted.<br/>
        All attached glosses will be unattached, though they will still be on file.
    </div>
</b-modal>

<b-modal ref="delete_glossed_text_gloss_confirm" title="Delete Confirmation" @ok="delete_glossed_text_gloss_after_confirmation(delete_glossed_text_gloss_confirm_glossed_text_gloss_id)">
    Are you sure you want to remove this gloss? <br/><br/>
    <div class="alert alert-warning">
        This action can not be undone later. The contents of this gloss will be unattached from this
        glossed text.<br/><br/>
        The gloss will still be on file.
    </div>
</b-modal>

<b-modal ref="attach_gloss_modal" title="Attach Gloss" size="xl">
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
            <a href="#" style="cursor:pointer;" @click.prevent="attach_gloss(g.id,modal_attached_gloss.glossed_text_id)" v-html="g.html"></a>
        </div>
    </div>

    <hr/>
    <h4>Or Add New Gloss</h4>

    <form method="POST"
          action="/admin2/eieol_gloss"
          class="form">

        <div class='row'>
            <div class='form-group col-sm-2'>
                <label for="surface_form">Surface Form</label>
                <input placeholder="Surface Form" class="form-control custom-keyboard" id="surface_form" name="surface_form" type="text"
                    v-model="modal_attached_gloss.surface_form">
                <div id ="surface_form_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['surface_form']">{{error}}</div></div>
            </div>

            <div class='form-group col-sm-2'>
                <label for="element_1_part_of_speech">Part Of Speech</label>
                <input placeholder="Part Of Speech" class="form-control part_of_speech" name="element_1_part_of_speech" type="text" id="element_1_part_of_speech"
                       v-model="modal_attached_gloss.element_1_part_of_speech">
                <div id ="element_1_part_of_speech_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_1_part_of_speech']">{{error}}</div></div>
            </div>

            <div class='form-group col-sm-3'>
                <label for="element_1_analysis">Analysis</label>
                <textarea class="form-control analysis" name="element_1_analysis" cols="10" rows="2" id="element_1_analysis"
                          v-model="modal_attached_gloss.element_1_analysis"></textarea>
                <div id ="element_1_analysis_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_1_analysis']">{{error}}</div></div>
            </div>

            <div class='form-group col-sm-2'>
                <label for="element_1_head_word_id">Head Word</label>
                <input id="element_1_head_word_id" name="element_1_head_word_id" type="hidden">
                <div id="element_1_head_word_display"></div>
                <button class="btn btn-primary btn-sm pick_head_word_button" onclick="element_id =1" type="button">Pick Head Word</button>
                <div id ="element_1_head_word_id_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_1_head_word_id']">{{error}}</div></div>
            </div>

            <div class='form-group col-sm-2'>
                <label for="contextual_gloss">Contextual Gloss</label>
                <input placeholder="Contextual Gloss" class="form-control" id="contextual_gloss" name="contextual_gloss" type="text"
                       v-model="modal_attached_gloss.contextual_gloss">
                <div id ="contextual_gloss_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['contextual_gloss']">{{error}}</div></div>
            </div>

            <div class='form-group col-sm-1 bottom_button'>
                <input class="btn btn-sm btn-success" type="button" value="Add"
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
                           v-model="modal_attached_gloss.element_2_part_of_speech">
                    <div id="element_2_part_of_speech_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_2_part_of_speech']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-3'>
                    <label for="element_2_analysis">Analysis</label>
                    <textarea class="form-control analysis" name="element_2_analysis" cols="10" rows="2"
                              id="element_2_analysis"
                              v-model="modal_attached_gloss.element_2_analysis"></textarea>
                    <div id="element_2_analysis_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_2_analysis']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-2'>
                    <label for="element_2_head_word_id">Head Word</label>
                    <input id="element_2_head_word_id" name="element_2_head_word_id" type="hidden">
                    <div id="element_2_head_word_display"></div>
                    <button class="btn btn-primary btn-sm pick_head_word_button" onclick="element_id =2"
                            type="button">Pick Head Word
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
                           v-model="modal_attached_gloss.element_3_part_of_speech">
                    <div id="element_3_part_of_speech_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_3_part_of_speech']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-3'>
                    <label for="element_3_analysis">Analysis</label>
                    <textarea class="form-control analysis" name="element_3_analysis" cols="10" rows="2"
                              id="element_3_analysis"
                              v-model="modal_attached_gloss.element_3_analysis"></textarea>
                    <div id="element_3_analysis_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_3_analysis']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-2'>
                    <label for="element_3_head_word_id">Head Word</label>
                    <input id="element_3_head_word_id" name="element_3_head_word_id" type="hidden">
                    <div id="element_3_head_word_display"></div>
                    <button class="btn btn-primary btn-sm pick_head_word_button" onclick="element_id =3"
                            type="button">Pick Head Word
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
                           v-model="modal_attached_gloss.element_4_part_of_speech">
                    <div id="element_4_part_of_speech_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_4_part_of_speech']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-3'>
                    <label for="element_4_analysis">Analysis</label>
                    <textarea class="form-control analysis" name="element_4_analysis" cols="10" rows="2"
                              id="element_4_analysis"
                              v-model="modal_attached_gloss.element_4_analysis"></textarea>
                    <div id="element_4_analysis_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_4_analysis']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-2'>
                    <label for="element_4_head_word_id">Head Word</label>
                    <input id="element_4_head_word_id" name="element_4_head_word_id" type="hidden">
                    <div id="element_4_head_word_display"></div>
                    <button class="btn btn-primary btn-sm pick_head_word_button" onclick="element_id =4"
                            type="button">Pick Head Word
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
                           v-model="modal_attached_gloss.element_5_part_of_speech">
                    <div id="element_5_part_of_speech_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_5_part_of_speech']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-3'>
                    <label for="element_5_analysis">Analysis</label>
                    <textarea class="form-control analysis" name="element_5_analysis" cols="10" rows="2"
                              id="element_5_analysis"
                              v-model="modal_attached_gloss.element_5_analysis"></textarea>
                    <div id="element_5_analysis_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_5_analysis']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-2'>
                    <label for="element_5_head_word_id">Head Word</label>
                    <input id="element_5_head_word_id" name="element_5_head_word_id" type="hidden">
                    <div id="element_5_head_word_display"></div>
                    <button class="btn btn-primary btn-sm pick_head_word_button" onclick="element_id =5"
                            type="button">Pick Head Word
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
                           v-model="modal_attached_gloss.element_6_part_of_speech">
                    <div id="element_6_part_of_speech_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_6_part_of_speech']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-3'>
                    <label for="element_6_analysis">Analysis</label>
                    <textarea class="form-control analysis" name="element_6_analysis" cols="10" rows="2"
                              id="element_6_analysis"
                              v-model="modal_attached_gloss.element_6_analysis"></textarea>
                    <div id="element_6_analysis_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_6_analysis']">{{error}}</div></div>
                </div>

                <div class='form-group col-sm-2'>
                    <label for="element_6_head_word_id">Head Word</label>
                    <input id="element_6_head_word_id" name="element_6_head_word_id" type="hidden">
                    <div id="element_6_head_word_display"></div>
                    <button class="btn btn-primary btn-sm pick_head_word_button" onclick="element_id =6"
                            type="button">Pick Head Word
                    </button>
                    <div id="element_6_head_word_id_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['element_6_head_word_id']">{{error}}</div></div>
                </div>
            </div>

        <div class='row'>
            <div class='form-group col-sm-12'>
                <label for="comments">Comments</label>
                <input placeholder="Comments" class="form-control" id="comments" name="comments"
                       type="text"
                       v-model="modal_attached_gloss.comments">
                <div id="comments_gloss_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['comments']">{{error}}</div></div>
            </div>
        </div>

        <div class='row'>
            <div class='form-group col-sm-12'>
                <label for="underlying_form">Underlying Form</label>
                <input placeholder="Underlying Form" class="form-control" id="underlying_form"
                       name="underlying_form" type="text"
                       v-model="modal_attached_gloss.underlying_form">
                <div id="underlying_form_gloss_error" class="alert-danger errors"><div v-for="error in modal_attached_gloss_errors['underlying_form']">{{error}}</div></div>
            </div>
        </div>

    </form>

    <div slot="modal-footer"><!-- no ok or cancel buttons --></div>
</b-modal>

<div class='col-lg-12'>
 
    <h1><i class='fa fa-file-text'></i> Edit Lesson for <a :href="'/admin2/eieol_series/'+lesson.series.id+'/edit'" title="Return to series">{{lesson.series.title}}</a></h1>
    <p><a href="/guides/eieol_author" target=_new>Author Guide</a></p>
    <p><a :href="'/eieol_lesson/'+lesson.series.id+'?id='+lesson.id" target="_blank">Preview</a></p>
    <div class='alert-warning alert'>
    	If you change the order of items on this page, they will not appear in that order until you refresh the page.
    	<br/><br/>
    	<a href="#"
    	   data-toggle="popover" 
    	   data-trigger="focus"
    	   title="How to add images" 
    	   data-html ="true"
    	   data-content="You need a FTP program like Filezilla.  New users will have to be authorized for this (contact la-help@utlists.utexas.edu.)  Set it up with the following:
						<ol>
							<li>Make sure you are using SFTP</li>
							<li>The host is file.laits.utexas.edu</li>
							<li>Logon Type is normal</li>
							<li>User is your EID</li>
							<li>Password is your EID password</li>
							<li>In the Advanced Tab, set the Default remote directory to /mnt/www/la.utexas.edu/lrc.</li>
						</ol>
						Here you can drag images from your machine
						<br/><br/>
						Within the lesson editor, any field that has a tool bar has a button for an image.  It should be the 2nd to last button.  For the url, put http://www.la.utexas.edu/lrc/ followed by the name of your image.  You can further set the size, border alternate text, etc...
    	   ">
    		How to add images
    	</a>
    </div>

    <form method="POST" :action="'/admin2/eieol_lesson/'+lesson.id"
          accept-charset="UTF-8"
          class="form"
          id="update_form"
          onsubmit="ajax_submit(this);return false;"
          :dirty="isFormDirty('update_form')">
        <input name="_method" type="hidden" value="PUT">

    <input name="series_id" type="hidden" :value="lesson.series_id">

		<div class='form-row'>
			<div class='col-sm-1'></div>
			
			<div class='form-group col-sm-1 '>
                <label for="order">Order</label>
                <input placeholder="Order" class="form-control" name="order" type="text" id="order"
                       v-model="lesson.order"
                       autocomplete="off"
                       @input="markFormDirty('update_form')"
                >
		        <div id="order_error" class="alert-danger errors"></div>
		    </div>
		    	
		    <div class='form-group col-sm-3'>
                <label for="title">Title</label>
                <input-custom-keyboard placeholder="Title"
                       name="title"
                       type="text"
                       v-model="lesson.title"
                        @input="markFormDirty('update_form')"
                       :custom_keyboard="custom_keyboard_layout">
                </input-custom-keyboard>
		        <div id="title_error" class="alert-danger errors"></div>
		    </div>
		    
		    <div class='form-group col-sm-2'>
                <label for="language">Language</label><br/>
                    <select id="language" name="language" v-model="lesson.language_id"
                            class="form-control"
                        @change="markFormDirty('update_form')">
                    <option value="">Select a language</option>
                    <option v-for="(lang_name, lang_id) in languages" :value="lang_id">
                        {{ lang_name }}
                    </option>
                </select>
		        <div id="language_error" class="alert-danger errors"></div>
		    </div>
		    
		    <div class='form-group col-sm-2'>
		    	<comment-icon :author_comment="lesson.author_comments"
                              :admin_comment="lesson.admin_comments"
                              :author_done="lesson.author_done"
                              @click="toggleCommentsOpen('lesson_main')"></comment-icon>
		    </div>
		    
		 </div>

        <comment-area v-model="lesson"
                      :is_user_admin="is_user_admin"
                      :show_comments_area="areCommentsOpen('lesson_main')"
                      @input="markFormDirty('update_form')"
        ></comment-area>
			    	
		<div class='row'>	    	
		    <div class='form-group col-sm-10 offset-1'>
                <label for="intro_text">Intro Text</label>
		        <ck-editor html_id="intro_text"
                           html_name="intro_text"
                           v-model="lesson.intro_text"
                           :custom_config="ckeditor_customization"
                           @input="markFormDirty('update_form')"
                ></ck-editor>
		        <div id="intro_text_error" class="alert-danger errors"></div>
                <input class="btn btn-sm btn-primary" type="submit" value="Save">
                <button type="button" class="btn btn-sm btn-secondary" v-b-modal.intro_text_preview>Preview</button>
                <b-modal id="intro_text_preview" title="Intro Text"  :ok-only="true"
                         size="xl"><div v-html="lesson.intro_text"></div></b-modal>
		    </div>
		</div>		    

    </form>

    
    <!-- ---------------------------------------------------------------------------------------- -->  
    
    <hr/>
    <h2>Glossed Texts</h2>
    
    <div id ="glossed_texts">
        <div v-for="(glossed_text, glossed_text_ix) in glossed_texts">

                <form method="POST"
                      :action="glossed_text.id==='' ? '/admin2/eieol_glossed_text' : '/admin2/eieol_glossed_text/'+glossed_text.id"
                      accept-charset="UTF-8"
                      class="form glossed_text_form"
                      :id="'glossed_text_form_'+glossed_text.id"
                      onsubmit="ajax_submit(this);return false;"
                      :dirty="isFormDirty('glossed_text_form_'+glossed_text.id)">
                    <input type="hidden" name="lesson_id" :value="lesson.id">
                    <input name="_method" type="hidden" value="PUT" v-if="glossed_text.id !== ''">
					
					<div class='row'>
						<div class='col-sm-1'></div>
						
						<div class='form-group col-sm-1 '>
                            <label for="order">Order</label>
                            <input placeholder="Order" class="form-control" id="order" name="order" type="text"
                                v-model="glossed_text.order">
					        <div id ="order_error" class="alert-danger errors"></div>
					    </div>
					    	
					    <div class='form-group col-sm-7'>
                            <label for="glossed_text">Glossed Text</label>
                            <textarea-custom-keyboard placeholder="Glossed Text"
                                                   name="glossed_text"
                                                   v-model="glossed_text.glossed_text"
                                                   @input="markFormDirty('glossed_text_form_'+glossed_text.id)"
                                                      :lang="lesson.language.lang_attribute"
                                                      :custom_keyboard="custom_keyboard_layout"></textarea-custom-keyboard>
					        <div id ="glossed_text_error" class="alert-danger errors"></div>
					    </div>	    
					    
					    <div class='form-group col-sm-1 comment_button'>
                            <comment-icon :author_comment="glossed_text.author_comments"
                                          :admin_comment="glossed_text.admin_comments"
                                          :author_done="glossed_text.author_done"
                                          @click="toggleCommentsOpen('glossed_text_'+glossed_text.id)"></comment-icon>
						</div>
					    
					    <div class='form-group col-sm-1 bottom_button'>
                            <input class="btn btn-sm btn-primary" type="submit" value="Save">
						</div>
					
			    		<div class='form-group col-sm-1 bottom_button'>
                            <button class="btn btn-sm btn-danger delete_glossed_text" type="button"
                                    @click="delete_glossed_text(glossed_text.id)"
                                    v-if="glossed_text.id !== ''">Delete</button>
                        </div>
	
				    </div>


                    <comment-area v-model="glossed_texts[glossed_text_ix]"
                                  :is_user_admin="is_user_admin"
                                  :show_comments_area="areCommentsOpen('glossed_text_'+glossed_text.id)"
                                  @input="markFormDirty('glossed_text_form_'+glossed_text.id)"
                    ></comment-area>

                </form>
			    
			    <div class="row">
                    <div class='col-sm-2'></div>
                    <div class="col-sm-10">
                <b-button size="sm" variant="secondary"
                          v-b-toggle="'glosses-'+glossed_text.id"
                          v-if="glossed_text.id !== ''">Toggle Glosses</b-button>
			    </div>
			    </div>
			    
			    <b-collapse :id="'glosses-'+glossed_text.id">
			    
                    <p></p>
			    <div id="'glossed_text_'+glossed_text.id+'_glosses'">
					  <div v-for="(gloss, gloss_ix) in glossed_text.glosses">
					  
						<div class='row'>
							<div class='col-sm-2'></div>

                            <form method="POST"
                                  :action="gloss.id==='' ? '/admin2/eieol_glossed_text_gloss' : '/admin2/eieol_glossed_text_gloss/'+gloss.id"
                                  accept-charset="UTF-8"
                                  class="form"
                                  :id="'glossed_text_gloss_form_'+gloss.id"
                                  onsubmit="ajax_submit(this);return false;"
                                  :dirty="isFormDirty('glossed_text_gloss_form_'+gloss.id)">
                                <input type="hidden" name="glossed_text_id" :value="glossed_text.id">
                                <input name="_method" type="hidden" value="PUT" v-if="gloss.id !== ''">
							<div class='form-group col-sm-1 '>

						        <label for="order">Order</label>
                                <div class="row">
                                    <input placeholder="Order" id="order" name="order" type="text"
                                           v-model="gloss.order">
                                    <input type="submit" value="Save Order" class="btn btn-sm btn-primary">
                                </div>
						        <div id ="order_error" class="alert-danger errors"></div>
						    </div>

                            </form>

                            <div class='col-sm-4'>
                                <br>
                                <span :lang="gloss.language.lang_attribute">{{gloss.surface_form}}</span>
                                <span style="white-space: nowrap">--</span>
                                    <span v-html="getElementsForDisplay(gloss)"></span>
                                    <span style="white-space: nowrap">--</span>
                                <strong>{{gloss.contextual_gloss}}</strong>
                                    <span v-if="gloss.comments"># {{gloss.comments}}</span>
                                    <span v-if="gloss.underlying_form">
                                        <br/>
                                        <span :lang="gloss.language.lang_attribute"
                                              style="margin-left:10px;">
                                            ({{gloss.underlying_form}})
                                        </span>
                                    </span>
			    			</div>
			    			
			    			<div class='col-sm-1 bottom_button gloss_comment_indicator'>
                                <comment-icon :author_comment="gloss.author_comments"
                                              :admin_comment="gloss.admin_comments"
                                              :author_done="gloss.author_done"></comment-icon>
			    			</div>
			    			
			    			<div class='col-sm-1 bottom_button'>
                                <form class="edit_gloss" :id="'edit_gloss_form_' + gloss.id">
                                    <input type="hidden" name="gloss_id" :value="gloss.id">
                                    <input type="button" value="Edit Gloss" class="btn btn-sm btn-primary"
                                        @click="open_edit_gloss_modal(gloss.id)">
			    				</form>
			    			</div>
			    			
			    			<div class='form-group col-sm-1 bottom_button'>
                                <input type="button" value="Remove" class="btn btn-sm btn-danger"
                                       @click="delete_glossed_text_gloss(gloss.id)">
							</div>
						      
					    </div>
					  </div>
			    </div>
			   
			    <!-- this will open a modal to attach a gloss to the glossed text --> 
			    <div class='row'>
			   		<div class='col-sm-2'></div>
			   		<div class='form-group col-sm-1 '>
                        <input class="btn btn-sm btn-success" type="button" value="Attach Gloss"
                                @click="open_attach_gloss_modal(glossed_text.id)">
				    </div>
				</div>
				
				  </b-collapse>

			    <hr/>
			</div>
    </div>
	  
	  
	  <!-- Button that will clone the new glossed text template -->
	  <div class='row'>
	  	<div class='col-sm-1'></div>
	  	<div class="col-sm-1">
	  		<a class="btn btn-sm btn-success" @click="add_glossed_text()">Create New Glossed Text</a>
	  	</div>
	  </div>


    <!-- ---------------------------------------------------------------------------------------- -->

    
    <hr/>
    
    <h2>Text and Translation</h2>

    <div class='row'>
		<div class='col-sm-10 offset-1'>
	        <strong>Lesson Text</strong> 
	        <div class="card"><div class="card-body" id="lesson_text">
                {{lesson_text}}
	        </div></div>
	    </div>
	    <br/>
    </div>

    <form method="POST" :action="'/admin2/eieol_lesson/update_translation/'+lesson.id"
          accept-charset="UTF-8"
          class="form"
          id="update_translation_form"
          onsubmit="ajax_submit(this);return false;"
          :dirty="isFormDirty('update_translation_form')">
        <input name="_method" type="hidden" value="PUT">

        <div class='row'>
			<div class='form-group col-sm-10 offset-1'>
                <label for="lesson_translation">Lesson Translation</label>
                <ck-editor html_id="lesson_translation"
                           html_name="lesson_translation"
                           v-model="lesson.lesson_translation"
                           :custom_config="ckeditor_customization"
                           @input="markFormDirty('update_translation_form')"
                ></ck-editor>
		        <div id="lesson_translation_error" class="alert-danger errors"></div>
		    </div>
		    <div class='form-group col-sm-1'>
                <comment-icon :author_comment="lesson.translation_author_comments"
                              :admin_comment="lesson.translation_admin_comments"
                              :author_done="lesson.translation_author_done"
                              @click="toggleCommentsOpen('lesson_translation')"></comment-icon>
			</div>
	    </div>

        <comment-area v-model="lesson"
              :is_user_admin="is_user_admin"
                      author_comments_prop_name="translation_author_comments"
                      author_done_prop_name="translation_author_done"
                      admin_comments_prop_name="translation_admin_comments"
              :show_comments_area="areCommentsOpen('lesson_translation')"
              @input="markFormDirty('update_translation_form')"
        ></comment-area>

	    <div class='row'>
	    	<div class='form-group col-sm-2 offset-1'>
                <input class="btn btn-sm btn-primary" type="submit" value="Save Translation">
                <button type="button" class="btn btn-sm btn-secondary" v-b-modal.lesson_translation_preview>Preview</button>
                <b-modal id="lesson_translation_preview" title="Lesson Translation" :ok-only="true"
                         size="xl"><div v-html="lesson.lesson_translation"></div></b-modal>
            </div>
	    </div>
	    
	</form>
	
	
	<!-- ---------------------------------------------------------------------------------------- -->
	
	
	<hr/>
    <h2>Grammar</h2>	
    <div id ="grammars">
        <div v-for="(grammar, grammar_ix) in grammars">
            <form method="POST" :action="grammar.id==='' ? '/admin2/eieol_grammar' : '/admin2/eieol_grammar/'+grammar.id"
                  accept-charset="UTF-8"
                  class="form grammar_form"
                  :id="'grammar_form_'+grammar.id"
                  onsubmit="ajax_submit(this);return false;"
                  :dirty="isFormDirty('grammar_form_'+grammar.id)"
            >
                <input name="_method" type="hidden" value="PUT" v-if="grammar.id !== ''">
                <input name="lesson_id" type="hidden" :value="lesson.id">

                <div class='row'>
						<div class='col-sm-1'></div>
						
						<div class='form-group col-sm-1 '>
                            <label for="order">Order</label>
                            <input placeholder="Order" class="form-control" id="order" name="order" type="text"
                                   v-model="grammar.order"
                                   @input="markFormDirty('grammar_form_'+grammar.id)"
                            >
					        <div id ="order_error" class="alert-danger errors"></div>
					    </div>
					    
					    <div class='form-group col-sm-1 '>
                            <label for="section_number">Section Number</label>
                            <input placeholder="Section Number" class="form-control" name="section_number" type="text" id="section_number"
                                   v-model="grammar.section_number"
                                   @input="markFormDirty('grammar_form_'+grammar.id)"
                            >
					        <div id ="section_number_error" class="alert-danger errors"></div>
					    </div>
					    	
					    <div class='form-group col-sm-3'>
                            <label for="title">Title</label>
                            <input-custom-keyboard placeholder="Title"
                                                   name="title"
                                                   type="text"
                                                   v-model="grammar.title"
                                                   @input="markFormDirty('grammar_form_'+grammar.id)"
                                                   :custom_keyboard="custom_keyboard_layout"></input-custom-keyboard>
					        <div id ="title_error" class="alert-danger errors"></div>
					    </div>
					    
						<div class='form-group col-sm-1 comment_button'>
                            <comment-icon :author_comment="grammar.author_comments"
                                          :admin_comment="grammar.admin_comments"
                                          :author_done="grammar.author_done"
                                          @click="toggleCommentsOpen('grammar_'+grammar.id)"></comment-icon>
					    </div>
				    
				   </div>

                <comment-area v-model="grammars[grammar_ix]"
                              :is_user_admin="is_user_admin"
                              :show_comments_area="areCommentsOpen('grammar_'+grammar.id)"
                              @input="markFormDirty('grammar_form_'+grammar.id)"
                ></comment-area>

					<div class='row'>    
					    <div class='form-group col-sm-10 offset-1'>
                            <label for="grammar_text">Grammar Text</label>
                            <ck-editor :html_id="'grammar_text_'+grammar.id"
                                       html_name="grammar_text"
                                       v-model="grammar.grammar_text"
                                       :custom_config="ckeditor_customization"
                                       @input="markFormDirty('grammar_form_'+grammar.id)"
                            ></ck-editor>
					        <div id ="grammar_text_error" class="alert-danger errors"></div>
					    </div>	
					</div>
					
					<div class='row'>
			    		<div class='form-group col-sm-1 '></div>
			    		<div class='form-group col-sm-2 '>
                            <input class="btn btn-sm btn-primary" type="submit" value="Save">
                            <button type="button" class="btn btn-sm btn-secondary" v-b-modal="'grammar_text_preview_'+grammar.id">Preview</button>
                            <b-modal :id="'grammar_text_preview_'+grammar.id" :ok-only="true"
                                     title="Grammar" size="xl"><div v-html="grammar.grammar_text"></div></b-modal>
                        </div>
			    		
			    		<div class='form-group col-sm-8 '></div>
			    		<div class='form-group col-sm-1 '>
                            <button class="btn btn-sm btn-danger delete_grammar" type="button"
                                    v-if="grammar.id !== ''"
                                @click.prevent="delete_grammar(grammar.id)">Delete</button>
						</div>
				    </div>
            </form>
			    
			    <hr/>
        </div>
    </div>

	  <!-- Button that will clone the new grammar template -->
	  <div class='row'>
	  	<div class='col-sm-1'></div>
	  	<div class="col-sm-1">
	  		<a class="btn btn-sm btn-success" @click="add_grammar()">Create New Grammar</a>
	  	</div>
	  </div>
    
</div>


@endverbatim
@stop
