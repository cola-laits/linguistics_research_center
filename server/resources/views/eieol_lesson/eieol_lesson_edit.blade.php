@extends('admin_layout')
 
@section('title') Edit Lesson @stop
 
@section('content')

<script type="text/javascript">

    window.lesson_language_id = {{$lesson->language_id}};
    window.lesson_language_custom_keyboard_layout = [ {!! $lesson->language->custom_keyboard_layout !!} ];
    @if (Auth::user()->isAdmin())
        window.isAdmin = true;
    @else
        window.isAdmin = false;
    @endif

</script>
<script src="/js/lesson_edit.js"></script>

<!-- ---------------------------------------------------------------------------------------- -->

<div class="spinner">
    <img src="/images/ajax_loader_red_350.gif" alt="Loading" width="150" height="150" border="0">
    <br/>Please Wait...
</div>

@include('eieol_lesson.modal_attach_gloss')
@include('eieol_lesson.modal_edit_gloss')
@include('eieol_lesson.modal_attach_head_word')
@include('eieol_lesson.modal_edit_head_word')
@include('eieol_lesson.confirm_delete_glosssed_text')
@include('eieol_lesson.confirm_delete_glossed_text_gloss')
@include('eieol_lesson.confirm_delete_grammar')


<!-- ---------------------------------------------------------------------------------------- -->  

