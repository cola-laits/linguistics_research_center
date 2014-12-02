@extends('admin_layout')
 
@section('title') Edit Lesson @stop
 
@section('content')

<script type="text/javascript">

	function generate_lesson_text() {
		var lesson_text = '';
		$("form", "#glossed_texts").each(function() {
			lesson_text += $('#glossed_text', this).val() + ' ';
		});
		$('#lesson_text').html(lesson_text);
	} //generate_lesson_text

	function ajax_submit(myform) { 
		//generic ajax function.   This will prevent the regular submission and send it by ajax instead.

		$(".spinner").show();

		//get values from CKEditor and pu them into textarea fields
		for ( instance in CKEDITOR.instances )
		    CKEDITOR.instances[instance].updateElement();

		//we need to know which form we're working with
		var formDiv = "#"+myform.attr('id');
		
	    //hide any previous error messages
	    $(".errors", formDiv).empty();
	    
	    //function that handles ajax form submission
		$.ajax({
			type: "POST",
	        url:myform.attr('action'),
	        data:myform.serialize(),
	        dataType: "html",
	        
	        success : function(data){
		        var json = JSON.parse(data);    
		        
	        	if(json['fail']) { //go through all errors and set error messages, just within this form
	  		      $.each(json['errors'], function( index, value ) {
	  		          var errorDiv = '#'+index+'_error';
	  		          $(errorDiv, formDiv).html(value);
	  		      });
	  		      $('#successMessage').empty();          
	  		    }  //json fail
	  		    
	  		    if(json['success']) { //briefly show a success popup and turn off form background
	  		        $('#success_messaage').html(json['message']);
	  		        $("#update_confirm").modal('show');
		  		    setTimeout(function(){
		  		        $("#update_confirm").modal('hide');
		  		    }, 1000);
	  		      	myform.css("background-color", "#FFFFFF");
	  		    } //json success

    		    if(json['added']) { //if we just performed an add, we need to change the form to an update form
        		    $(formDiv).find(":submit").attr('value','Edit');
        		    $(formDiv).find(":submit").attr('class', 'btn btn-primary');
        		    $(formDiv).attr("action", json['action']);
        		    $('<input>').attr({type: 'hidden', value: 'PUT', name: '_method'}).appendTo(formDiv);
    		    }  
    		  
    		    //rebuild lesson text
    			generate_lesson_text();

    			$(".spinner").hide(); 
	        }, //success
	        
	        error : function(xml_http_request, text_status, error_thrown) {
	        	alert('Ajax Error: ' + text_status + '/ ' + xml_http_request + '/ ' + error_thrown);
	        } //error

	    }); //ajax call
	} //ajax submit function


	//highlight forms if they are changed
	function highlight_form(){
		var my_form = $(this).closest('form');
		my_form.css("background-color", "#EBAD99");
	} //highlight form


	//ajax search for glosses
	function searchGlosses(gloss) {
		if (gloss.length==0) { 
			document.getElementById("gloss_search_result").innerHTML="";
		    document.getElementById("gloss_search_result").style.border="0px";
		    return;
		}
		xmlhttp=new XMLHttpRequest();
		xmlhttp.onreadystatechange=function() {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200) {
			  document.getElementById("gloss_search_result").innerHTML=xmlhttp.responseText;
			  document.getElementById("gloss_search_result").style.border="1px solid #A5ACB2"; 
		  }
		}
		xmlhttp.open("GET","/admin/eieol_gloss?gloss="+gloss,true);
		xmlhttp.send();
	}

	
    $(document).ready(function(){
		var grammar_ctr = 0;
		var glossed_text_ctr = 0;

		//build lesson text
		generate_lesson_text();
        
        //trigger highlight form if inputs change.  If you are using ckeditor, you have to do that with its on change function
        $(':input').keyup(highlight_form); //listen for typing
    	$(':input').change(highlight_form); //listen for clicking

    	//bind all ajax forms to our ajax function
    	$('.ajax_form').submit(function(){
    		ajax_submit($(this));
    		return false; // this keeps the form from submitting
    	});//submit

    	//popup to attach gloss
		$(".attach_gloss").click(function(e) {
		    e.preventDefault();
		    $("#gloss_search_input").val("");
		    $("#attach_gloss_modal").modal('show');
		    $("#gloss_search_input").focus();
		    document.getElementById("gloss_search_result").innerHTML="";
		    document.getElementById("gloss_search_result").style.border="0px";
		});

    	
    	//this clones the default add glossed text form 
    	$( "#add_glossed_text" ).click(function() {	
    		glossed_text_ctr ++;
    		var new_div_id = "new_glossed_text_div_" + glossed_text_ctr;
    		var new_form_id = "new_glossed_text_form_" + glossed_text_ctr;
    		
    		var new_div = $( "#new_glossed_text_div" ).clone(true).attr("id",new_div_id);
    		new_div.appendTo( "#glossed_texts" );
    		new_div.show();
    		
    		$('#new_glossed_text_form', '#'+new_div_id).attr("id",new_form_id);
    	});
    	

    	//this clones the default add grammar form and turns on the ckeditor for it
    	$( "#add_grammar" ).click(function() {	
    		grammar_ctr ++;
    		var new_div_id = "new_grammar_div_" + grammar_ctr;
    		var new_form_id = "new_grammar_form_" + grammar_ctr;
    		var new_text_id = "new_grammar_text_" + grammar_ctr;
    		
    		var new_div = $( "#new_grammar_div" ).clone(true).attr("id",new_div_id);
    		new_div.appendTo( "#grammars" );
    		new_div.show();
    		
    		$('#new_grammar_form', '#'+new_div_id).attr("id",new_form_id);
    		$('#new_grammar_text', '#'+new_form_id).attr("id",new_text_id);
    		
    		CKEDITOR.replace( new_text_id,{toolbar : $mytoolbar, contentsCss : '/css/lrcstyle.css', allowedContent : true, extraPlugins : 'onchange'}  );
    		CKEDITOR.instances[new_text_id].on('change', function() {
    			if(this.checkDirty())
    				$('#'+new_form_id).css("background-color", "#EBAD99");
    		});
    	});
		
    });//document ready

    
