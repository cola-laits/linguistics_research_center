@extends('admin_layout')
 
@section('title') Edit Lesson @stop
 
@section('content')

<script type="text/javascript">

	function generate_lesson_text() {
		//every time they update the glossed text, we calculate the full text and display it below
		var lesson_text = '';
		$("form", "#glossed_texts").each(function() {
			if ($('#glossed_text', this).val() != null) { //without this, it will try to include edit gloss forms
				lesson_text += $('#glossed_text', this).val() + ' ';
			}
		});
		$('#lesson_text').html(lesson_text); //replace div with new text
	} //generate_lesson_text

	function ajax_submit(myform) { 
		//generic ajax function.   This will prevent the regular submission and send it by ajax instead.

		$(".spinner").show();

		//get values from CKEditor and put them into textarea fields
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
	  		      	myform.removeAttr("dirty");


	  		    	//if they updated the language, we need to change the hidden language ids
	  		      	if(json.hasOwnProperty('language_id')) {
						$(".language_id_class").each(function() {
							$(this).attr('value',json['language_id']);;
							hold_language_id = json['language_id'];
						});
	  		      	}
	  		      	
	  		      	//if they updated a gloss, we need to change the text of every occurrence of it on the page
	  		      	if(json.hasOwnProperty('gloss_id')) {
						$(".gloss_" + json['gloss_id']).each(function() {
							$(this).html(json['gloss_display']);
						});
	  		      	}
	  		    } //json success

    		    if(json['added']) { //if we just performed an add, we need to change the form to an update form
        		    $(formDiv).find(":submit").attr('value','Edit');
        		    $(formDiv).find(":submit").attr('class', 'btn btn-primary');
        		    $(formDiv).attr("action", json['action']);
        		    $('<input>').attr({type: 'hidden', value: 'PUT', name: '_method'}).appendTo(formDiv);

        		    //if they just added a glossed text, we need to further customize the form
        		    if (json.hasOwnProperty('glossed_text_id')) {
        		    	$("#add_glossed_text").show(); //now that they've saved the glossed text, they can add another
        		    	var new_form_id = 'glossed_text_form_' + json['glossed_text_id'];
        		    	var new_div_id = 'glossed_text_div_' + json['glossed_text_id'];
        		    	$('#new_glossed_text_div').find('#attach_gloss_form').find("#glossed_text_id").attr('value',json['glossed_text_id']);
        	    		$('#new_glossed_text_div').find('#attach_gloss_form').find("#attach_gloss_button").show();
        	    		$('#new_glossed_text_div').attr("id",new_div_id);
        	    		$('#new_glossed_text_form').attr("id", new_form_id);
        	    		$('#new_glossed_text_glosses').attr("id",'glossed_text_' + json['glossed_text_id'] + '_glosses');
        	    		$('#' +  new_form_id).find('.delete_glossed_text').show();
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
        	    		$('#grammars').find('.delete_grammar').show();

        	    		//attach new ckeditor
        	    		CKEDITOR.replace(new_text_id,{toolbar : $mytoolbar, contentsCss : '/css/lrcstyle.css', disableNativeSpellChecker:false, allowedContent : true, extraPlugins : 'onchange,language', specialChars : [ {{$lesson->language->custom_keyboard_layout}} ] }  );
        	    		CKEDITOR.instances[new_text_id].on('change', function() {
        	    			if(this.checkDirty()) {
        	    				$('#'+new_form_id).css("background-color", "#EBAD99");
        	    				$('#'+new_form_id).attr("dirty", "dirty");
        	    			}
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

	function attach_gloss(gloss_id, gloss_text) {
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

		var mydata = {};
		mydata['gloss_id'] = gloss_id;
		mydata['glossed_text_id'] = glossed_text_id; //set when displaying attach modal
		mydata['order'] = next_gloss_order;
		mydata['token'] = '{{csrf_token()}}';
		
		$.ajax({
			type: "POST",
	        url:'/admin2/eieol_glossed_text_gloss',
	        data:mydata,
	        dataType: "html",
	        
	        success : function(data){
		        var json = JSON.parse(data);    
		        
	        	if(json['fail']) { 
	        		alert('Ajax Error: ' + json['msg']);
	  		    }  //json fail
	  		    
	  		    if(json['success']) { 
	  		    	var new_div_id = "glossed_text_gloss_" + json['id'] + "_div";
	  	    		var new_form_id = "new_glossed_text_gloss_form_" + json['id'];
	  	    		var new_form_action = "/admin2/eieol_glossed_text_gloss/" + json['id']
	  	    		
	  	    		var new_div = $( "#new_glossed_text_gloss_div" ).clone(true).attr("id",new_div_id);
	  	    		new_div.appendTo( temp_div );
	  	    		new_div.show();
					
	  	    		$('#new_glossed_text_gloss_form', '#'+new_div_id).find("#order").attr('value',next_gloss_order);
	  	    		$('#new_glossed_text_gloss_form', '#'+new_div_id).find("#glossed_text_id").attr('value',glossed_text_id);
	  	    		$('#new_glossed_text_gloss_form', '#'+new_div_id).find(".gloss_text").html('<br/>' + gloss_text);
	  	    		$('#new_glossed_text_gloss_form', '#'+new_div_id).find(".gloss_text").addClass('gloss_' + gloss_id);
	  	    		$('#new_glossed_text_gloss_form', '#'+new_div_id).attr("action",new_form_action);
	  	    		$('#new_glossed_text_gloss_form', '#'+new_div_id).attr("id",new_form_id);
	  	    		$('.delete_glossed_text_gloss', '#' + new_div_id).show();
	  	    		$('.delete_glossed_text_gloss_form', '#'+new_div_id).attr("action",new_form_action);
	  	    		$('#glossed_text_gloss_id', '#'+new_div_id).val(json['id']);

	  	    		$('#edit_gloss', '#'+new_div_id).find("#gloss_id").attr('value',gloss_id);

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
	} //attach gloss function

	function attach_head_word(head_word_id, head_word_display) {
		//gloss_form is set when they open the head word modal
		$(gloss_form).find("#element_" + element_id + "_head_word_id").attr('value', head_word_id);
		$(gloss_form).find("#element_" + element_id + "_head_word_display").html(head_word_display);
		highlight_form($(gloss_form));
		$("#attach_head_word_modal").modal('hide'); 
	} //attach head word

	//highlight forms if they are changed
	function highlight_form(input){
		var my_form = $(input).closest('form');
		my_form.css("background-color", "#EBAD99");
		if (my_form.attr('id') == 'edit_gloss_form' || 
			my_form.attr('id') == 'new_head_word_form' ||
			my_form.attr('id') == 'edit_head_word_form' ||
			my_form.attr('id') == 'new_gloss_form' ) {
			//ignore popup's when it comes to setting dirty attribute
		} else {
			my_form.attr("dirty", "dirty");
		}		
	} //highlight form


	//ajax search for glosses
	function searchGlosses(gloss) {
		if (gloss.length==0) { //if the search is blank, reset the result box
			document.getElementById("gloss_search_result").innerHTML="";
		    return;
		}
		xmlhttp=new XMLHttpRequest();
		xmlhttp.onreadystatechange=function() {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200) { //if ajax is successful, load result box
			  document.getElementById("gloss_search_result").innerHTML=xmlhttp.responseText;
		  }
		}
		xmlhttp.open("GET","/admin2/eieol_gloss/filtered_list?gloss="+gloss+"&language="+hold_language_id,true);
		xmlhttp.send();
	}

	//ajax search for head words
	function searchHeadWords(head_word) {
		if (head_word.length==0) { //if the search is blank, reset the result box
			document.getElementById("head_word_search_result").innerHTML="";
		    return;
		}
		xmlhttp=new XMLHttpRequest();
		xmlhttp.onreadystatechange=function() {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200) { //if ajax is successful, load result box
			  document.getElementById("head_word_search_result").innerHTML=xmlhttp.responseText;
		  }
		}
		xmlhttp.open("GET","/admin2/eieol_head_word/filtered_list?head_word="+head_word+"&language="+hold_language_id,true);
		xmlhttp.send();
	}

	
    $(document).ready(function(){

    	//if they have unsaved changes, ask them if they want to leave
    	window.onbeforeunload = function() {
        	dirty_page = false;
    		$("form").each(function() {
    			   var attr = $(this).attr('dirty');
    			   if (typeof attr !== typeof undefined && attr !== false) {
    				   dirty_page = true;
    			   }    			   
    		});
    		if (dirty_page) {
	    	    return 'You have unsaved changes!';
    		}
    	}

    	//set language js variable so we can use it for the gloss, head word and keyword lookups
    	hold_language_id = {{$lesson->language_id}};
    	
		//build lesson text
		generate_lesson_text();

		//turn on tags for keywords (in head word modal)
		$('#new_keywords').tagsInput({
			'height':'50px',
			'width':'100%',
			'defaultText':'',
			'autocomplete_url':'/admin2/eieol_head_word_keyword/filtered_list?language='+hold_language_id
		});
		$('#edit_keywords').tagsInput({
			'height':'50px',
			'width':'100%',
			'defaultText':'',
			'autocomplete_url':'/admin2/eieol_head_word_keyword/filtered_list?language='+hold_language_id
		});
		  
		//custom keyboard for text inputs
		$('.custom-keyboard').specialedit([ {{$lesson->language->custom_keyboard_layout}} ]); 

		//these two functions prevent users from tabbing out of the keywords fields.  We want them to stay and enter a comma after each word
		$('#new_keywords_tag').keypress(function (e) { //listen for typing
            if(e.keyCode == 9){ // tab
                e.preventDefault();
            }
        });
		$('#edit_keywords_tag').keypress(function (e) { //listen for typing
            if(e.keyCode == 9){ // tab
                e.preventDefault();
            }
        });

		//autocomplete fields
		$(".part_of_speech").autocomplete({
		    source: function (request, response) {
		        $.ajax({
		            dataType: "json",
		            data: {
	                    term: request.term,
	                },
		            type : 'GET',
		            url: '/admin2/part_of_speech/filtered_list',
		            success: function(data) {
		            	response(data)
	
		            }
		        });
		    }
		}); //part of speech autocomplete

		$(".analysis").autocomplete({
		    source: function (request, response) {
		        $.ajax({
		            dataType: "json",
		            data: {
	                    term: request.term,
	                },
		            type : 'GET',
		            url: '/admin2/eieol_analysis/filtered_list',
		            success: function(data) {
		            	response(data)
	
		            }
		        });
		    }
		}); //analysis autocomplete
		
		       
        //highlight form if inputs change.  If you are using ckeditor, you have to do that with its on change function
        
        var ctrlDown = false;
	    var ctrlKey = 17, aKey = 65, cKey = 67;
	
	    $(document).keydown(function(e)
	    {
	        if (e.keyCode == ctrlKey) ctrlDown = true;
	    }).keyup(function(e)
	    {
	        if (e.keyCode == ctrlKey) ctrlDown = false;
	    });
	
        $(':input').keyup(function (e) { //listen for typing
            ignore_keys = false;
            if(e.keyCode == 9 ||
               e.keyCode == 16 || 
               e.keyCode == 17 ||
               e.keyCode == 18 || 
               e.keyCode == 20 || 
               e.keyCode == 27 || 
               e.keyCode == 45 || 
               e.keyCode == 36 || 
               e.keyCode == 35 ||
               e.keyCode == 33 || 
               e.keyCode == 34 ||
               e.keyCode == 37 || 
               e.keyCode == 38 || 
               e.keyCode == 39 || 
               e.keyCode == 40 || 
               e.keyCode == 91 || 
               e.keyCode == 92){ //ignore tab, shift, ctrl, alt, caplock, escape, insert, home, end, page up, page down,arrows,windows keys                
                ignore_keys = true;
            }

            if (ctrlDown && (e.keyCode == aKey || e.keyCode == cKey)){ //ignore select all and copy
                ignore_keys = true;
            }

            if (!ignore_keys) {
            	highlight_form(this); 
            }
        });
    	$(':input').change(function () { //listen for clicking
            highlight_form(this); 
        });

    	//bind all ajax forms to our ajax function
    	$('.ajax_form').submit(function(){
    		ajax_submit($(this));
    		return false; // this keeps the form from submitting
    	});//submit

    	//popup to attach gloss
		$(".attach_gloss_form").submit(function() {
			$("#new_gloss_form").css("background-color", "#FFFFFF");
		    glossed_text_id = $(this).find("#glossed_text_id").val(); //this sets the global variable so we can attach a selected gloss
		    $("#gloss_search_input").val(""); //reset the input box
		    $("#attach_gloss_modal").modal('show'); 
		    $("#gloss_search_input").focus(); //put cursor in search box
		    document.getElementById("gloss_search_result").innerHTML=""; //reset result box so it's empty each time the click it
		    $('#new_gloss_form')[0].reset(); //reset the new gloss form
		    $(".errors", '#new_gloss_form').empty(); //reset gloss form error divs
		    for (i=1; i<=6; i++) {
		    	$('#element_' + i + '_head_word_display', '#new_gloss_form').text(''); //reset headword text
		    	$('#element_' + i + '_head_word_id', '#new_gloss_form').val('');//reset headword id
		    }  
		    return false;
		});

    	//this is when they click on a gloss in the gloss listing modal
		$("#gloss_search_result").on('click', 'a', function() {
			attach_gloss($(this).attr('id'), $(this).html());
		});

		//when they add a new gloss
		$("#new_gloss_form").submit(function() {	
			$(".errors", '#new_gloss_form').empty();
			$.ajax({
				type: "POST",
		        url:$("#new_gloss_form").attr('action'),
		        data:$("#new_gloss_form").serialize(),
		        dataType: "html",
		        
		        success : function(data){
			        var json = JSON.parse(data);    
			        
		        	if(json['fail']) { //go through all errors and set error messages, just within this form;
		  		      $.each(json['errors'], function( index, value ) {
		  		          var errorDiv = '#'+index+'_error';
		  		          $(errorDiv, "#new_gloss_form").html(value);
		  		      });        
		  		    }  //json fail
		  		    
		  		    if(json['success']) { 
		  		      	$(this).css("background-color", "#FFFFFF");
		  		        $(this).removeAttr("dirty");
		  		        attach_gloss(json['gloss_id'], json['gloss_display']);
		  		    } //json success
		        }, //success
		        
		        error : function(xml_http_request, text_status, error_thrown) {
		        	alert('Ajax Error: ' + text_status + '/ ' + xml_http_request + '/ ' + error_thrown);
		        } //error

		    }); //ajax call
		    
		    return false; // this keeps the form from submitting
    	});//add gloss

    	//popup to edit gloss
		$(".edit_gloss").submit(function() {
		    		    
		    //load form with data for the record they want to edit
		    $.ajax({
				type: "GET",
		        url: "/admin2/eieol_gloss/" + $(this).find("#gloss_id").val(),
		        data: null,
		        dataType: "json",
		        
		        success : function(data){
		    		$('#edit_gloss_form')[0].reset();
		    		//for some reason the reset doesn't reset all the fields
		    		for (i=1; i<=6; i++) {
				    	$('#element_' + i + '_head_word_id', '#edit_gloss_form').val('');
				    }  
				    
			        $.each(data, function(key, value){
					    $('[name='+key+']', '#edit_gloss_form').val(value);
				    });
				    for (i=1; i<=6; i++) {
				    	$('#element_' + i + '_head_word_display', '#edit_gloss_form').text(''); //we only get ones that already exist, so reset it first
				    	$('#element_' + i + '_head_word_display', '#edit_gloss_form').html(data['element_' + i + '_head_word_display']);
				    }    

				    for (i=2; i<=6; i++) {
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
				    $('#edit_gloss_form').css("background-color", "#FFFFFF");
				    $('#edit_gloss_form').removeAttr("dirty");
				    $("#surface_form", "#edit_gloss_form").focus(); //put cursor in first field
		        }, //success
		        
		        error : function(xml_http_request, text_status, error_thrown) {
		        	alert('Ajax Error: ' + text_status + '/ ' + xml_http_request + '/ ' + error_thrown);
		        } //error

		    }); //ajax call

		    
		    
		    return false;
		}); //edit gloss

		//delete glossed_text_gloss confirmation
		$(".delete_glossed_text_gloss").click(function(e) {
		    e.preventDefault();
		    var form=$(this).closest('form');
		    var my_div = 'glossed_text_gloss_' + $(form).find("#glossed_text_gloss_id").val() + '_div';
		    $("#delete_glossed_text_gloss_confirm").modal('show')
		    	.one('click', '#delete_confirmed', function (e) {
		    		$.ajax({
						type: "POST",
				        url:form.attr('action'),
				        data:{'_method':'delete'},
				        dataType: "html",
				        
				        success : function(){
				        	$('#' + my_div).remove();
				            $("#delete_glossed_text_gloss_confirm").modal('hide');

				            $('#success_message').html('Gloss has been unattached.');
			  		        $("#update_confirm").modal('show');
				  		    setTimeout(function(){
				  		        $("#update_confirm").modal('hide');
				  		    }, 1000);
				        }, //success
				        
				        error : function(xml_http_request, text_status, error_thrown) {
				        	alert('Ajax Error: ' + text_status + '/ ' + xml_http_request + '/ ' + error_thrown);
				        } //error

				    }); //ajax call		            
		        });
		}); //delete glossed_text_gloss


		//popup to attach or change head word to gloss
		$(".pick_head_word_button").click(function() {
			$("#new_head_word_form").css("background-color", "#FFFFFF");
			gloss_form = $(this).closest('form'); //we will use this in the attach_head_word function
		    $("#head_word_search_input").val(""); //reset the input box
		    $("#attach_head_word_modal").modal('show'); 
		    $("#head_word_search_input").focus(); //put cursor in search box
		    document.getElementById("head_word_search_result").innerHTML=""; //reset result box so it's empty each time the click it
		    $('#new_head_word_form')[0].reset(); //reset the new head word form
		    $('#new_keywords').importTags(""); //trigger reset doesn't work because of the jquery tags, so do this one manually
		    $(".errors", '#new_head_word_form').empty(); //reset head word form error divs
		    return false;
		});

		//popup to edit head word
		$(".edit_head_word_button").click(function() {
		    
		    gloss_form = $(this).closest('form'); //get gloss form so we can get head_word_id
		    head_word_id = $(gloss_form).find("#element_" + element_id + "_head_word_id").val();
			if (head_word_id == '') {
				alert('Please add a Head Word before editing it.');
				return false;
			}
		    
		    //load form with data for the record they want to edit
		    $.ajax({
				type: "GET",
		        url: "/admin2/eieol_head_word/" + head_word_id,
		        data: null,
		        dataType: "json",
		        
		        success : function(data){
			        $.each(data, function(key, value){
					    $('[name='+key+']', '#edit_head_word_form').val(value);
				    });
			        $('#edit_keywords').importTags(data['keywords']); //because of the jquery tags, do this one manually
				    $("#edit_head_word_form").attr("action", "/admin2/eieol_head_word/" + data['id']);
				    $("#head_word_glosses").html("<strong>This is used by the following glosses:</strong> " + data['glosses']);
				    $(".errors", '#edit_head_word_form').empty(); //reset head word form error divs
				    $('#edit_head_word_form').css("background-color", "#FFFFFF");
				    $("#edit_head_word_modal").modal('show');
				    $("#word", "#edit_head_word_form").focus(); //put cursor in first field
		        }, //success
		        
		        error : function(xml_http_request, text_status, error_thrown) {
		        	alert('Ajax Error: ' + text_status + '/ ' + xml_http_request + '/ ' + error_thrown);
		        } //error

		    }); //ajax call
		    
		    return false;
		});

		//this is when they click on a head word in the head word listing modal
		$("#head_word_search_result").on('click', 'a', function() {
			attach_head_word($(this).attr('id'), $(this).html());
		});

		//when they add a new headword
		$("#new_head_word_form").submit(function() {	
			$(".errors", '#new_head_word_form').empty();
			$.ajax({
				type: "POST",
		        url:$("#new_head_word_form").attr('action'),
		        data:$("#new_head_word_form").serialize(),
		        dataType: "html",
		        
		        success : function(data){
			        var json = JSON.parse(data);    
			        
		        	if(json['fail']) { //go through all errors and set error messages, just within this form;
		  		      $.each(json['errors'], function( index, value ) {
		  		          var errorDiv = '#'+index+'_error';
		  		          $(errorDiv, "#new_head_word_form").html(value);
		  		      });        
		  		    }  //json fail
		  		    
		  		    if(json['success']) { 
		  		      	$(this).css("background-color", "#FFFFFF");
		  		      	$(this).removeAttr("dirty");
		  		        attach_head_word(json['head_word_id'], json['head_word_display']);
		  		    } //json success
		        }, //success
		        
		        error : function(xml_http_request, text_status, error_thrown) {
		        	alert('Ajax Error: ' + text_status + '/ ' + xml_http_request + '/ ' + error_thrown);
		        } //error

		    }); //ajax call
		    
		    return false; // this keeps the form from submitting
    	});//add headword
    	
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

    	//delete glossed text confirmation
		$(".delete_glossed_text").click(function(e) {
		    e.preventDefault();
		    var form = $(this).closest('form');
		    var div = $(form).closest('div');
		    $("#delete_glossed_text_confirm").modal('show')
		    	.one('click', '#delete_confirmed', function (e) {
		    		$.ajax({
						type: "POST",
				        url:form.attr('action'),
				        data:{'_method':'delete'},
				        dataType: "html",
				        
				        success : function(){
				        	div.remove();
				        	generate_lesson_text();
				            $("#delete_glossed_text_confirm").modal('hide');

				            $('#success_message').html('Glossed Text has been deleted.');
			  		        $("#update_confirm").modal('show');
				  		    setTimeout(function(){
				  		        $("#update_confirm").modal('hide');
				  		    }, 1000);
				        }, //success
				        
				        error : function(xml_http_request, text_status, error_thrown) {
				        	alert('Ajax Error: ' + text_status + '/ ' + xml_http_request + '/ ' + error_thrown);
				        } //error

				    }); //ajax call		            
		        });
		}); //delete glossed text
    	

    	//this clones the default add grammar form and turns on the ckeditor for it
    	$( "#add_grammar" ).click(function() {	
    		var new_div = $( "#new_grammar_div" ).clone(true);
    		new_div.appendTo( "#grammars" );
    		new_div.show();
    		
    		CKEDITOR.replace('new_grammar_text',{toolbar : $mytoolbar, contentsCss : '/css/lrcstyle.css', disableNativeSpellChecker:false, allowedContent : true, extraPlugins : 'onchange', specialChars : [ {{$lesson->language->custom_keyboard_layout}} ]}  );
    		CKEDITOR.instances['new_grammar_text'].on('change', function() {
    			if(this.checkDirty()) {
    				$('#new_grammar_form').css("background-color", "#EBAD99");
    				$('#new_grammar_form').attr("dirty", "dirty");
    			}
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

    	//delete grammar confirmation
		$(".delete_grammar").click(function(e) {
		    e.preventDefault();
		    var form=$(this).closest('form');
		    $("#delete_grammar_confirm").modal('show')
		    	.one('click', '#delete_confirmed', function (e) {
		    		$.ajax({
						type: "POST",
				        url:form.attr('action'),
				        data:{'_method':'delete'},
				        dataType: "html",
				        
				        success : function(){
				        	form.remove();
				            $("#delete_grammar_confirm").modal('hide');

				            $('#success_message').html('Grammar has been deleted.');
			  		        $("#update_confirm").modal('show');
				  		    setTimeout(function(){
				  		        $("#update_confirm").modal('hide');
				  		    }, 1000);
				        }, //success
				        
				        error : function(xml_http_request, text_status, error_thrown) {
				        	alert('Ajax Error: ' + text_status + '/ ' + xml_http_request + '/ ' + error_thrown);
				        } //error

				    }); //ajax call		            
		        });
		}); //delete grammar

		$('.show_element').click(function() {
			  var content = $(this).next();
			  $(content).slideToggle('slow');
			  return false;
			});
		
    });//document ready

    
</script>
 

<div class="spinner">
  {{ HTML::image('images/ajax_loader_red_350.gif', $alt="Loading", $attributes = array('border'=>0, 'width'=>150, 'height'=>150))  }}<br/>Please Wait...
</div>


<div id="attach_gloss_modal" class="modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Attach Gloss</h4>
            </div>
            <div class="modal-body">
            	<div class='col-lg-12'>
                	{{ Form::label('gloss_search_input', 'Search Gloss') }}
		        	{{ Form::text('gloss_search_input', null, ['placeholder' => 'Search Gloss', 'class' => 'form-control custom-keyboard', 'onkeyup' => 'searchGlosses(this.value)']) }}
		        	<br/><br/>
		        </div>
            	<div id="gloss_search_result"></div>
            	           	
            	
		    	<hr/>
				<h4>Or Add New Gloss</h4>
				
				{{ Form::open(['role' => 'form',
		    		  'url' => '/admin2/eieol_gloss/', 
		    		  'class' => 'form modal_form',
		    		  'id' => 'new_gloss_form'  
		    	]) }}
		    		  
		    		{{ Form::hidden('language_id', $lesson->language_id, ['class' => 'language_id_class']) }}
				    
				    @for ($i = 1; $i <= 6; $i++)
				    	<div class='row'>
				    	@if ($i == 1)
						    <div class='form-group col-sm-2'>
						        {{ Form::label('surface_form', 'Surface Form') }}
						        {{ Form::text('surface_form', null, ['placeholder' => 'Surface Form', 'class' => 'form-control custom-keyboard', 'id' => 'surface_form']) }}
						        <div id ="surface_form_error" class="alert-danger errors"></div>
						    </div>
						@else
							<a class="show_element" href="#"><i class='fa fa-plus-square-o '></i></a>
							<div class = "element">
				    		<div class='form-group col-sm-2'></div>
				    	@endif
				    	
					    <div class='form-group col-sm-2'>
					        {{ Form::label('element_' . $i . '_part_of_speech', 'Part Of Speech') }}
					        {{ Form::text('element_' . $i . '_part_of_speech', null, ['placeholder' => 'Part Of Speech', 'class' => 'form-control part_of_speech']) }}
					        <div id ="element_{{$i}}_part_of_speech_error" class="alert-danger errors"></div>
					    </div>	     
					    
					     <div class='form-group col-sm-3'>
					        {{ Form::label('element_' . $i . '_analysis', 'Analysis') }}
					        {{ Form::textarea('element_' . $i . '_analysis', null, ['class' => 'form-control analysis', 'size' => '10x2']) }}
					        <div id ="element_{{$i}}_analysis_error" class="alert-danger errors"></div>
					    </div>	     
					    
					     <div class='form-group col-sm-2'>
					        {{ Form::label('element_' . $i . '_head_word_id', 'Head Word') }}
					        {{ Form::hidden('element_' . $i . '_head_word_id', null, ['id' => 'element_' . $i . '_head_word_id']) }}
					        <div id="element_{{$i}}_head_word_display"></div>
					        {{ Form::button('Pick Head Word', ['class' => 'btn btn-primary btn-sm pick_head_word_button', 'onclick' => 'element_id =' . $i]) }}
					        <div id ="element_{{$i}}_head_word_id_error" class="alert-danger errors"></div>
					    </div>	   
					    
					    @if ($i == 1)  
					    	<div class='form-group col-sm-2'>
						        {{ Form::label('contextual_gloss', 'Contextual Gloss') }}
						        {{ Form::text('contextual_gloss', null, ['placeholder' => 'Contextual Gloss', 'class' => 'form-control', 'id' => 'contextual_gloss']) }}
						        <div id ="contextual_gloss_error" class="alert-danger errors"></div>
						    </div>	     
						    
						    <div class='form-group col-sm-1 bottom_button'> 
						    	{{ Form::submit('Add', ['class' => 'btn btn-success']) }}
						    </div>
						@else
							</div>
						@endif
						
						</div>
						
					@endfor
					
					<div class='row'>
						<div class='form-group col-sm-12'>
							{{ Form::label('comments', 'Comments') }}
					        {{ Form::text('comments', null, ['placeholder' => 'Comments', 'class' => 'form-control', 'id' => 'comments']) }}
					        <div id ="comments_gloss_error" class="alert-danger errors"></div>
						</div>
					</div>
						
				{{ Form::close() }}		    
            </div>
        </div>
    </div>
</div>


<div id="edit_gloss_modal" class="modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Gloss</h4>
            </div>
            <div class="modal-body">
				
				{{ Form::open(['role' => 'form',
		    		  'url' => '', 
		    		  'method' => 'PUT',
		    		  'class' => 'form ajax_form modal_form',
		    		  'id' => 'edit_gloss_form'  
		    	]) }}
		    	
		    		{{ Form::hidden('language_id', $lesson->language_id, ['class' => 'language_id_class']) }}
		    		  
				    @for ($i = 1; $i <= 6; $i++)
				    	<div class='row'>
				    	@if ($i == 1)
						    <div class='form-group col-sm-2'>
						        {{ Form::label('surface_form', 'Surface Form') }}
						        {{ Form::text('surface_form', null, ['placeholder' => 'Surface Form', 'class' => 'form-control custom-keyboard', 'id' => 'surface_form']) }}
						        <div id ="surface_form_error" class="alert-danger errors"></div>
						    </div>
						@else
							<a class="show_element" href="#"><i class='fa fa-plus-square-o '></i></a>
							<div class = "element" id = "element_{{$i}}">
				    		<div class='form-group col-sm-2'></div>
				    	@endif
				    	
				    	{{ Form::hidden('element_' . $i . '_id', null) }}
					    <div class='form-group col-sm-2'>
					        {{ Form::label('element_' . $i . '_part_of_speech', 'Part Of Speech') }}
					        {{ Form::text('element_' . $i . '_part_of_speech', null, ['placeholder' => 'Part Of Speech', 'class' => 'form-control part_of_speech']) }}
					        <div id ="element_{{$i}}_part_of_speech_error" class="alert-danger errors"></div>
					    </div>	     
					    
					     <div class='form-group col-sm-3'>
					        {{ Form::label('element_' . $i . '_analysis', 'Analysis') }}
					        {{ Form::textarea('element_' . $i . '_analysis', null, ['class' => 'form-control analysis', 'size' => '10x2']) }}
					        <div id ="element_{{$i}}_analysis_error" class="alert-danger errors"></div>
					    </div>	     
					    
					     <div class='form-group col-sm-2'>
					        {{ Form::label('element_' . $i . '_head_word_id', 'Head Word') }}
					        {{ Form::hidden('element_' . $i . '_head_word_id', null, ['id' => 'element_' . $i . '_head_word_id']) }}
					        <div id="element_{{$i}}_head_word_display"></div>
					        {{ Form::button('Pick Head Word', ['class' => 'btn btn-primary btn-sm pick_head_word_button', 'onclick' => 'element_id =' . $i]) }}
					        {{ Form::button('Edit Head Word', ['class' => 'btn btn-primary btn-sm edit_head_word_button', 'onclick' => 'element_id =' . $i]) }}
					        <div id ="element_{{$i}}_head_word_id_error" class="alert-danger errors"></div>
					    </div>	   
					    
					    @if ($i == 1)  
					    	<div class='form-group col-sm-2'>
						        {{ Form::label('contextual_gloss', 'Contextual Gloss') }}
						        {{ Form::text('contextual_gloss', null, ['placeholder' => 'Contextual Gloss', 'class' => 'form-control', 'id' => 'contextual_gloss']) }}
						        <div id ="contextual_gloss_error" class="alert-danger errors"></div>
						    </div>	     
						    
						    <div class='form-group col-sm-1 bottom_button'> 
						    	{{ Form::submit('Edit', ['class' => 'btn btn-primary']) }}
						    </div>
						@else
							</div>
						@endif
						
						</div>
					
					@endfor
					
					<div class='row'>
						<div class='form-group col-sm-12'>
							{{ Form::label('comments', 'Comments') }}
					        {{ Form::text('comments', null, ['placeholder' => 'Comments', 'class' => 'form-control', 'id' => 'comments']) }}
					        <div id ="comments_gloss_error" class="alert-danger errors"></div>
						</div>
					</div>
				     
				{{ Form::close() }}

		    	<div class="well" id="gloss_lessons"></div>
            </div>
        </div>
    </div>
</div>


<div id="attach_head_word_modal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Attach Head Word</h4>
            </div>
            <div class="modal-body">
            	<div class='col-lg-12'>
                	{{ Form::label('head_word_search_input', 'Search Head Word') }}
		        	{{ Form::text('head_word_search_input', null, ['placeholder' => 'Search Head Words', 'class' => 'form-control custom-keyboard', 'onkeyup' => 'searchHeadWords(this.value)']) }}
            		<br/><br/>
            	</div>
            	<div id="head_word_search_result"></div>
            	           	
            	
		    	<hr/>
				<h4>Or Add New Head Word</h4>
				<div class='row'>
					<div class='col-sm-12'>
						{{ Form::open(['role' => 'form',
			    		   'url' => '/admin2/eieol_head_word/', 
			    		   'class' => 'form modal_form',
			    		   'id' => 'new_head_word_form'  
			    		  ]) }}
			    		  
			    		{{ Form::hidden('language_id', $lesson->language_id, ['class' => 'language_id_class']) }}
			    		  
						<div class='form-group'>
					        {{ Form::label('word', 'Word') }}
					        {{ Form::text('word', null, ['placeholder' => 'Word', 'class' => 'form-control custom-keyboard']) }}
					        <div id ="word_error" class="alert-danger errors"></div>
					    </div>
					    
					    <div class='form-group'>
					        {{ Form::label('definition', 'Definition') }}
					        {{ Form::text('definition', null, ['placeholder' => 'Definition', 'class' => 'form-control', 'id' => 'definition']) }}
					        <div id ="definition_error" class="alert-danger errors"></div>
					    </div>	     
					    
					    <div class='form-group'>
					        {{ Form::label('etyma_id', 'Etyma') }}
					        {{ Form::select('etyma_id', $etymas, null, ['class' => 'form-control etyma', 'id' => 'etyma_id']) }}
					        <div id ="etyma_id_error" class="alert-danger errors"></div>
					    </div>	
					    
					    <div class='form-group'>
					        {{ Form::label('keywords', 'Keywords') }}
					        {{ Form::text('keywords', null, ['class' => 'form-control keywords', 'id' => 'new_keywords']) }}
					        <div class="alert-warning">Separate with commas</div>
					        <div id ="keywords_error" class="alert-danger errors"></div>
					    </div>	 
	
					    <div class='form-group bottom_button'> 
					    	{{ Form::submit('Add', ['class' => 'btn btn-success']) }}
					    </div>
					    {{ Form::close() }}
					</div>
			    </div>			    
            </div>
        </div>
    </div>
</div>

<div id="edit_head_word_modal" class="modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Head Word</h4>
            </div>
            <div class="modal-body">
				<div class='row'>
					<div class='col-sm-12'>
						{{ Form::open(['role' => 'form',
			    		   'url' => '/admin2/eieol_head_word/', 
			    		   'method' => 'PUT',
			    		   'class' => 'form ajax_form modal_form',
			    		   'id' => 'edit_head_word_form'
			    		  ]) }}
			    		  
			    		  {{ Form::hidden('language_id', $lesson->language_id, ['class' => 'language_id_class']) }}
			    		  
						<div class='form-group'>
					        {{ Form::label('word', 'Word') }}
					        {{ Form::text('word', null, ['placeholder' => 'Word', 'class' => 'form-control custom-keyboard']) }}
					        <div id ="word_error" class="alert-danger errors"></div>
					    </div>
					    
					    <div class='form-group'>
					        {{ Form::label('definition', 'Definition') }}
					        {{ Form::text('definition', null, ['placeholder' => 'Definition', 'class' => 'form-control', 'id' => 'definition']) }}
					        <div id ="definition_error" class="alert-danger errors"></div>
					    </div>	
					    
					    <div class='form-group'>
					        {{ Form::label('etyma_id', 'Etyma') }}
					        {{ Form::select('etyma_id', $etymas, null, ['class' => 'form-control etyma', 'id' => 'etyma_id']) }}
					        <div id ="etyma_id_error" class="alert-danger errors"></div>
					    </div>	
					    
					    <div class='form-group'>
					        {{ Form::label('keywords', 'Keywords') }}
					        {{ Form::text('keywords', null, ['class' => 'form-control keywords', 'id' => 'edit_keywords']) }}
					        <div class="alert-warning">Separate with commas</div>
					        <div id ="keywords_error" class="alert-danger errors"></div>
					    </div>	
	
					    <div class='form-group bottom_button'> 
					    	{{ Form::submit('Edit', ['class' => 'btn btn-primary']) }}
					    </div>
					    
					    {{ Form::close() }}
				    </div>			
			    </div>    
		    
		   		<div class="well" id="head_word_glosses"></div>
	        </div>
	        
            </div>
        </div>
    </div>
</div>

<div id="delete_glossed_text_confirm" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this Glossed Text?  <br/><br/>
                <p class="text-warning"><small>This action can not be undone later.  The contents of this glossed text will be deleted.<br/>
                All attached glosses will be unattached, though they will still be on file and possibly used by other glossed texts.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="delete_confirmed">Delete</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div id="delete_glossed_text_gloss_confirm" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Removal Confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to remove this gloss?  <br/><br/>
                <p class="text-warning"><small>This action can not be undone later.  The contents of this gloss will be unattached from this glossed text.<br/><br/>
                The gloss will still be on file and possibly used by other glossed texts.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="delete_confirmed">Remove</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div id="delete_grammar_confirm" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this Grammar lesson?  <br/><br/>
                <p class="text-warning"><small>This action can not be undone later.  The contents of this grammar will be deleted.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="delete_confirmed">Delete</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- ---------------------------------------------------------------------------------------- -->  
<div class='col-lg-12'>
 
    <h1><i class='fa fa-file-text'></i> Edit Lesson for {{ HTML::link('admin2/eieol_series/' . $lesson->series->id . '/edit', $lesson->series->title , array('title' => 'Return to series' )) }}</h1>
    <div class='bg-danger alert'>
    	If you change the order of items on this page, they will not appear in that order until you refresh the page.
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
		        {{ Form::select('language', $languages, $lesson->language_id, ['class' => 'form-control']) }}
		        <div id ="language_error" class="alert-danger errors"></div>
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
					        {{ Form::text('glossed_text', null, ['placeholder' => 'Glossed Text', 'class' => 'form-control custom-keyboard', 'id' => 'glossed_text']) }}
					        <div id ="glossed_text_error" class="alert-danger errors"></div>
					    </div>	    
					    
					    <div class='form-group col-sm-1 bottom_button'>
						    {{ Form::submit('Edit', ['class' => 'btn btn-primary']) }}
						</div>
					
			    		<div class='form-group col-sm-1 bottom_button'>
			            	{{ Form::button('Delete', ['class' => 'btn btn-danger delete_glossed_text'])}}    
						</div>
	
				    </div>
			    
			    {{ Form::close() }}
			    
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
							    {{ Form::submit('Edit Order', ['class' => 'btn btn-primary']) }}
							    {{ Form::close() }}
							</div>
						    	
						    <div class='col-sm-4 gloss_{{$gloss->id}}'>
						    	<br/>
						    	{{$gloss->getDisplayGloss()}} 
			    			</div>   
			    			
			    			<div class='col-sm-1 bottom_button'>
			    				{{ Form::open(['class' => 'edit_gloss', 
			    							   'id' => 'edit_gloss_form_' . $gloss->pivot->id]) }} 
			    					{{ Form::hidden('gloss_id', $gloss->id, ['id' => 'gloss_id']) }}
			    					{{ Form::submit('Edit Gloss', ['class' => 'btn btn-primary']) }}
			    				{{ Form::close() }}
			    			</div>
			    			
			    			<div class='form-group col-sm-1 bottom_button'>
			    				{{ Form::open(['class' => 'delete_glossed_text_gloss_form',
			    							   'url' => '/admin2/eieol_glossed_text_gloss/' . $gloss->pivot->id,
			    							   'id' => 'delete_glossed_text_gloss_form_' . $gloss->pivot->id]) }} 
				            		{{ Form::hidden('glossed_text_gloss_id', $gloss->pivot->id, ['id' => 'glossed_text_gloss_id']) }}
				            		{{ Form::button('Remove', ['class' => 'btn btn-danger delete_glossed_text_gloss'])}}   
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
				    		{{ Form::submit('Attach Gloss', ['class' => 'btn btn-success']) }}
				    	{{ Form::close() }}
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
				        {{ Form::text('glossed_text', null, ['placeholder' => 'Glossed Text', 'class' => 'form-control custom-keyboard', 'id' => 'glossed_text']) }}
				        <div id ="glossed_text_error" class="alert-danger errors"></div>
				    </div>	     
				    
				    <div class='form-group col-sm-1 bottom_button'> 
				    	{{ Form::submit('Add', ['class' => 'btn btn-success']) }}
				    </div>
				    
				    <div class='form-group col-sm-1 bottom_button'>
		            	{{ Form::button('Delete', ['class' => 'btn btn-danger delete_glossed_text', 'style' => 'display: none'])}}    
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
					    {{ Form::submit('Edit Order', ['class' => 'btn btn-primary']) }}
					    {{ Form::close() }}
					</div>
				    	
				    <div class='col-sm-4 gloss_text'>
		   			</div>   
		   			
		   			<div class='col-sm-1 bottom_button'>
	    				{{ Form::open(['class' => 'edit_gloss', 'id' => 'edit_gloss']) }} 
	    					{{ Form::hidden('gloss_id', null, ['id' => 'gloss_id']) }}
	    					{{ Form::submit('Edit Gloss', ['class' => 'btn btn-primary']) }}
	    				{{ Form::close() }}
	    			</div>
				    
				    <div class='form-group col-sm-1 bottom_button'>
				    	{{ Form::open(['class' => 'delete_glossed_text_gloss_form',
	    							   'url' => '/admin2/eieol_glossed_text_gloss/', 
	    							   'id' => 'delete_gloss']) }} 
	    					{{ Form::hidden('glossed_text_gloss_id', null, ['id' => 'glossed_text_gloss_id']) }}
		            		{{ Form::button('Remove', ['class' => 'btn btn-danger delete_glossed_text_gloss', 'style' => 'display: none'])}}  
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
			    		<div class='form-group col-sm-1 '>
			    			{{ Form::submit('Edit', ['class' => 'btn btn-primary']) }}
					        
			    		</div>
			    		
			    		<div class='form-group col-sm-8 '></div>
			    		<div class='form-group col-sm-1 '>
			            	{{ Form::button('Delete', ['class' => 'btn btn-danger delete_grammar'])}}    
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
		    			{{ Form::submit('Add', ['class' => 'btn btn-success']) }}
				        
		    		</div>
		    		
		    		<div class='form-group col-sm-8 '></div>
		    		<div class='form-group col-sm-1 '>
		            	{{ Form::button('Delete', ['class' => 'btn btn-danger delete_grammar', 'style' => 'display: none'])}}    
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
	CKEDITOR.plugins.addExternal( 'onchange', '/js/', 'onchangeplugin.js' );
	
	//apply the ckeditor to the intro text
	CKEDITOR.replace( 'intro_text',{toolbar : $mytoolbar, contentsCss : '/css/lrcstyle.css', disableNativeSpellChecker:false, allowedContent : true, extraPlugins : 'onchange', specialChars : [ {{$lesson->language->custom_keyboard_layout}} ] } );
	CKEDITOR.instances['intro_text'].on('change', function() {
		if(this.checkDirty()) {
			$('#update_form').css("background-color", "#EBAD99");
			$('#update_form').attr("dirty", "dirty");
		}
	});

	//apply the ckeditor to the translation
	CKEDITOR.replace( 'lesson_translation',{toolbar : $mytoolbar, contentsCss : '/css/lrcstyle.css', disableNativeSpellChecker:false, allowedContent : true, extraPlugins : 'onchange', specialChars : [ {{$lesson->language->custom_keyboard_layout}} ]}  );
	CKEDITOR.instances['lesson_translation'].on('change', function() {
		if(this.checkDirty()) {
			$('#update_translation_form').css("background-color", "#EBAD99"); 
			$('#update_translation_form').attr("dirty", "dirty");
		}
	});

	//apply the ckeditor to each exisiting grammar
	@foreach ($grammars as $grammar)
		CKEDITOR.replace( 'grammar_text_{{{$grammar->id}}}',{toolbar : $mytoolbar, contentsCss : '/css/lrcstyle.css', disableNativeSpellChecker:false, allowedContent : true, extraPlugins : 'onchange', specialChars : [ {{$lesson->language->custom_keyboard_layout}} ]}  );
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
 
@stop