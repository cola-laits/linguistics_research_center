@extends('admin_layout')
 
@section('title') Edit Lesson @stop
 
@section('content')

<script type="text/javascript">
	
	function ajax_submit(myform) { 
		//generic ajax function.  Give your form action the name of the url you want to go to
		//and give it class=ajax_form.  This will prevent the regular submission and send it by ajax instead.

		$(".spinner").show();

		//get values from CKEditor
		for ( instance in CKEDITOR.instances )
		    CKEDITOR.instances[instance].updateElement();

	    //hide any previous error messages
	    $(".errors").empty();
	    
		$.ajax({
			type: "POST",
	        url:myform.attr('action'),
	        data:myform.serialize(),
	        dataType: "html",
	        success : function(data){
		        var json = JSON.parse(data);
		        
	        	if(json['fail']) {
	  		      $.each(json['errors'], function( index, value ) {
	  		        var errorDiv = '#'+index+'_error';
	  		        $(errorDiv).html(value);
	  		      });
	  		      $('#successMessage').empty();          
	  		    }  //json fail
	  		    
	  		    if(json['success']) {
	  		        $('#success_messaage').html(json['message']);
	  		        $("#update_confirm").modal('show');
		  		    setTimeout(function(){
		  		        $("#update_confirm").modal('hide');
		  		    }, 1000);
	  		      	myform.css("background-color", "#FFFFFF");
	  		    } //json success
	  		    
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
	
    $(document).ready(function(){
        //trigger highlight form if inputs change.  If you are using ckeditor, you have to do that with its on change function
        $(':input').keyup(highlight_form); //listen for typing
    	$(':input').change(highlight_form); //listen for clicking


    	//bind all ajax forms to our ajax function
    	$('.ajax_form').submit(function(){
    		ajax_submit($(this));
    		return false; // this keeps the form from submitting
    	});//submit
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

<div class='col-lg-12'>
 
    <h1><i class='fa fa-file-text'></i> Edit Lesson for {{ HTML::link('admin/eieol_series/' . $series->id . '/edit', $series->title , array('title' => 'Return to series' )) }}</h1>
    
    <div id="successMessage"></div>
    
    {{ Form::model($lesson, ['role' => 'form', 
    						 'url' => '/admin/eieol_lesson/' . $lesson->id, 
    						 'method' => 'PUT', 
    						 'class' => 'form ajax_form', 
    						 'id' => 'update_form',
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
    <hr/>
    
    <h2>Text and Translation</h2>
    
    <div class='row'>
		<div class='col-sm-10 col-sm-offset-1'>
	        Lesson Text 
	        <div class="well">
	        	calculate and display
	        </div>
	    </div>
	    <br/>
    </div>
    
    
    {{ Form::model($lesson, ['role' => 'form', 
    						 'url' => '/admin/eieol_lesson/update_translation/' . $lesson->id, 
    						 'method' => 'PUT', 
    						 'class' => 'form ajax_form', 
    						 'id' => 'update_translation_form',
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
    
</div>

<script>
	CKEDITOR.plugins.addExternal( 'onchange', '/js/', 'onchangeplugin.js' );
	CKEDITOR.replace( 'intro_text',{toolbar : $mytoolbar, contentsCss : '/css/lrcstyle.css', allowedContent : true, extraPlugins : 'onchange'} );
	CKEDITOR.replace( 'lesson_translation',{toolbar : $mytoolbar, contentsCss : '/css/lrcstyle.css', allowedContent : true, extraPlugins : 'onchange'}  );
	CKEDITOR.instances['intro_text'].on('change', function() {
		if(this.checkDirty())
			$('#update_form').css("background-color", "#EBAD99");
	});
	CKEDITOR.instances['lesson_translation'].on('change', function() {
		if(this.checkDirty())
			$('#update_translation_form').css("background-color", "#EBAD99");
	});
</script>
 
@stop