</script>
 
 
 <div class="spinner">
  {{ HTML::image('images/ajax_loader_red_350.gif', $alt="Loading", $attributes = array('border'=>0, 'width'=>150, 'height'=>150))  }}<br/>Please Wait...
</div>


<div id="update_confirm" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Update Confirmation</h4>
            </div>
            <div class="modal-body" id="success_messaage">
                Update was successful
            </div>
        </div>
    </div>
</div>

<div id="attach_gloss_modal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Attach Gloss</h4>
            </div>
            <div class="modal-body" id="success_messaage">
                {{ Form::label('gloss_search_input', 'Search Gloss') }}
		        {{ Form::text('gloss_search_input', null, ['placeholder' => 'Search Gloss', 'class' => 'form-control', 'onkeyup' => 'searchGlosses(this.value)']) }}
            	<div id="gloss_search_result"></div>
            </div>
        </div>
    </div>
</div>



<div class='col-lg-12'>
 
    <h1><i class='fa fa-file-text'></i> Edit Lesson for {{ HTML::link('admin/eieol_series/' . $series->id . '/edit', $series->title , array('title' => 'Return to series' )) }}</h1>
    <div class='bg-danger alert'>
    	If you change the order of items on this page, they will not appear in that order until you refresh the page.
    </div>
    
    {{ Form::model($lesson, ['role' => 'form', 
    						 'url' => '/admin/eieol_lesson/' . $lesson->id, 
    						 'method' => 'PUT', 
    						 'class' => 'form ajax_form',
    						 'id' => 'update_form'
    						 ]) }}
		
		{{ Form::hidden('series_id', $series->id) }}
		
		<div class='row'>
			<div class='col-sm-1'></div>
			
			<div class='form-group col-sm-1 '>
		        {{ Form::label('order', 'Order') }}
		        {{ Form::text('order', null, ['placeholder' => 'Order', 'class' => 'form-control']) }}
		        <div id ="order_error" class="alert-danger errors"></div>
		    </div>
		    	
		    <div class='form-group col-sm-3'>
		        {{ Form::label('title', 'Title') }}
		        {{ Form::text('title', null, ['placeholder' => 'Title', 'class' => 'form-control']) }}
		        <div id ="title_error" class="alert-danger errors"></div>
		    </div>
		    
		    <br/>
		    
		    <div class='form-group col-sm-10 col-sm-offset-1'>
		        {{ Form::label('intro_text', 'Intro Text') }}
		        {{ Form::textarea('intro_text', null, ['placeholder' => 'Intro Text', 'class' => 'form-control', 'size' => '100x10']) }}
		        <div id ="intro_text_error" class="alert-danger errors"></div>
		        {{ Form::submit('Edit', ['class' => 'btn btn-primary']) }}
		    </div>		    

	    </div>
    
    {{ Form::close() }}
    
    <hr/>
    <h2>Glossed Texts</h2>
    
    
    
    <!-- ---------------------------------------------------------------------------------------- -->
    
    
    
    
    <div id ="glossed_texts">
	    @foreach ($glossed_texts as $glossed_text)
	          {{ Form::model($glossed_text, ['role' => 'form',
			    					   'url' => '/admin/eieol_glossed_text/' . $glossed_text->id, 
			    					   'method' => 'PUT', 
			    					   'class' => 'form ajax_form',
			    					   'id' => 'glossed_text_form_' . $glossed_text->id
			    					  ]) }}
					
					<div class='row'>
						<div class='col-sm-1'></div>
						
						<div class='form-group col-sm-1 '>
					        {{ Form::label('order', 'Order') }}
					        {{ Form::text('order', null, ['placeholder' => 'Order', 'class' => 'form-control']) }}
					        <div id ="order_error" class="alert-danger errors"></div>
					    </div>
					    	
					    <div class='form-group col-sm-8'>
					        {{ Form::label('glossed_text', 'Glossed Text') }}
					        {{ Form::text('glossed_text', null, ['placeholder' => 'Glossed Text', 'class' => 'form-control', 'id' => 'glossed_text']) }}
					        <div id ="glossed_text_error" class="alert-danger errors"></div>
					    </div>	    
					    
					    <div class='form-group col-sm-1 '>
						    {{ Form::submit('Edit', ['class' => 'btn btn-primary']) }}
						</div>
				    </div>
			    
			    {{ Form::close() }}
			    
			    
			    @foreach ($glossed_text->glosses as $gloss)
			   		<div class='row'>
			   			<div class='col-sm-2'></div>
			   			<div class='col-sm-1'>
			   				{{{$gloss->pivot->order}}}
			   			</div>
			   			<div class='col-sm-8'>
			    			{{{$gloss->surface_form}}} -- {{{$gloss->part_of_speech}}}; {{$gloss->analysis}} {{{$gloss->head_word->word}}} {{{$gloss->head_word->definition}}} <strong>--{{{$gloss->contextual_gloss}}}</strong><br/>
			    		</div>
			    	</div>
			    @endforeach
			    
			    <div class='row'>
			   		<div class='col-sm-2'></div>
			   		<div class='form-group col-sm-1 '> 
				    	{{ Form::submit('Attach Gloss', ['class' => 'btn btn-success attach_gloss']) }}
				    </div>
				</div>

			    <hr/>
	    @endforeach
    </div>
    
    <!-- This is the template for adding new glossed text.  It is not used, but cloned when we want to add a new one -->
    <div id="new_glossed_text_div" style="display: none">
	    {{ Form::open(['role' => 'form',
		    		   'url' => '/admin/eieol_glossed_text/', 
		    		   'class' => 'form ajax_form',
		    		   'id' => 'new_glossed_text_form'
		    		  ]) }} 
		    	
		    	{{ Form::hidden('lesson_id', $lesson->id) }}
		    	
				<div class='row'>
					<div class='col-sm-1'></div>
					
					<div class='form-group col-sm-1'>
				        {{ Form::label('order', 'Order') }}
				        {{ Form::text('order', null, ['placeholder' => 'Order', 'class' => 'form-control']) }}
				        <div id ="order_error" class="alert-danger errors"></div>
				    </div>
				    
				    <div class='form-group col-sm-8'>
				        {{ Form::label('glossed_text', 'Glossed Text') }}
				        {{ Form::text('glossed_text', null, ['placeholder' => 'Glossed Text', 'class' => 'form-control']) }}
				        <div id ="glossed_text_error" class="alert-danger errors"></div>
				    </div>	     
				    
				    <div class='form-group col-sm-1 '> 
				    	{{ Form::submit('Add', ['class' => 'btn btn-success']) }}
				    </div>
			    </div>
		    
		    {{ Form::close() }}
		    <hr/>
	  </div>
	  
	  
	  <!-- Button that will clone the new glossed text template -->
	  <div class='row'>
	  	<div class='col-sm-1'></div>
	  	<div class="col-sm-1">
	  		<a class="btn btn-success" id="add_glossed_text">Create New Glossed Text</a>
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
    						 'url' => '/admin/eieol_lesson/update_translation/' . $lesson->id, 
    						 'method' => 'PUT', 
    						 'class' => 'form ajax_form',
    						 'id' => 'update_translation_form'
    						 ]) }}
		    
		<div class='row'>
			<div class='form-group col-sm-10 col-sm-offset-1'>
		        {{ Form::label('lesson_translation', 'Lesson Translation') }}
		        {{ Form::textarea('lesson_translation', null, ['placeholder' => 'Lesson Translation', 'class' => 'form-control', 'size' => '100x10']) }}
		        <div id ="lesson_translation_error" class="alert-danger errors"></div>
		        {{ Form::submit('Edit Translation', ['class' => 'btn btn-primary']) }}
		    </div>
		    <br/>
	    </div>
	    
	{{ Form::close() }}
	
	<hr/>
    <h2>Grammar</h2>	
    <div id ="grammars">
	    @foreach ($grammars as $grammar)
	          {{ Form::model($grammar, ['role' => 'form',
			    					   'url' => '/admin/eieol_grammar/' . $grammar->id, 
			    					   'method' => 'PUT', 
			    					   'class' => 'form ajax_form',
			    					   'id' => 'grammar_form_' . $grammar->id
			    					  ]) }}
					
					<div class='row'>
						<div class='col-sm-1'></div>
						
						<div class='form-group col-sm-1 '>
					        {{ Form::label('order', 'Order') }}
					        {{ Form::text('order', null, ['placeholder' => 'Order', 'class' => 'form-control']) }}
					        <div id ="order_error" class="alert-danger errors"></div>
					    </div>
					    
					    <div class='form-group col-sm-1 '>
					        {{ Form::label('section_number', 'Section Number') }}
					        {{ Form::text('section_number', null, ['placeholder' => 'Section Number', 'class' => 'form-control']) }}
					        <div id ="section_number_error" class="alert-danger errors"></div>
					    </div>
					    	
					    <div class='form-group col-sm-3'>
					        {{ Form::label('title', 'Title') }}
					        {{ Form::text('title', null, ['placeholder' => 'Title', 'class' => 'form-control']) }}
					        <div id ="title_error" class="alert-danger errors"></div>
					    </div>
					    
					    <br/>
					    
					    <div class='form-group col-sm-10 col-sm-offset-1'>
					        {{ Form::label('grammar_text', 'Grammar Text') }}
					        {{ Form::textarea('grammar_text', null, ['placeholder' => 'Grammar Text', 'class' => 'form-control', 'size' => '100x10', 'id' => 'grammar_text_' . $grammar->id]) }}
					        <div id ="grammar_text_error" class="alert-danger errors"></div>
					        {{ Form::submit('Edit', ['class' => 'btn btn-primary']) }}
					    </div>		    
			
				    </div>
			    
			    {{ Form::close() }}
			    <hr/>
	    @endforeach
    </div>
    
    <!-- This is the template for adding new grammars.  It is not used, but cloned when we want to add a new one -->
    <div id="new_grammar_div" style="display: none">
	    {{ Form::open(['role' => 'form',
		    		   'url' => '/admin/eieol_grammar/', 
		    		   'class' => 'form ajax_form',
		    		   'id' => 'new_grammar_form'
		    		  ]) }} 
		    	
		    	{{ Form::hidden('lesson_id', $lesson->id) }}
		    	
				<div class='row'>
					<div class='col-sm-1'></div>
					
					<div class='form-group col-sm-1'>
				        {{ Form::label('order', 'Order') }}
				        {{ Form::text('order', null, ['placeholder' => 'Order', 'class' => 'form-control']) }}
				        <div id ="order_error" class="alert-danger errors"></div>
				    </div>
				    
				    <div class='form-group col-sm-1'>
				        {{ Form::label('section_number', 'Section Number') }}
				        {{ Form::text('section_number', null, ['placeholder' => 'Section Number', 'class' => 'form-control']) }}
				        <div id ="section_number_error" class="alert-danger errors"></div>
				    </div>
				    	
				    <div class='form-group col-sm-3'>
				        {{ Form::label('title', 'Title') }}
				        {{ Form::text('title', null, ['placeholder' => 'Title', 'class' => 'form-control']) }}
				        <div id ="title_error" class="alert-danger errors"></div>
				    </div>
				    
				    <br/>
				    
				    <div class='form-group col-sm-10 col-sm-offset-1'>
				        {{ Form::label('grammar_text', 'Grammar Text') }}
				        {{ Form::textarea('grammar_text', null, ['placeholder' => 'Grammar Text', 'class' => 'form-control', 'size' => '100x10', 'id' => 'new_grammar_text']) }}
				        <div id ="grammar_text_error" class="alert-danger errors"></div>
				        {{ Form::submit('Add', ['class' => 'btn btn-success']) }}
				    </div>		    
		
			    </div>
		    
		    {{ Form::close() }}
		    <hr/>
	  </div>
	  
	  
	  <!-- Button that will clone the new grammar template -->
	  <div class='row'>
	  	<div class='col-sm-1'></div>
	  	<div class="col-sm-1">
	  		<a class="btn btn-success" id="add_grammar">Create New Grammar</a>
	  	</div>
	  </div>
    