<div class='col-lg-12'>
 
    <h1><i class='fa fa-file-text'></i> Edit Lesson for <a href="/admin2/eieol_series/{{$lesson->series->id}}/edit" title="Return to series">{{$lesson->series->title}}</a></h1>
    <p><a href="/guides/eieol_author" target=_new>Author Guide</a></p>
    <p><a href="../../../eieol_lesson/{{$lesson->series->id}}?id={{$lesson->id}}" target="_blank">Preview</a></p>
    <div class='bg-danger alert'>
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
    
    {{ Form::model($lesson, ['role' => 'form', 
    						 'url' => '/admin2/eieol_lesson/' . $lesson->id, 
    						 'method' => 'PUT', 
    						 'class' => 'form ajax_form',
    						 'id' => 'update_form'
    						 ]) }}
		
		{{ Form::hidden('series_id', $lesson->series->id) }}
		
		<div class='row'>
			<div class='col-sm-1'></div>
			
			<div class='form-group col-sm-1 '>
		        {{ Form::label('order', 'Order') }}
		        {{ Form::text('order', null, ['placeholder' => 'Order', 'class' => 'form-control']) }}
		        <div id ="order_error" class="alert-danger errors"></div>
		    </div>
		    	
		    <div class='form-group col-sm-3'>
		        {{ Form::label('title', 'Title') }}
		        {{ Form::text('title', null, ['placeholder' => 'Title', 'class' => 'form-control custom-keyboard']) }}
		        <div id ="title_error" class="alert-danger errors"></div>
		    </div>
		    
		    <div class='form-group col-sm-2'>
		        {{ Form::label('language', 'Language') }}<br/>
		        {{ Form::select('language', $languages, $lesson->language_id, ['class' => 'form-control', 'placeholder'=>'Select a language']) }}
		        <div id ="language_error" class="alert-danger errors"></div>
		    </div>
		    
		    <div class='form-group col-sm-2 comment_button'>
		    	<i class="fa fa-comment-o"></i>
		    </div>
		    
		 </div>
		    
	    <div class="row comment_rows">
	    	@if (!Auth::user()->isAdmin() || $lesson->author_comments || $lesson->author_done)
	    		<!-- only show if you are not an admin, or if they were filled in. -->
			    <div class='form-group col-sm-9 col-sm-offset-1'>
			    	{{ Form::label('author_comments', 'Author Comments') }}
				    {{ Form::textarea('author_comments', null, ['class' => 'form-control comment_textarea author_comments', 'size' => '100x2']) }}
				</div>
				
				<div class='form-group col-sm-1'>
			    	{{ Form::label('author_done', 'Done') }}
                    <input class="form-control author_done" name="author_done"
                           type="checkbox" value="1" id="author_done"
                           checked="{{$lesson->author_done?'checked':''}}">
				</div>
			@endif
		 
			@if (Auth::user()->isAdmin())
				<div class='form-group col-sm-9 col-sm-offset-1'>
			 		{{ Form::label('admin_comment', 'Admin Comments') }}	  
			    	{{ Form::textarea('admin_comments', null, ['class' => 'form-control comment_textarea admin_comments', 'size' => '100x2']) }}
			    </div>
			    
			    <div class='form-group col-sm-1'>
		    		{{ Form::submit('Clear', ['class' => 'btn btn-xs btn-warning comment_clear']) }}
			    </div>
		    @else
		    	@if ($lesson->admin_comments)
		    		<!-- Only show admin comments to authors if they exist -->
				    <div class='form-group col-sm-9 col-sm-offset-1'>
				        {{ Form::label('admin_comment', 'Admin Comments') }}	
				    	{{ Form::hidden('admin_comments', null, ['class' => 'form-control']) }}
				    	<div class="well" style="white-space: pre-wrap" >{{$lesson->admin_comments}}</div>
				    </div>
				@endif
			@endif
		</div>
			    	
		<div class='row'>	    	
		    <div class='form-group col-sm-10 col-sm-offset-1'>
		        {{ Form::label('intro_text', 'Intro Text') }}
		        {{ Form::textarea('intro_text', null, ['placeholder' => 'Intro Text', 'class' => 'form-control', 'size' => '100x10']) }}
		        <div id ="intro_text_error" class="alert-danger errors"></div>
		        {{ Form::submit('Edit', ['class' => 'btn btn-xs btn-primary']) }}
                <button type="button" class="btn btn-xs" onclick="previewText('intro_text')">Preview</button>
		    </div>
		</div>		    

    
    {{ Form::close() }}
    

    
    
    
    <!-- ---------------------------------------------------------------------------------------- -->  
    
    
    <hr/>
    <h2>Glossed Texts</h2>
    
    <div id ="glossed_texts">
	    @foreach ($glossed_texts as $glossed_text)
	    	<div id = 'glossed_text_div_{{$glossed_text->id}}'>
	    
	          {{ Form::model($glossed_text, ['role' => 'form',
			    					   'url' => '/admin2/eieol_glossed_text/' . $glossed_text->id, 
			    					   'method' => 'PUT', 
			    					   'class' => 'form ajax_form glossed_text_form',
			    					   'id' => 'glossed_text_form_' . $glossed_text->id
			    					  ]) }}
					
					<div class='row'>
						<div class='col-sm-1'></div>
						
						<div class='form-group col-sm-1 '>
					        {{ Form::label('order', 'Order') }}
					        {{ Form::text('order', null, ['placeholder' => 'Order', 'class' => 'form-control', 'id' => 'order']) }}
					        <div id ="order_error" class="alert-danger errors"></div>
					    </div>
					    	
					    <div class='form-group col-sm-7'>
					        {{ Form::label('glossed_text', 'Glossed Text') }}
					        {{ Form::textarea('glossed_text', null, ['placeholder' => 'Glossed Text', 'class' => 'form-control glossed_text_area custom-keyboard', 'size' => '100x3', 'id' => 'glossed_text_' . $glossed_text->id, 'lang'=>$lesson->language->lang_attribute]) }}
					        <div id ="glossed_text_error" class="alert-danger errors"></div>
					    </div>	    
					    
					    <div class='form-group col-sm-1 comment_button'>
							<i class="fa fa-comment-o"></i>
						</div>
					    
					    <div class='form-group col-sm-1 bottom_button'>
						    {{ Form::submit('Edit', ['class' => 'btn btn-xs btn-primary']) }}
						</div>
					
			    		<div class='form-group col-sm-1 bottom_button'>
			            	{{ Form::button('Delete', ['class' => 'btn btn-xs btn-danger delete_glossed_text'])}}    
						</div>
	
				    </div>
				    
				    
				    <div class="row comment_rows">
				    	@if (!Auth::user()->isAdmin() || $glossed_text->author_comments || $glossed_text->author_done)
				    		<!-- only show if you are not an admin, or if they were filled in. -->
						    <div class='form-group col-sm-9 col-sm-offset-1'>
						    	{{ Form::label('author_comments', 'Author Comments') }}
							    {{ Form::textarea('author_comments', null, ['class' => 'form-control comment_textarea author_comments', 'size' => '100x2']) }}
							</div>
							
							<div class='form-group col-sm-1'>
						    	{{ Form::label('author_done', 'Done') }}
                                <input class="form-control author_done" name="author_done"
                                       type="checkbox" value="1" id="author_done"
                                       checked="{{$glossed_text->author_done?'checked':''}}">
							</div>
						@endif
					 
						@if (Auth::user()->isAdmin())
							<div class='form-group col-sm-9 col-sm-offset-1'>
						 		{{ Form::label('admin_comment', 'Admin Comments') }}	  
						    	{{ Form::textarea('admin_comments', null, ['class' => 'form-control comment_textarea admin_comments', 'size' => '100x2']) }}
						    </div>
						    
						    <div class='form-group col-sm-1'>
					    		{{ Form::submit('Clear', ['class' => 'btn btn-xs btn-warning comment_clear']) }}
						    </div>
					    @else
					    	@if ($glossed_text->admin_comments)
					    		<!-- Only show admin comments to authors if they exist -->
							    <div class='form-group col-sm-9 col-sm-offset-1'>
							        {{ Form::label('admin_comment', 'Admin Comments') }}	
							    	{{ Form::hidden('admin_comments', null, ['class' => 'form-control']) }}
							    	<div class="well" style="white-space: pre-wrap" >{{$glossed_text->admin_comments}}</div>
							    </div>
							@endif
						@endif
					</div>
			    
			    {{ Form::close() }}
			    
			    <div class="row">
			    <div class='col-sm-2'></div>
			    <button class="togglegloss btn btn-xs btn-default">Toggle glosses</button>
			    </div>
			    </div>
			    
			    <div class='lotsagloss'>
			    
			    <p/>
			    <div id="glossed_text_{{$glossed_text->id}}_glosses">
			    
				    @foreach ($glossed_text->glosses as $gloss)
					  <div id="glossed_text_gloss_{{$gloss->pivot->id}}_div">
					  
						<div class='row'>
							<div class='col-sm-2'></div>

							<div class='form-group col-sm-1 '>
								{{ Form::model($gloss, ['role' => 'form',
				    					   'url' => '/admin2/eieol_glossed_text_gloss/' . $gloss->pivot->id,
				    					   'method' => 'PUT',
				    					   'class' => 'form ajax_form',
				    					   'id' => 'glossed_text_gloss_form_' . $gloss->pivot->id
				    					  ]) }}
								{{ Form::hidden('glossed_text_id', $glossed_text->id, ['id' => 'glossed_text_id']) }}
						        {{ Form::label('order', 'Order') }}
						        {{ Form::text('order', $gloss->pivot->order, ['placeholder' => 'Order', 'class' => 'form-control']) }}
						        <div id ="order_error" class="alert-danger errors"></div>
						    </div>
						    
						    <div class='form-group col-sm-1 bottom_button'>
							    {{ Form::submit('Edit Order', ['class' => 'btn btn-xs btn-primary']) }}
							    {{ Form::close() }}
							</div>

						    <div class='col-sm-4 gloss_{{$gloss->id}}'>
						    	<br/>
						    	{!! $gloss->getDisplayGloss() !!}
			    			</div>
			    			
			    			<div class='col-sm-1 bottom_button gloss_comment_indicator'>
			    				@if ($gloss->author_done)
			    					<div style="color:green"><i class="fa fa-comments"></i></div>
			    				@elseif ($gloss->author_comments || $gloss->admin_comments)
			    					<div style="color:red"><i class="fa fa-comments"></i></div>
			    				@endif
			    			</div>
			    			
			    			<div class='col-sm-1 bottom_button'>
			    				{{ Form::open(['class' => 'edit_gloss', 
			    							   'id' => 'edit_gloss_form_' . $gloss->pivot->id]) }} 
			    					{{ Form::hidden('gloss_id', $gloss->id, ['id' => 'gloss_id']) }}
			    					{{ Form::submit('Edit Gloss', ['class' => 'btn btn-xs btn-primary']) }}
			    				{{ Form::close() }}
			    			</div>
			    			
			    			<div class='form-group col-sm-1 bottom_button'>
			    				{{ Form::open(['class' => 'delete_glossed_text_gloss_form',
			    							   'url' => '/admin2/eieol_glossed_text_gloss/' . $gloss->pivot->id,
			    							   'id' => 'delete_glossed_text_gloss_form_' . $gloss->pivot->id]) }} 
				            		{{ Form::hidden('glossed_text_gloss_id', $gloss->pivot->id, ['id' => 'glossed_text_gloss_id']) }}
				            		{{ Form::button('Remove', ['class' => 'btn btn-xs btn-danger delete_glossed_text_gloss'])}}   
				            	{{ Form::close() }} 
							</div>
						      
					    </div>
					  </div>
				    @endforeach
			    
			    </div>
			   
			    <!-- this will open a modal to attach a gloss to the glossed text --> 
			    <div class='row'>
			   		<div class='col-sm-2'></div>
			   		<div class='form-group col-sm-1 '> 
			   			{{ Form::open(['class' => 'attach_gloss_form',
			   						   'id' => 'attach_gloss_form_' . $glossed_text->id]) }} 
			   				{{ Form::hidden('glossed_text_id', $glossed_text->id, ['id' => 'glossed_text_id']) }}
				    		{{ Form::submit('Attach Gloss', ['class' => 'btn btn-xs btn-success']) }}
				    	{{ Form::close() }}
				    </div>
				</div>
				
				  </div>

			    <hr/>
			</div>
	    @endforeach
    </div>
    
    <!-- This is the template for adding new glossed text.  It is not used, but cloned when we want to add a new one -->
    <div id="new_glossed_text_div" style="display: none">
	    {{ Form::open(['role' => 'form',
		    		   'url' => '/admin2/eieol_glossed_text/', 
		    		   'class' => 'form ajax_form glossed_text_form',
		    		   'id' => 'new_glossed_text_form'  
		    		  ]) }}
		    	
		    	{{ Form::hidden('lesson_id', $lesson->id) }}
		    	
				<div class='row'>
					<div class='col-sm-1'></div>
					
					<div class='form-group col-sm-1'>
				        {{ Form::label('order', 'Order') }}
				        {{ Form::text('order', null, ['placeholder' => 'Order', 'class' => 'form-control', 'id' => 'order']) }}
				        <div id ="order_error" class="alert-danger errors"></div>
				    </div>
				    
				    <div class='form-group col-sm-7'>
				        {{ Form::label('glossed_text', 'Glossed Text') }}
				        {{ Form::textarea('glossed_text', null, ['placeholder' => 'Glossed Text', 'class' => 'form-control','size' => '100x10', 'id' => 'new_glossed_text']) }}
				        <div id ="glossed_text_error" class="alert-danger errors"></div>
				    </div>	     
				    
				    <div class='form-group col-sm-1 comment_button'>
						<i class="fa fa-comment-o"></i>
					</div>
				    
				    <div class='form-group col-sm-1 bottom_button'> 
				    	{{ Form::submit('Add', ['class' => 'btn btn-xs btn-success']) }}
				    </div>
				    
				    <div class='form-group col-sm-1 bottom_button'>
		            	{{ Form::button('Delete', ['class' => 'btn btn-xs btn-danger delete_glossed_text', 'style' => 'display: none'])}}    
					</div>
			    </div>
			    
			    <div class="row comment_rows">
			    	@if (!Auth::user()->isAdmin())
			    		<!-- only show if you are not an admin, or if they were filled in. -->
					    <div class='form-group col-sm-9 col-sm-offset-1'>
					    	{{ Form::label('author_comments', 'Author Comments') }}
						    {{ Form::textarea('author_comments', null, ['class' => 'form-control comment_textarea author_comments', 'size' => '100x2']) }}
						</div>
						
						<div class='form-group col-sm-1'>
					    	{{ Form::label('author_done', 'Done') }}
                            <input class="form-control author_done" name="author_done"
                                   type="checkbox" value="1" id="author_done">
						</div>
					@endif
				 
					@if (Auth::user()->isAdmin())
						<div class='form-group col-sm-9 col-sm-offset-1'>
					 		{{ Form::label('admin_comment', 'Admin Comments') }}	  
					    	{{ Form::textarea('admin_comments', null, ['class' => 'form-control comment_textarea admin_comments', 'size' => '100x2']) }}
					    </div>
					    
					    <div class='form-group col-sm-1'>
				    		{{ Form::submit('Clear', ['class' => 'btn btn-xs btn-warning comment_clear']) }}
					    </div>
					@endif
				</div>
			    
			    
		    
		    {{ Form::close() }}
		    
		    <div id="new_glossed_text_glosses">
		    </div>
		    
		    <!-- this will open a modal to attach a gloss to the glossed text --> 
		    <div class='row'>
		   		<div class='col-sm-2'></div>
		   		<div class='form-group col-sm-1 '> 
		   			{{ Form::open(['class' => 'attach_gloss_form', 'id' => 'attach_gloss_form']) }} 
		   				{{ Form::hidden('glossed_text_id', 0, ['id' => 'glossed_text_id']) }} 
			    		{{ Form::submit('Attach Gloss', ['class' => 'btn btn-xs btn-success', 'id' => 'attach_gloss_button', 'style' => 'display: none']) }}
			    	{{ Form::close() }}
			    </div>
			</div>
		    
		    
		    
		    <hr/>
	  </div>
	  
	  
	  <!-- Button that will clone the new glossed text template -->
	  <div class='row'>
	  	<div class='col-sm-1'></div>
	  	<div class="col-sm-1">
	  		<a class="btn btn-xs btn-success" id="add_glossed_text">Create New Glossed Text</a>
	  	</div>
	  </div>
	  
	  
	  
    
    
    	<!-- This is the template for adding new glossed_text_GLOSS.  It is not used, but cloned when we want to add a new one -->
    	<div id="new_glossed_text_gloss_div" style="display: none">
	    	{{ Form::open(['role' => 'form',
		   		   'url' => '/admin2/eieol_glossed_text_gloss/', 
		   		   'class' => 'form ajax_form',
		   		   'id' => 'new_glossed_text_gloss_form'
		   		  ]) }} 
		    	
		   		{{ Form::hidden('_method', 'PUT') }}
		   		{{ Form::hidden('glossed_text_id', 0, ['id' => 'glossed_text_id']) }}
		   		<div class='row'>
					<div class='col-sm-2'></div>
					
					<div class='form-group col-sm-1 '>
				        {{ Form::label('order', 'Order') }}
				        {{ Form::text('order', null, ['placeholder' => 'Order', 'class' => 'form-control', 'id' => 'order']) }}
				        <div id ="order_error" class="alert-danger errors"></div>
				    </div>
				    
				    <div class='form-group col-sm-1 bottom_button'>
					    {{ Form::submit('Edit Order', ['class' => 'btn btn-xs btn-primary']) }}
					    {{ Form::close() }}
					</div>
				    	
				    <div class='col-sm-4 gloss_text'>
		   			</div>
		   			
		   			<div class='col-sm-1 bottom_button gloss_comment_indicator'>
		   			</div>   
		   			
		   			<div class='col-sm-1 bottom_button'>
	    				{{ Form::open(['class' => 'edit_gloss', 'id' => 'edit_gloss']) }} 
	    					{{ Form::hidden('gloss_id', null, ['id' => 'gloss_id']) }}
	    					{{ Form::submit('Edit Gloss', ['class' => 'btn btn-xs btn-primary']) }}
	    				{{ Form::close() }}
	    			</div>
				    
				    <div class='form-group col-sm-1 bottom_button'>
				    	{{ Form::open(['class' => 'delete_glossed_text_gloss_form',
	    							   'url' => '/admin2/eieol_glossed_text_gloss/', 
	    							   'id' => 'delete_gloss']) }} 
	    					{{ Form::hidden('glossed_text_gloss_id', null, ['id' => 'glossed_text_gloss_id']) }}
		            		{{ Form::button('Remove', ['class' => 'btn btn-xs btn-danger delete_glossed_text_gloss', 'style' => 'display: none'])}}  
		            	{{ Form::close() }} 
					</div>
					  
			    </div>
		    
    	</div>
    
    <!-- ---------------------------------------------------------------------------------------- -->
    
    
    <hr/>
    
    <h2>Text and Translation</h2>
    
    <div class='row'>
		<div class='col-sm-10 col-sm-offset-1'>
	        <strong>Lesson Text</strong> 
	        <div class="well" id="lesson_text">
	        </div>
	    </div>
	    <br/>
    </div>
    
    
    {{ Form::model($lesson, ['role' => 'form', 
    						 'url' => '/admin2/eieol_lesson/update_translation/' . $lesson->id, 
    						 'method' => 'PUT', 
    						 'class' => 'form ajax_form',
    						 'id' => 'update_translation_form'
    						 ]) }}
		    
		<div class='row'>
			<div class='form-group col-sm-10 col-sm-offset-1'>
		        {{ Form::label('lesson_translation', 'Lesson Translation') }}
		        {{ Form::textarea('lesson_translation', null, ['placeholder' => 'Lesson Translation', 'class' => 'form-control', 'size' => '100x10']) }}
		        <div id ="lesson_translation_error" class="alert-danger errors"></div>
		    </div>
		    <div class='form-group col-sm-1 comment_button'>
				<i class="fa fa-comment-o"></i>
			</div>
	    </div>
	    <div class="row comment_rows">
	    	@if (!Auth::user()->isAdmin() || $lesson->translation_author_comments || $lesson->translation_author_done)
	    		<!-- only show if you are not an admin, or if they were filled in. -->
			    <div class='form-group col-sm-9 col-sm-offset-1'>
			    	{{ Form::label('translation_author_comments', 'Author Comments') }}
				    {{ Form::textarea('translation_author_comments', null, ['class' => 'form-control comment_textarea author_comments', 'size' => '100x2']) }}
				</div>
				
				<div class='form-group col-sm-1'>
			    	{{ Form::label('translation_author_done', 'Done') }}
                    <input class="form-control author_done" name="translation_author_done"
                           type="checkbox" value="1" id="translation_author_done"
                           checked="{{$lesson->translation_author_done?'checked':''}}">
				</div>
			@endif
		 
			@if (Auth::user()->isAdmin())
				<div class='form-group col-sm-9 col-sm-offset-1'>
			 		{{ Form::label('translation_admin_comment', 'Admin Comments') }}	  
			    	{{ Form::textarea('translation_admin_comments', null, ['class' => 'form-control comment_textarea admin_comments', 'size' => '100x2']) }}
			    </div>
			    
			    <div class='form-group col-sm-1'>
		    		{{ Form::submit('Clear', ['class' => 'btn btn-xs btn-warning comment_clear']) }}
			    </div>
		    @else
		    	@if ($lesson->translation_admin_comments)
		    		<!-- Only show admin comments to authors if they exist -->
				    <div class='form-group col-sm-9 col-sm-offset-1'>
				        {{ Form::label('translation_admin_comment', 'Admin Comments') }}	
				    	{{ Form::hidden('translation_admin_comments', null, ['class' => 'form-control']) }}
				    	<div class="well" style="white-space: pre-wrap" >{{{$lesson->translation_admin_comments}}}</div>
				    </div>
				@endif
			@endif
		</div>
					
	    <div class='row'>
	    	<div class='form-group col-sm-2 col-sm-offset-1'>
	    		{{ Form::submit('Edit Translation', ['class' => 'btn btn-xs btn-primary']) }}
                <button type="button" class="btn btn-xs" onclick="previewText('lesson_translation')">Preview</button>
            </div>
	    </div>
	    
	{{ Form::close() }}
	
	
	<!-- ---------------------------------------------------------------------------------------- -->
	
	
	<hr/>
    <h2>Grammar</h2>	
    <div id ="grammars">
	    @foreach ($grammars as $grammar)
	          {{ Form::model($grammar, ['role' => 'form',
			    					   'url' => '/admin2/eieol_grammar/' . $grammar->id, 
			    					   'method' => 'PUT', 
			    					   'class' => 'form ajax_form grammar_form',
			    					   'id' => 'grammar_form_' . $grammar->id
			    					  ]) }}
					
					<div class='row'>
						<div class='col-sm-1'></div>
						
						<div class='form-group col-sm-1 '>
					        {{ Form::label('order', 'Order') }}
					        {{ Form::text('order', null, ['placeholder' => 'Order', 'class' => 'form-control', 'id' => 'order']) }}
					        <div id ="order_error" class="alert-danger errors"></div>
					    </div>
					    
					    <div class='form-group col-sm-1 '>
					        {{ Form::label('section_number', 'Section Number') }}
					        {{ Form::text('section_number', null, ['placeholder' => 'Section Number', 'class' => 'form-control']) }}
					        <div id ="section_number_error" class="alert-danger errors"></div>
					    </div>
					    	
					    <div class='form-group col-sm-3'>
					        {{ Form::label('title', 'Title') }}
					        {{ Form::text('title', null, ['placeholder' => 'Title', 'class' => 'form-control custom-keyboard']) }}
					        <div id ="title_error" class="alert-danger errors"></div>
					    </div>
					    
						<div class='form-group col-sm-1 comment_button'>
					    	<i class="fa fa-comment-o"></i>
					    </div>
				    
				   </div>
				    
				    <div class="row comment_rows">
				    	@if (!Auth::user()->isAdmin() || $grammar->author_comments || $grammar->author_done)
				    		<!-- only show if you are not an admin, or if they were filled in. -->
						    <div class='form-group col-sm-9 col-sm-offset-1'>
						    	{{ Form::label('author_comments', 'Author Comments') }}
							    {{ Form::textarea('author_comments', null, ['class' => 'form-control comment_textarea author_comments', 'size' => '100x2']) }}
							</div>
							
							<div class='form-group col-sm-1'>
						    	{{ Form::label('author_done', 'Done') }}
                                <input class="form-control author_done" name="author_done" type="checkbox"
                                       value="1" id="author_done"
                                       checked="{{$grammar->author_done?'checked':''}}">
							</div>
						@endif
					 
						@if (Auth::user()->isAdmin())
							<div class='form-group col-sm-9 col-sm-offset-1'>
						 		{{ Form::label('admin_comment', 'Admin Comments') }}	  
						    	{{ Form::textarea('admin_comments', null, ['class' => 'form-control comment_textarea admin_comments', 'size' => '100x2']) }}
						    </div>
						    
						    <div class='form-group col-sm-1'>
					    		{{ Form::submit('Clear', ['class' => 'btn btn-xs btn-warning comment_clear']) }}
						    </div>
					    @else
					    	@if ($grammar->admin_comments)
					    		<!-- Only show admin comments to authors if they exist -->
							    <div class='form-group col-sm-9 col-sm-offset-1'>
							        {{ Form::label('admin_comment', 'Admin Comments') }}	
							    	{{ Form::hidden('admin_comments', null, ['class' => 'form-control']) }}
							    	<div class="well" style="white-space: pre-wrap" >{{$grammar->admin_comments}}</div>
							    </div>
							@endif
						@endif
					</div>
					
					
					<div class='row'>    
					    <div class='form-group col-sm-10 col-sm-offset-1'>
					        {{ Form::label('grammar_text', 'Grammar Text') }}
					        {{ Form::textarea('grammar_text', null, ['placeholder' => 'Grammar Text', 'class' => 'form-control', 'size' => '100x10', 'id' => 'grammar_text_' . $grammar->id]) }}
					        <div id ="grammar_text_error" class="alert-danger errors"></div>
					        
					    </div>	
					</div>
					
					<div class='row'>
			    		<div class='form-group col-sm-1 '></div>
			    		<div class='form-group col-sm-2 '>
			    			{{ Form::submit('Edit', ['class' => 'btn btn-xs btn-primary']) }}
                            <button type="button" class="btn btn-xs" onclick="previewText('grammar_text_{{$grammar->id}}')">Preview</button>

                        </div>
			    		
			    		<div class='form-group col-sm-8 '></div>
			    		<div class='form-group col-sm-1 '>
			            	{{ Form::button('Delete', ['class' => 'btn btn-xs btn-danger delete_grammar'])}}    
						</div>
				    </div>
			    {{ Form::close() }}
			    
			    <hr/>
	    @endforeach
    </div>
    
    <!-- This is the template for adding new grammars.  It is not used, but cloned when we want to add a new one -->
    <div id="new_grammar_div" style="display: none">
	    {{ Form::open(['role' => 'form',
		    		   'url' => '/admin2/eieol_grammar/', 
		    		   'class' => 'form ajax_form grammar_form',
		    		   'id' => 'new_grammar_form'
		    		  ]) }} 
		    	
		    	{{ Form::hidden('lesson_id', $lesson->id) }}
		    	
				<div class='row'>
					<div class='col-sm-1'></div>
					
					<div class='form-group col-sm-1'>
				        {{ Form::label('order', 'Order') }}
				        {{ Form::text('order', null, ['placeholder' => 'Order', 'class' => 'form-control', 'id' => 'order']) }}
				        <div id ="order_error" class="alert-danger errors"></div>
				    </div>
				    
				    <div class='form-group col-sm-1'>
				        {{ Form::label('section_number', 'Section Number') }}
				        {{ Form::text('section_number', null, ['placeholder' => 'Section Number', 'class' => 'form-control']) }}
				        <div id ="section_number_error" class="alert-danger errors"></div>
				    </div>
				    	
				    <div class='form-group col-sm-3'>
				        {{ Form::label('title', 'Title') }}
				        {{ Form::text('title', null, ['placeholder' => 'Title', 'class' => 'form-control custom-keyboard']) }}
				        <div id ="title_error" class="alert-danger errors"></div>
				    </div>
				    
					<div class='form-group col-sm-1 comment_button'>
				    	<i class="fa fa-comment-o"></i>
				    </div>
			    
			   </div>
			    
			    <div class="row comment_rows">
			    	@if (!Auth::user()->isAdmin())
			    		<!-- only show if you are not an admin, or if they were filled in. -->
					    <div class='form-group col-sm-9 col-sm-offset-1'>
					    	{{ Form::label('author_comments', 'Author Comments') }}
						    {{ Form::textarea('author_comments', null, ['class' => 'form-control comment_textarea author_comments', 'size' => '100x2']) }}
						</div>
						
						<div class='form-group col-sm-1'>
					    	{{ Form::label('author_done', 'Done') }}
                            <input class="form-control author_done" name="author_done" type="checkbox"
                                   value="1" id="author_done">
						</div>
					@endif
				 
					@if (Auth::user()->isAdmin())
						<div class='form-group col-sm-9 col-sm-offset-1'>
					 		{{ Form::label('admin_comment', 'Admin Comments') }}	  
					    	{{ Form::textarea('admin_comments', null, ['class' => 'form-control comment_textarea admin_comments', 'size' => '100x2']) }}
					    </div>
					    
					    <div class='form-group col-sm-1'>
				    		{{ Form::submit('Clear', ['class' => 'btn btn-xs btn-warning comment_clear']) }}
					    </div>
					@endif
				</div>
			
				<div class='row'>
				    
				    <div class='form-group col-sm-10 col-sm-offset-1'>
				        {{ Form::label('grammar_text', 'Grammar Text') }}
				        {{ Form::textarea('grammar_text', null, ['placeholder' => 'Grammar Text', 'class' => 'form-control', 'size' => '100x10', 'id' => 'new_grammar_text']) }}
				        <div id ="grammar_text_error" class="alert-danger errors"></div>
				        
				    </div>		    
		
			    </div>
			    
			    <div class='row'>
		    		<div class='form-group col-sm-1 '></div>
		    		<div class='form-group col-sm-1 '>
		    			{{ Form::submit('Add', ['class' => 'btn btn-xs btn-success']) }}
				        
		    		</div>
		    		
		    		<div class='form-group col-sm-8 '></div>
		    		<div class='form-group col-sm-1 '>
		            	{{ Form::button('Delete', ['class' => 'btn btn-xs btn-danger delete_grammar', 'style' => 'display: none'])}}    
					</div>
			    </div>
		    
		    {{ Form::close() }}
                        
		    <hr/>
	  </div>
	  
	  
	  <!-- Button that will clone the new grammar template -->
	  <div class='row'>
	  	<div class='col-sm-1'></div>
	  	<div class="col-sm-1">
	  		<a class="btn btn-xs btn-success" id="add_grammar">Create New Grammar</a>
	  	</div>
	  </div>
    
