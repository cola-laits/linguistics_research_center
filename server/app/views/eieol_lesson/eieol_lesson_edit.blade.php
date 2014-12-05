@extends('admin_layout')
 
@section('title') Edit Lesson @stop
 
@section('content')

<script type="text/javascript">

	function generate_lesson_text() {
		//every time they update the glossed text, we calculate the full text and display it below
		var lesson_text = '';
		$("form", "#glossed_texts").each(function() {
			lesson_text += $('#glossed_text', this).val() + ' ';
		});
		$('#lesson_text').html(lesson_text); //replace div with new text
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
	  		        $('#success_message').html(json['message']);
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

        		    //if they just added a glossed text, we need to further customize the form
        		    if (json.hasOwnProperty('glossed_text_id')) {
        		    	$("#add_glossed_text").show(); //now that they've saved the glossed text, they can add another

        		    	$('#new_glossed_text_div').find('#attach_gloss_form').find("#glossed_text_id").attr('value',json['glossed_text_id']);
        	    		$('#new_glossed_text_div').find('#attach_gloss_form').find("#attach_gloss_button").show();
        	    		$('#new_glossed_text_div').attr("id",'glossed_text_div_' + json['glossed_text_id']);
        	    		$('#new_glossed_text_form').attr("id",'glossed_text_form_' + json['glossed_text_id'] );
        	    		$('#new_glossed_text_glosses').attr("id",'glossed_text_' + json['glossed_text_id'] + '_glosses');
        		    }


        		  //if they just added a grammar, we need to further customize the form
        		    if (json.hasOwnProperty('grammar_id')) {
        		    	$("#add_grammar").show(); //now that they've saved the grammar, they can add another

        		    	//remove old ckeditor
        		    	CKEDITOR.instances['new_grammar_text'].destroy(true); 
        		    	
						//rename div, form and text area
        		    	var new_form_id = 'grammar_form_' + json['grammar_id'];
            		    var new_text_id = 'grammar_text_' + json['grammar_id'];
            		    $('#grammars').find('#new_grammar_div').attr("id",'grammar_div_' + json['grammar_id']);
        		    	$('#grammars').find('#new_grammar_form').attr("id",new_form_id);
        	    		$('#grammars').find('#new_grammar_text').attr("id",new_text_id);

        	    		//attach new ckeditor
        	    		CKEDITOR.replace(new_text_id,{toolbar : $mytoolbar, contentsCss : '/css/lrcstyle.css', allowedContent : true, extraPlugins : 'onchange'}  );
        	    		CKEDITOR.instances[new_text_id].on('change', function() {
        	    			if(this.checkDirty())
        	    				$('#'+new_form_id).css("background-color", "#EBAD99");
        	    		});
        		    }
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
		if (gloss.length==0) { //if the search is blank, reset the result box
			document.getElementById("gloss_search_result").innerHTML="";
		    document.getElementById("gloss_search_result").style.border="0px";
		    return;
		}
		xmlhttp=new XMLHttpRequest();
		xmlhttp.onreadystatechange=function() {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200) { //if ajax is successful, load result box
			  document.getElementById("gloss_search_result").innerHTML=xmlhttp.responseText;
			  document.getElementById("gloss_search_result").style.border="1px solid #A5ACB2"; 
		  }
		}
		xmlhttp.open("GET","/admin/eieol_gloss?gloss="+gloss,true);
		xmlhttp.send();
	}

	
    $(document).ready(function(){

		//build lesson text
		generate_lesson_text();
        
        //highlight form if inputs change.  If you are using ckeditor, you have to do that with its on change function
        $(':input').keyup(highlight_form); //listen for typing
    	$(':input').change(highlight_form); //listen for clicking

    	//bind all ajax forms to our ajax function
    	$('.ajax_form').submit(function(){
    		ajax_submit($(this));
    		return false; // this keeps the form from submitting
    	});//submit

    	//popup to attach gloss
		$(".attach_gloss_form").submit(function() {
		    glossed_text_id = $(this).find("#glossed_text_id").val(); //this sets the global variable so we can attach a selected gloss
		    $("#gloss_search_input").val(""); //reset the input box
		    $("#attach_gloss_modal").modal('show'); 
		    $("#gloss_search_input").focus(); //put cursor in search box
		    document.getElementById("gloss_search_result").innerHTML=""; //reset result box so it's empty each time the click it
		    document.getElementById("gloss_search_result").style.border="0px"; //remove the border from the result box
		    return false;
		});

    	//this is when they click on a gloss in the gloss listining modal
		$("#gloss_search_result").on('click', 'a', function() {
			
			//calculate next order by finding the highest order in the form and adding 10
			var next_gloss_order = 0;
			temp_div = '#glossed_text_' + glossed_text_id + '_glosses'; //get div that surrouds glosses for given glossed text
			$("form", temp_div).each(function() { // get the value of each order
				order = parseInt($('#order', this).val());
				if(order > next_gloss_order) {
					next_gloss_order = order;
				}
			});
			next_gloss_order += 10;

			gloss_text = $(this).html();

			var mydata = {};
			mydata['gloss_id'] = $(this).attr('id');
			mydata['glossed_text_id'] = glossed_text_id; //set when displaying attach modal
			mydata['order'] = next_gloss_order;
			mydata['token'] = '{{csrf_token()}}';
			

			$.ajax({
				type: "POST",
		        url:'/admin/eieol_glossed_text_gloss',
		        data:mydata,
		        dataType: "html",
		        
		        success : function(data){
			        var json = JSON.parse(data);    
			        
		        	if(json['fail']) { 
		        		alert('Ajax Error: ' + json['msg']);
		  		    }  //json fail
		  		    
		  		    if(json['success']) { //briefly show a success popup and turn off form background
		  		    	var new_div_id = "new_glossed_text_gloss_div_" + json['id'];
		  	    		var new_form_id = "new_glossed_text_gloss_form_" + json['id'];
		  	    		var new_form_action = "/admin/eieol_glossed_text_gloss/" + json['id']
		  	    		
		  	    		var new_div = $( "#new_glossed_text_gloss_div" ).clone(true).attr("id",new_div_id);
		  	    		new_div.appendTo( temp_div );
		  	    		new_div.show();
						
		  	    		$('#new_glossed_text_gloss_form', '#'+new_div_id).find("#order").attr('value',next_gloss_order);
		  	    		$('#new_glossed_text_gloss_form', '#'+new_div_id).find("#glossed_text_id").attr('value',glossed_text_id);
		  	    		$('#new_glossed_text_gloss_form', '#'+new_div_id).find(".gloss_text").html(gloss_text);
		  	    		$('#new_glossed_text_gloss_form', '#'+new_div_id).attr("action",new_form_action);
		  	    		$('#new_glossed_text_gloss_form', '#'+new_div_id).attr("id",new_form_id);
		  	    		
		  		    	$("#attach_gloss_modal").modal('hide'); 
		  		    	$('#success_message').html('Gloss successfully added.');
		  		        $("#update_confirm").modal('show');
			  		    setTimeout(function(){
			  		        $("#update_confirm").modal('hide');
			  		    }, 1000);
		  		    } //json success
	    
		        }, //success
		        
		        error : function(xml_http_request, text_status, error_thrown) {
		        	alert('Ajax Error: ' + text_status + '/ ' + xml_http_request + '/ ' + error_thrown);
		        } //error

		    }); //ajax call

		});
    	
    	//this clones the default add glossed text form 
    	$("#add_glossed_text").click(function() {	
    		var new_div = $("#new_glossed_text_div").clone(true);
    		new_div.appendTo("#glossed_texts");
    		new_div.show();

    		//calculate next order by finding the highest order in the form and adding 10
			var next_glosssed_text_order = 0;
			$(".glossed_text_form").each(function() { // get the value of each order
				order = parseInt($('#order', this).val());
				if(order > next_glosssed_text_order) {
					next_glosssed_text_order = order;
				}
			});
			next_glosssed_text_order += 10;
			$("#glossed_texts").find("#new_glossed_text_div").find("#order").val(next_glosssed_text_order);
			
    		$("#add_glossed_text").hide(); //hide the button so they can't add another glossed text till they finsish this one
    	});
    	

    	//this clones the default add grammar form and turns on the ckeditor for it
    	$( "#add_grammar" ).click(function() {	
    		var new_div = $( "#new_grammar_div" ).clone(true);
    		new_div.appendTo( "#grammars" );
    		new_div.show();
    		
    		CKEDITOR.replace('new_grammar_text',{toolbar : $mytoolbar, contentsCss : '/css/lrcstyle.css', allowedContent : true, extraPlugins : 'onchange'}  );
    		CKEDITOR.instances['new_grammar_text'].on('change', function() {
    			if(this.checkDirty())
    				$('#new_grammar_form').css("background-color", "#EBAD99");
    		});

    		//calculate next order by finding the highest order in the form and adding 10
			var next_grammar_order = 0;
			$(".grammar_form").each(function() { // get the value of each order
				order = parseInt($('#order', this).val());
				if(order > next_grammar_order) {
					next_grammar_order = order;
				}
			});
			next_grammar_order += 10;
			$("#grammars").find("#new_grammar_div").find("#order").val(next_grammar_order);

    		$("#add_grammar").hide(); //hide the button so they can't add another glossed text till they finsish this one
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
            <div class="modal-body" id="success_message">
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
            <div class="modal-body">
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
    

    
    
    
    <!-- ---------------------------------------------------------------------------------------- -->  
    
    
    <hr/>
    <h2>Glossed Texts</h2>
    
    <div id ="glossed_texts">
	    @foreach ($glossed_texts as $glossed_text)
	          {{ Form::model($glossed_text, ['role' => 'form',
			    					   'url' => '/admin/eieol_glossed_text/' . $glossed_text->id, 
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
			    
			    <div id="glossed_text_{{$glossed_text->id}}_glosses">
			    
				    @foreach ($glossed_text->glosses as $gloss)
					    {{ Form::model($gloss, ['role' => 'form',
					    					   'url' => '/admin/eieol_glossed_text_gloss/' . $gloss->pivot->id, 
					    					   'method' => 'PUT', 
					    					   'class' => 'form ajax_form',
					    					   'id' => 'glossed_text_gloss_form_' . $gloss->pivot->id
					    					  ]) }}
							{{ Form::hidden('glossed_text_id', $glossed_text->id, ['id' => 'glossed_text_id']) }}
							<div class='row'>
								<div class='col-sm-2'></div>
								
								<div class='form-group col-sm-1 '>
							        {{ Form::label('order', 'Order') }}
							        {{ Form::text('order', $gloss->pivot->order, ['placeholder' => 'Order', 'class' => 'form-control']) }}
							        <div id ="order_error" class="alert-danger errors"></div>
							    </div>
							    
							    <div class='form-group col-sm-1 '>
								    {{ Form::submit('Edit', ['class' => 'btn btn-primary']) }}
								</div>
							    	
							    <div class='col-sm-7'>
				    				{{{$gloss->surface_form}}} -- {{{$gloss->part_of_speech}}}; {{$gloss->analysis}} {{{$gloss->head_word->word}}} {{{$gloss->head_word->definition}}} <strong>--{{{$gloss->contextual_gloss}}}</strong><br/>
				    			</div>   
							      
						    </div>
					    
					    {{ Form::close() }}
	
				    @endforeach
			    
			    </div>
			   
			    <!-- this will open a modal to attach a gloss to the glossed text --> 
			    <div class='row'>
			   		<div class='col-sm-2'></div>
			   		<div class='form-group col-sm-1 '> 
			   			{{ Form::open(['class' => 'attach_gloss_form']) }} 
			   				{{ Form::hidden('glossed_text_id', $glossed_text->id, ['id' => 'glossed_text_id']) }}
				    		{{ Form::submit('Attach Gloss', ['class' => 'btn btn-success']) }}
				    	{{ Form::close() }}
				    </div>
				</div>

			    <hr/>
	    @endforeach
    </div>
    
    <!-- This is the template for adding new glossed text.  It is not used, but cloned when we want to add a new one -->
    <div id="new_glossed_text_div" style="display: none">
	    {{ Form::open(['role' => 'form',
		    		   'url' => '/admin/eieol_glossed_text/', 
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
				    
				    <div class='form-group col-sm-8'>
				        {{ Form::label('glossed_text', 'Glossed Text') }}
				        {{ Form::text('glossed_text', null, ['placeholder' => 'Glossed Text', 'class' => 'form-control', 'id' => 'glossed_text']) }}
				        <div id ="glossed_text_error" class="alert-danger errors"></div>
				    </div>	     
				    
				    <div class='form-group col-sm-1 '> 
				    	{{ Form::submit('Add', ['class' => 'btn btn-success']) }}
				    </div>
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
			    		{{ Form::submit('Attach Gloss', ['class' => 'btn btn-success', 'id' => 'attach_gloss_button', 'style' => 'display: none']) }}
			    	{{ Form::close() }}
			    </div>
			</div>
		    
		    
		    
		    <hr/>
	  </div>
	  
	  
	  <!-- Button that will clone the new glossed text template -->
	  <div class='row'>
	  	<div class='col-sm-1'></div>
	  	<div class="col-sm-1">
	  		<a class="btn btn-success" id="add_glossed_text">Create New Glossed Text</a>
	  	</div>
	  </div>
	  
	  
	  
    
    
    	<!-- This is the template for adding new glossed_text_GLOSS.  It is not used, but cloned when we want to add a new one -->
    	<div id="new_glossed_text_gloss_div" style="display: none">
	    	{{ Form::open(['role' => 'form',
		   		   'url' => '/admin/eieol_glossed_text_gloss/', 
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
				    
				    <div class='form-group col-sm-1 '>
					    {{ Form::submit('Edit', ['class' => 'btn btn-primary']) }}
					</div>
				    	
				    <div class='col-sm-7 gloss_text'>
		   			</div>   
				      
			    </div>
		    
		    {{ Form::close() }}
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
	
	
	<!-- ---------------------------------------------------------------------------------------- -->
	
	
	<hr/>
    <h2>Grammar</h2>	
    <div id ="grammars">
	    @foreach ($grammars as $grammar)
	          {{ Form::model($grammar, ['role' => 'form',
			    					   'url' => '/admin/eieol_grammar/' . $grammar->id, 
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


<!-- ---------------------------------------------------------------------------------------- -->

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