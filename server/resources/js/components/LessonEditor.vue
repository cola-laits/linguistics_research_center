<template>
<div>
    <div class="spinner">
        <img src="/images/ajax_loader_red_350.gif" alt="Loading" width="150" height="150" style="border:0;">
        <br/>Please Wait...
    </div>

    <!-- ---------------------------------------------------------------------------------------- -->

    <b-modal ref="flash_modal" :title="modal_flash_title" :ok-only="true" size="md"><template v-slot:body><div v-html="modal_flash_body"></div></template></b-modal>

    <b-modal ref="delete_grammar_confirm" title="Delete Confirmation" @ok="delete_grammar_after_confirmation(delete_grammar_confirm_grammar_id)">
        <template v-slot:body>
        Are you sure you want to delete this Grammar lesson? <br/><br/>
        <div class="alert alert-warning">
            This action can not be undone later. The contents of this grammar will be deleted.
        </div>
        </template>
    </b-modal>

    <b-modal ref="delete_glossed_text_confirm" title="Delete Confirmation" @ok="delete_glossed_text_after_confirmation(delete_glossed_text_confirm_glossed_text_id)">
        <template v-slot:body>
        Are you sure you want to delete this Glossed Text? <br/><br/>
        <div class="alert alert-warning">
            This action can not be undone later. The contents of this glossed text will be deleted.<br/>
            All attached glosses will be unattached, though they will still be on file.
        </div>
        </template>
    </b-modal>

    <b-modal ref="delete_glossed_text_gloss_confirm" title="Delete Confirmation" @ok="delete_glossed_text_gloss_after_confirmation(delete_glossed_text_gloss_confirm_glossed_text_gloss_id)">
        <template v-slot:body>
        Are you sure you want to remove this gloss? <br/><br/>
        <div class="alert alert-warning">
            This action can not be undone later. The contents of this gloss will be unattached from this
            glossed text.<br/><br/>
            The gloss will still be on file.
        </div>
        </template>
    </b-modal>

    <gloss-editor ref="gloss_editor"
                  :gloss="gloss_for_edit"
                  :lesson_lang_attribute="lesson.language.lang_attribute"
                  :language="lesson.language"
                  :etymas="etymas"
                  @saved="update_gloss_after_save"
                  :custom_keyboard="custom_keyboard_layout"></gloss-editor>

    <div class='col-lg-12'>

        <h1><i class='fa fa-file-text'></i> Edit Lesson for <a :href="'/admin2/eieol_series/'+lesson.series.id+'/edit'" title="Return to series">{{lesson.series.title}}</a></h1>
        <p><a href="/guides/eieol_author" target=_new>Author Guide</a></p>
        <p><a :href="'/eieol/'+lesson.series.slug+'/'+lesson.order" target="_blank">Preview</a></p>
        <div class='alert-warning alert'>
            If you change the order of items on this page, they will not appear in that order until you refresh the page.
        </div>

        <form method="POST"
              accept-charset="UTF-8"
              class="form"
              id="update_form"
              @submit.prevent="save_lesson()"
              :dirty="isFormDirty('update_form')">

            <input name="series_id" type="hidden" :value="lesson.series_id">

            <div class='row g-3'>
                <div class='col-1'></div>

                <div class='mb-3 col-1'>
                    <label for="order">Order</label>
                    <input placeholder="Order" class="form-control" name="order" type="text"
                           v-model="lesson.order"
                           autocomplete="off"
                           @input="markFormDirty('update_form')"
                    >
                    <div id="order_error" class="alert-danger errors">{{get_error_message_html('order')}}</div>
                </div>

                <div class='mb-3 col-3'>
                    <label>Title</label>
                    <input-custom-keyboard placeholder="Title"
                                           name="title"
                                           type="text"
                                           v-model="lesson.title"
                                           @input="markFormDirty('update_form')"
                                           :custom_keyboard="custom_keyboard_layout">
                    </input-custom-keyboard>
                    <div id="title_error" class="alert-danger errors"></div>
                </div>

                <div class='mb-3 col-2'>
                    <label for="language">Language</label><br/>
                    <input type="text" disabled id="language" class="form-control" :value="lesson.language.language"/>
                </div>

                <div class='mb-3 col-2'>
                    <comment-icon :issue_pointer="'/lesson/'+lesson.id+'/intro_text'" :issues="init_issues"></comment-icon>
                </div>

            </div>

            <div class='row'>
                <div class='col-sm-10 offset-1'>
                    <label>Intro Text</label>
                    <ck-editor html_id="intro_text"
                               html_name="intro_text"
                               v-model="lesson.intro_text"
                               :custom_config="ckeditor_customization"
                               @input="markFormDirty('update_form')"
                    ></ck-editor>
                    <div id="intro_text_error" class="alert-danger errors"></div>
                    <input class="btn btn-sm btn-primary" type="submit" value="Save">
                    <button type="button" class="btn btn-sm btn-secondary" @click="this.$refs['intro_text_preview'].show()">Preview</button>
                    <b-modal id="intro_text_preview" title="Intro Text" ref="intro_text_preview" :ok-only="true"
                             size="xl"><template v-slot:body><div v-html="lesson.intro_text"></div></template></b-modal>
                </div>
            </div>

        </form>


        <!-- ---------------------------------------------------------------------------------------- -->

        <hr/>
        <h2>Glossed Texts</h2>

        <div id="glossed_texts">
            <div v-for="(glossed_text, glossed_text_ix) in glossed_texts">
                <a :name="'glossed_text/'+glossed_text.id"></a>
                <form method="POST"
                      accept-charset="UTF-8"
                      class="form glossed_text_form"
                      @submit.prevent="save_glossed_text(glossed_text)"
                      :dirty="isFormDirty(glossed_text)"
                >

                    <div class='row'>
                        <div class='col-sm-1'></div>

                        <div class='col-sm-1 '>
                            <label for="order">Order</label>
                            <input placeholder="Order" class="form-control" name="order" type="text"
                                   @input="markFormDirty(glossed_text)"
                                   v-model="glossed_text.order">
                            <div class="alert-danger errors">{{get_error_message_html('glossed_text_'+glossed_text.id+'_order')}}</div>
                        </div>

                        <div class='col-sm-7'>
                            <label>Glossed Text</label>
                            <textarea-custom-keyboard placeholder="Glossed Text"
                                                      name="glossed_text"
                                                      v-model="glossed_text.glossed_text"
                                                      @input="markFormDirty(glossed_text)"
                                                      :lang="lesson.language.lang_attribute"
                                                      :custom_keyboard="custom_keyboard_layout"></textarea-custom-keyboard>
                            <div class="alert-danger errors">{{get_error_message_html('glossed_text_'+glossed_text.id+'_glossed_text')}}</div>
                        </div>

                        <div class='col-sm-1 comment_button'>
                            <audio-icon v-model="glossed_text.audio_url"
                                        :id="'glossed-text-audio-'+glossed_text.id"
                                        :lang="lesson.language.lang_attribute"
                                        :text="glossed_text.glossed_text"
                                        @input="markFormDirty(glossed_text)"
                            >
                            </audio-icon>

                            <comment-icon :issue_pointer="'/lesson/'+lesson.id+'/glossed_text/'+glossed_text.id" :issues="init_issues"></comment-icon>
                        </div>

                        <div class='col-sm-1 bottom_button'>
                            <input class="btn btn-sm btn-primary" type="submit" value="Save">
                        </div>

                        <div class='col-sm-1 bottom_button'>
                            <button class="btn btn-sm btn-danger delete_glossed_text" type="button"
                                    @click="delete_glossed_text(glossed_text.id)"
                                    v-if="glossed_text.id !== ''">Delete</button>
                        </div>

                    </div>

                </form>

                <div class="row">
                    <div class='col-sm-2'></div>
                    <div class="col-sm-5">
                        <button class="btn btn-sm btn-secondary"
                                @click="toggled_gloss_id===null ? toggled_gloss_id=glossed_text.id : toggled_gloss_id=null"
                                v-if="glossed_text.id !== ''">Toggle Glosses</button>
                    </div>
                    <div class="col-sm-5">
                        <button class="btn btn-sm btn-secondary"
                                @click="toggled_glossmapper_id===null ? toggled_glossmapper_id=glossed_text.id : toggled_glossmapper_id=null"
                                v-if="glossed_text.id !== ''">Customize Gloss Mapping</button>
                    </div>
                </div>

                <div v-if="toggled_glossmapper_id===glossed_text.id" :id="'glossmapper-'+glossed_text.id">
                    <div>By default, we try to do a simple word-for-word match to find the glosses in this text.  For texts where that doesn't work for some reason, you can override that manually.  For each gloss, click the characters matching it in the glossed text to highlight them.  Then 'Save' the glossed text.</div>
                    <div v-for="gloss in glossed_text.glosses">
                        Gloss #{{gloss.order}}: {{gloss.surface_form}}<br>
                        <div style="overflow:scroll">
                            <table border="1">
                                <tr>
                                    <td v-for="(char, char_ix) in glossed_text.glossed_text"
                                        style="min-width:1em;cursor:default;"
                                        :style="is_custom_gloss_mapping_char_selected(glossed_text, gloss.id, char_ix) ? 'background-color:yellow;' : ''"
                                        @click="toggle_gloss_mapping_char(glossed_text, gloss.id, char_ix)"
                                    >{{combining_character_regex.test(char) ? '◌'+char : char}}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div v-if="toggled_gloss_id===glossed_text.id" :id="'glosses-'+glossed_text.id">
                    <p></p>
                    <div id="'glossed_text_'+glossed_text.id+'_glosses'">
                        <div v-for="(gloss, gloss_ix) in glossed_text.glosses">
                            <a :name="'gloss/'+gloss.id"></a>

                            <div class='row'>
                                <div class='col-2'></div>


                                    <div class='col-1'>
                                        <form method="POST"
                                              accept-charset="UTF-8"
                                              class="form"
                                              @submit.prevent="save_gloss_order(gloss)"
                                              :dirty="isFormDirty(gloss)"
                                        >
                                        <label for="order">Order</label>
                                        <div class="row">
                                            <input placeholder="Order" name="order" type="text"
                                                   @input="markFormDirty(gloss)"
                                                   v-model="gloss.order" class="form-control">
                                            <input type="submit" value="Save Order" class="btn btn-sm btn-primary">
                                        </div>
                                        <div class="alert-danger errors">{{get_error_message_html('gloss_'+gloss.id+'_order')}}</div>
                                        </form>
                                    </div>



                                <div class='col-4'>
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

                                <div class='col-1 bottom_button gloss_comment_indicator'>
                                    <comment-icon :issue_pointer="'/lesson/'+lesson.id+'/gloss/'+gloss.id" :issues="init_issues"></comment-icon>
                                </div>

                                <div class='col-1 bottom_button'>
                                    <form class="edit_gloss" :id="'edit_gloss_form_' + gloss.id">
                                        <input type="hidden" name="gloss_id" :value="gloss.id">
                                        <input type="button" value="Edit Gloss" class="btn btn-sm btn-primary"
                                               @click="open_edit_gloss_modal(gloss.id)">
                                    </form>
                                </div>

                                <div class='col-1 bottom_button'>
                                    <input type="button" value="Remove" class="btn btn-sm btn-danger"
                                           @click="delete_glossed_text_gloss(gloss.id)">
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- this will open a modal to attach a gloss to the glossed text -->
                    <div class='row'>
                        <div class='col-2'></div>
                        <div class='col-1 '>
                            <input class="btn btn-sm btn-success" type="button" value="Attach Gloss"
                                   @click="open_attach_gloss_modal(glossed_text.id)">
                        </div>
                    </div>

                </div>

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
                <div class="card"><div class="card-body" id="lesson_text" v-html="lesson_text">
                </div></div>
            </div>
            <br/>
        </div>

        <a name="lesson_translation"></a>
        <form method="POST" :action="'/admin2/eieol_lesson/update_translation/'+lesson.id"
              accept-charset="UTF-8"
              class="form"
              id="update_translation_form"
              @submit.prevent="save_lesson_translation()"
              :dirty="isFormDirty('update_translation_form')"
        >
            <input name="_method" type="hidden" value="PUT">

            <div class='row'>
                <div class='col-sm-10 offset-1'>
                    <label>Lesson Translation</label>
                    <ck-editor html_id="lesson_translation"
                               html_name="lesson_translation"
                               v-model="lesson.lesson_translation"
                               :custom_config="ckeditor_customization"
                               @input="markFormDirty('update_translation_form')"
                    ></ck-editor>
                    <div id="lesson_translation_error" class="alert-danger errors"></div>
                </div>
                <div class='col-sm-1'>
                    <comment-icon :issue_pointer="'/lesson/'+lesson.id+'/lesson_translation'" :issues="init_issues"></comment-icon>
                </div>
            </div>

            <div class='row'>
                <div class='col-sm-2 offset-1'>
                    <input class="btn btn-sm btn-primary" type="submit" value="Save Translation">
                    <button type="button" class="btn btn-sm btn-secondary" @click="this.$refs['lesson_translation_preview'].show()">Preview</button>
                    <b-modal id="lesson_translation_preview" title="Lesson Translation" ref="lesson_translation_preview" :ok-only="true"
                             size="xl"><template v-slot:body><div v-html="lesson.lesson_translation"></div></template></b-modal>
                </div>
            </div>

        </form>


        <!-- ---------------------------------------------------------------------------------------- -->


        <hr/>
        <h2>Grammar</h2>
        <div id ="grammars">
            <div v-for="(grammar, grammar_ix) in grammars">
                <a :name="'grammar/'+grammar.id"></a>
                <form method="POST" :action="grammar.id==='' ? '/admin2/eieol_grammar' : '/admin2/eieol_grammar/'+grammar.id"
                      accept-charset="UTF-8"
                      class="form grammar_form"
                      @submit.prevent="save_grammar(grammar)"
                      :dirty="isFormDirty(grammar)"
                >

                    <input name="_method" type="hidden" value="PUT" v-if="grammar.id !== ''">
                    <input name="lesson_id" type="hidden" :value="lesson.id">

                    <div class='row'>
                        <div class='col-sm-1'></div>

                        <div class='col-1'>
                            <label for="order">Order</label>
                            <input placeholder="Order" class="form-control" id="order" name="order" type="text"
                                   v-model="grammar.order"
                                   @input="markFormDirty(grammar)"
                            >
                            <div class="alert-danger errors">{{get_error_message_html('grammar_'+grammar.id+'_order')}}</div>
                        </div>

                        <div class='col-2'>
                            <label for="section_number">Section Number</label>
                            <input placeholder="Section Number" class="form-control" name="section_number" type="text" id="section_number"
                                   v-model="grammar.section_number"
                                   @input="markFormDirty(grammar)"
                            >
                            <div class="alert-danger errors">{{get_error_message_html('grammar_'+grammar.id+'_section_number')}}</div>
                        </div>

                        <div class='col-3'>
                            <label>Title</label>
                            <input-custom-keyboard placeholder="Title"
                                                   name="title"
                                                   type="text"
                                                   v-model="grammar.title"
                                                   @input="markFormDirty(grammar)"
                                                   :custom_keyboard="custom_keyboard_layout"></input-custom-keyboard>
                            <div class="alert-danger errors">{{get_error_message_html('grammar_'+grammar.id+'_title')}}</div>
                        </div>

                        <div class='col-2 comment_button'>
                            <comment-icon :issue_pointer="'/lesson/'+lesson.id+'/grammar/'+grammar.id" :issues="init_issues"></comment-icon>
                        </div>

                    </div>

                    <div class='row'>
                        <div class='col-sm-10 offset-1'>
                            <label>Grammar Text</label>
                            <ck-editor :html_id="'grammar_text_'+grammar.id"
                                       html_name="grammar_text"
                                       v-model="grammar.grammar_text"
                                       :custom_config="ckeditor_customization"
                                       @input="markFormDirty(grammar)"
                            ></ck-editor>
                            <div class="alert-danger errors">{{get_error_message_html('grammar_'+grammar.id+'_grammar_text')}}</div>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='col-sm-1 '></div>
                        <div class='col-sm-2 '>
                            <input class="btn btn-sm btn-primary" type="submit" value="Save">
                            <button type="button" class="btn btn-sm btn-secondary" @click="this.$refs['grammar_text_preview_'+grammar.id][0].show()">Preview</button>
                            <b-modal :id="'grammar_text_preview_'+grammar.id" :ref="'grammar_text_preview_'+grammar.id" :ok-only="true"
                                     title="Grammar" size="xl"><template v-slot:body><div v-html="grammar.grammar_text"></div></template></b-modal>
                        </div>

                        <div class='col-sm-8 '></div>
                        <div class='col-sm-1 '>
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