</div>

<script>
	//apply the ckeditor to the intro text
	CKEDITOR.plugins.addExternal( 'onchange', '/js/', 'onchangeplugin.js' );
	CKEDITOR.replace( 'intro_text',{toolbar : $mytoolbar, contentsCss : '/css/lrcstyle.css', allowedContent : true, extraPlugins : 'onchange'} );
	CKEDITOR.instances['intro_text'].on('change', function() {
		if(this.checkDirty())
			$('#update_form').css("background-color", "#EBAD99");
	});

	//apply the ckeditor to the translation
	CKEDITOR.replace( 'lesson_translation',{toolbar : $mytoolbar, contentsCss : '/css/lrcstyle.css', allowedContent : true, extraPlugins : 'onchange'}  );
	CKEDITOR.instances['lesson_translation'].on('change', function() {
		if(this.checkDirty())
			$('#update_translation_form').css("background-color", "#EBAD99");
	});

	//apply the ckeditor to each exisiting grammar
	@foreach ($grammars as $grammar)
		CKEDITOR.replace( 'grammar_text_{{{$grammar->id}}}',{toolbar : $mytoolbar, contentsCss : '/css/lrcstyle.css', allowedContent : true, extraPlugins : 'onchange'}  );
		CKEDITOR.instances['grammar_text_{{{$grammar->id}}}'].on('change', function() {
			if(this.checkDirty())
				$('#grammar_form_{{{$grammar->id}}}').css("background-color", "#EBAD99");
		});
	@endforeach
</script>
 
@stop