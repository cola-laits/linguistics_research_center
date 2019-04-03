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
            'gloss_for_edit': {}
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
                var app = this;
                axios.get('/admin2/eieol_gloss/' + gloss_id)
                    .then(function(response) {
                        app.gloss_for_edit = response.data;
                        app.$refs['gloss_editor'].show();
                    });
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

<gloss-editor ref="gloss_editor" :gloss="gloss_for_edit"
              :is_user_admin="is_user_admin"
              :lesson_lang_attribute="lesson.language.lang_attribute"
              :language="lesson.language"
              @saved="update_gloss_after_save"
              :custom_keyboard="custom_keyboard_layout">></gloss-editor>

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