</div>
</template>

<script>
import Modal from './Modal.vue';
import AudioIcon from './AudioIcon.vue';
import CKEditor from './CkEditor.vue';
import CommentIcon from './CommentIcon.vue';
import GlossEditor from './GlossEditor.vue';
import InputCustomKeyboard from './InputCustomKeyboard.vue';
import TextareaCustomKeyboard from "./TextareaCustomKeyboard.vue";

    export default {
        props: [
            'init_lesson',
            'init_languages',
            'init_glossed_texts',
            'init_grammars',
            'init_etymas',
            'init_issues',
            'init_ckeditor_customization',
            'init_custom_keyboard_layout',
            'focus',
        ],
        data: function() {return {
            'lesson': {},
            'languages': {},
            'glossed_texts': {},
            'grammars': {},
            'etymas': {},
            'ckeditor_customization': {},
            'custom_keyboard_layout': {},
            'dirty_objects': [],
            'modal_flash_title': '',
            'modal_flash_body': '',
            'delete_grammar_confirm_grammar_id': '',
            'delete_glossed_text_confirm_glossed_text_id': '',
            'delete_glossed_text_gloss_confirm_glossed_text_gloss_id': '',
            'gloss_for_edit': {},
            'error_messages': {},
            'combining_character_regex': /\p{Mn}/gu,
            'toggled_gloss_id': null,
            'toggled_glossmapper_id': null,
        };},
        components: {
            'b-modal': Modal,
            'audio-icon': AudioIcon,
            'ck-editor': CKEditor,
            'comment-icon': CommentIcon,
            'gloss-editor': GlossEditor,
            'input-custom-keyboard': InputCustomKeyboard,
            'textarea-custom-keyboard': TextareaCustomKeyboard,
        },
        computed: {
            lesson_text: function() {
                let lesson_text = '';
                this.glossed_texts.forEach(function(gt) {
                    if (gt.glossed_text) {
                        lesson_text += gt.glossed_text.replace('<p>', '').replace('</p>', '') + ' ';
                    }
                });
                return lesson_text;
            },
        },
        methods: {
            flash_modal(msg) {
                this.modal_flash_title = 'Update Confirmation';
                this.modal_flash_body = msg;
                this.$refs['flash_modal'].show();
                setTimeout(() => {
                    this.$refs['flash_modal'].hide();
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
            markFormDirty(obj) {
                if (this.dirty_objects.indexOf(obj) !== -1) {
                    return;
                }
                this.dirty_objects.push(obj);
            },
            markFormClean(obj) {
                let ix = this.dirty_objects.indexOf(obj);
                if (ix !== -1) {
                    this.dirty_objects.splice(ix, 1);
                }
            },
            isFormDirty(obj) {
                if (this.dirty_objects.indexOf(obj) !== -1) {
                    return true;
                }
                return null;
            },
            save_lesson() {
                document.querySelector(".spinner").style.display = "block";
                axios.post('/admin2/eieol_lesson/'+this.lesson.id, {
                    _method:'PUT',
                    title:this.lesson.title,
                    order:this.lesson.order,
                    intro_text:this.lesson.intro_text
                })
                    .then((response) => {
                        document.querySelector(".spinner").style.display = "none";
                        if (response.data.fail) {
                            this.error_messages = response.data.errors;
                        } else {
                            this.error_messages = {};
                            this.flash_modal(response.data.message);
                            this.markFormClean('update_form');
                        }
                    });
            },
            save_lesson_translation() {
                document.querySelector(".spinner").style.display = "block";
                axios.post('/admin2/eieol_lesson/update_translation/'+this.lesson.id, {
                    _method:'PUT',
                    lesson_translation:this.lesson.lesson_translation,
                })
                    .then((response) => {
                        document.querySelector(".spinner").style.display = "none";
                        if (response.data.fail) {
                            this.error_messages = response.data.errors;
                        } else {
                            this.error_messages = {};
                            this.flash_modal(response.data.message);
                            this.markFormClean('update_translation_form');
                        }
                    });
            },
            get_error_message_html(key) {
                if (!this.error_messages[key]) {
                    return null;
                }
                return this.error_messages[key].join("<br>");
            },
            delete_grammar(id) {
                this.delete_grammar_confirm_grammar_id = id;
                this.$refs['delete_grammar_confirm'].show();
            },
            delete_grammar_after_confirmation(id) {
                axios.post('/admin2/eieol_grammar/'+id, {'_method':'delete'})
                    .then((response) => {
                        this.grammars = this.grammars.filter(function(grammar) {
                            return grammar.id !== id;
                        });

                        this.flash_modal('Grammar has been deleted.');
                    });
            },
            add_grammar() {
                let next_grammar_order = 0;
                this.grammars.forEach(function(grammar) {
                    let order = parseInt(grammar.order);
                    if(order > next_grammar_order) {
                        next_grammar_order = order;
                    }
                });
                next_grammar_order += 10;

                this.grammars.push({
                    id:"",
                    order:next_grammar_order,
                    lesson_id:this.lesson.id
                });
            },
            save_grammar(grammar) {
                let is_new = !grammar.id;
                document.querySelector(".spinner").style.display = "block";
                let url = grammar.id==='' ? '/admin2/eieol_grammar' : '/admin2/eieol_grammar/'+grammar.id;
                let payload = grammar.id==='' ? grammar : Object.assign(grammar, {_method:'PUT'});
                axios.post(url, payload)
                    .then((response) => {
                        document.querySelector(".spinner").style.display = "none";
                        if (response.data.fail) {
                            this.error_messages = _.mapKeys(response.data.errors, function(value,key) {
                                return 'grammar_'+grammar.id+'_'+key;
                            });
                        } else {
                            this.error_messages = {};
                            this.flash_modal(response.data.message);
                            this.markFormClean(grammar);
                            if (is_new) {
                                grammar.id = response.data.grammar_id;
                            }
                        }
                    });
            },
            delete_glossed_text(id) {
                this.delete_glossed_text_confirm_glossed_text_id = id;
                this.$refs['delete_glossed_text_confirm'].show();
            },
            delete_glossed_text_after_confirmation(id) {
                axios.post('/admin2/eieol_glossed_text/'+id, {'_method':'delete'})
                    .then((response) => {
                        this.glossed_texts = this.glossed_texts.filter(function(glossed_text) {
                            return glossed_text.id !== id;
                        });

                        this.flash_modal('Glossed Text has been deleted.');
                    });
            },
            add_glossed_text() {
                //calculate next order by finding the highest order in the form and adding 10
                let next_glosssed_text_order = 0;
                this.glossed_texts.forEach(function(gt) {
                    let order = parseInt(gt.order);
                    if(order > next_glosssed_text_order) {
                        next_glosssed_text_order = order;
                    }
                });
                next_glosssed_text_order += 10;
                this.glossed_texts.push({
                    id:"",
                    order:next_glosssed_text_order,
                    lesson_id:this.lesson.id
                });
            },
            save_glossed_text(glossed_text) {
                let is_new = !glossed_text.id;
                document.querySelector(".spinner").style.display = "block";
                let url = glossed_text.id==='' ? '/admin2/eieol_glossed_text' : '/admin2/eieol_glossed_text/'+glossed_text.id;
                let payload = glossed_text.id==='' ? glossed_text : Object.assign(glossed_text, {_method:'PUT'});
                axios.post(url, payload)
                    .then((response) => {
                        document.querySelector(".spinner").style.display = "none";
                        if (response.data.fail) {
                            this.error_messages = _.mapKeys(response.data.errors, function(value,key) {
                                return 'glossed_text_'+glossed_text.id+'_'+key;
                            });
                        } else {
                            this.error_messages = {};
                            this.flash_modal(response.data.message);
                            this.markFormClean(glossed_text);
                            if (is_new) {
                                glossed_text.id = response.data.glossed_text_id;
                            }
                        }
                    });
            },
            delete_glossed_text_gloss(id) {
                this.delete_glossed_text_gloss_confirm_glossed_text_gloss_id = id;
                this.$refs['delete_glossed_text_gloss_confirm'].show();
            },
            delete_glossed_text_gloss_after_confirmation(id) {
                axios.post('/admin2/eieol_glossed_text_gloss/'+id, {'_method':'delete'})
                    .then((response) => {
                        this.glossed_texts.forEach(function (glossed_text) {
                            glossed_text.glosses = glossed_text.glosses.filter(function(gloss) {
                                return gloss.id !== id;
                            });
                        });

                        this.flash_modal('Gloss has been unattached.');
                    });
            },
            open_attach_gloss_modal(glossed_text_id) {
                this.gloss_for_edit = {
                    'glossed_text_id': glossed_text_id,
                    'language_id': this.lesson.language_id
                };
                this.$refs['gloss_editor'].show();
            },
            update_gloss_after_save(glosses, glossed_text_id) {
                this.glossed_texts.forEach(function(gt) {
                    if (gt.id===glossed_text_id) {
                        gt.glosses = glosses;
                    }
                });

                this.$refs['gloss_editor'].hide();
                this.flash_modal('Gloss successfully added.');
            },
            open_edit_gloss_modal(gloss_id) {
                axios.get('/admin2/eieol_gloss/' + gloss_id)
                    .then((response) => {
                        this.gloss_for_edit = response.data;
                        this.$refs['gloss_editor'].show();
                    });
            },
            save_gloss_order(gloss) {
                document.querySelector(".spinner").style.display = "block";
                let url = gloss.id==='' ? '/admin2/eieol_glossed_text_gloss' : '/admin2/eieol_glossed_text_gloss/'+gloss.id;
                let payload = gloss.id==='' ? gloss : Object.assign(gloss, {_method:'PUT'});
                axios.post(url, payload)
                    .then((response) => {
                        document.querySelector(".spinner").style.display = "none";
                        if (response.data.fail) {
                            this.error_messages = _.mapKeys(response.data.errors, function(value,key) {
                                return 'gloss_'+gloss.id+'_'+key;
                            });
                        } else {
                            this.error_messages = {};
                            this.flash_modal(response.data.message);
                            this.markFormClean(gloss);
                        }
                    });
            },
            toggle_gloss_mapping_char(glossed_text, gloss_id, char_ix) {
                if (!glossed_text.custom_gloss_mapping) {
                    glossed_text.custom_gloss_mapping = {};
                }
                if (!glossed_text.custom_gloss_mapping[gloss_id]) {
                    glossed_text.custom_gloss_mapping[gloss_id] = [];
                }
                if (glossed_text.custom_gloss_mapping[gloss_id].includes(char_ix)) {
                    glossed_text.custom_gloss_mapping[gloss_id] =
                        glossed_text.custom_gloss_mapping[gloss_id].filter(i => i != char_ix)
                } else {
                    glossed_text.custom_gloss_mapping[gloss_id].push(char_ix);
                }
                this.markFormDirty(glossed_text);
            },
            is_custom_gloss_mapping_char_selected(glossed_text, gloss_id, char_ix) {
                if (!glossed_text.custom_gloss_mapping) {
                    return false
                }
                let map = glossed_text.custom_gloss_mapping[gloss_id]
                if (!map) {
                    return false
                }
                return map.includes(char_ix)
            },
        },
        created() {
            this.lesson = this.init_lesson;
            this.languages = this.init_languages;
            this.glossed_texts = this.init_glossed_texts;
            this.grammars = this.init_grammars;
            this.etymas = this.init_etymas;
            this.ckeditor_customization = this.init_ckeditor_customization;
            this.custom_keyboard_layout = this.init_custom_keyboard_layout;
        },
        mounted() {
            if (this.focus) {
                if (this.focus.indexOf('gloss/')===0) {
                    this.glossed_texts.forEach(gt => {
                        this.$root.$emit('bv::toggle::collapse', 'glosses-'+gt.id)
                    });
                }
                const element = document.querySelector("a[name='"+this.focus+"']");
                window.setTimeout(function() {
                    element.scrollIntoView();
                }, 500);
            }
        },
    }
</script>