</div>


<!-- ---------------------------------------------------------------------------------------- -->

<script>
	CKEDITOR.plugins.addExternal( 'onchange', '/js/', 'onchangeplugin.js' );
	CKEDITOR.plugins.addExternal( 'eieol_language', '/ckeditor-plugins/eieol_language/');
	ckeditor_parms = {
			  toolbar : $mytoolbar,
			  language_list :
        [
            @foreach ($series_languages as $series_language)	
            '{{$series_language}}',
            @endforeach
        ],
			  contentsCss : '/css/lrcstyle.css',
			  disableNativeSpellChecker : false, 
			  allowedContent : true, 
			  extraPlugins : 'onchange,eieol_language',
			  language_lang : '{{$lesson->language->lang_attribute}}',
			  specialChars : [ {!! $lesson->language->custom_keyboard_layout !!}],
			  enterMode : 'CKEDITOR.ENTER_BR',
			  entities : false
			};
	glossed_text_ckeditor_parms = jQuery.extend(true, {}, ckeditor_parms); //deep copy
	glossed_text_ckeditor_parms['height'] = '4em';

	//apply the ckeditor to the intro text
	CKEDITOR.replace('intro_text',ckeditor_parms);
	CKEDITOR.instances['intro_text'].on('change', function() {
		if(this.checkDirty()) {
			$('#update_form').css("background-color", "#EBAD99");
			$('#update_form').attr("dirty", "dirty");
		}
	});

	//apply the ckeditor to the translation
	CKEDITOR.replace('lesson_translation',ckeditor_parms);
	CKEDITOR.instances['lesson_translation'].on('change', function() {
		if(this.checkDirty()) {
			$('#update_translation_form').css("background-color", "#EBAD99"); 
			$('#update_translation_form').attr("dirty", "dirty");
		}
	});

	//apply the ckeditor to each exisiting grammar
	@foreach ($grammars as $grammar)
		CKEDITOR.replace('grammar_text_{{{$grammar->id}}}', ckeditor_parms);
		CKEDITOR.instances['grammar_text_{{{$grammar->id}}}'].on('change', function() {
			if(this.checkDirty()) {
				$('#grammar_form_{{{$grammar->id}}}').css("background-color", "#EBAD99");
				$('#grammar_form_{{{$grammar->id}}}').attr("dirty", "dirty");
			}
		});
	@endforeach
</script>

<!-- This has to be defined after any other modals so it will show up if in a modal -->
<div id="update_confirm" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Update Confirmation</h4>
            </div>
            <div class="modal-body" id="success_message">
                Update was successful
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="preview_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Preview</h4>
            </div>
            <div class="modal-body">
                <p id="preview_modal_body"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
 
@stop
