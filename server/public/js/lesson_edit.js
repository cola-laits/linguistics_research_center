
function ajax_submit(form) {
    //generic ajax function.   This will prevent the regular submission and send it by ajax instead.

    var myform = $(form);

    $(".spinner").show();

    //get values from CKEditor and put them into textarea fields
    for (var instance in CKEDITOR.instances )
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
                myform.removeAttr("dirty");

                //if they updated the language, we need to change the hidden language ids
                if(json.hasOwnProperty('language_id')) {
                    $(".language_id_class").each(function() {
                        $(this).attr('value',json['language_id']);
                        hold_language_id = json['language_id'];
                    });
                }

                //if they updated a gloss, we need to change the text of every occurrence of it on the page
                if(json.hasOwnProperty('gloss_id')) {
                    $(".gloss_" + json['gloss_id']).each(function() {
                        $(this).html(json['gloss_display']);

                        if (json['author_done']) {
                            $(this).next('.gloss_comment_indicator').html('<div style="color:green"><i class="fa fa-comments"></i></div>');
                        } else if (json['author_comments'] || json['admin_comments']) {
                            $(this).next('.gloss_comment_indicator').html('<div style="color:red"><i class="fa fa-comments"></i></div>');
                        } else {
                            $(this).next('.gloss_comment_indicator').html('');
                        }

                    });
                }
            } //json success

            if(json['added']) { //if we just performed an add, we need to change the form to an update form
                $(formDiv).find(":submit").attr('value','Edit');
                $(formDiv).find(":submit").attr('class', 'btn btn-xs btn-primary');
                $(formDiv).attr("action", json['action']);
                $('<input>').attr({type: 'hidden', value: 'PUT', name: '_method'}).appendTo(formDiv);

                //if they just added a glossed text, we need to further customize the form
                if (json.hasOwnProperty('glossed_text_id')) {
                    $("#add_glossed_text").show(); //now that they've saved the glossed text, they can add another

                    //rename div, form and text area
                    var new_form_id = 'glossed_text_form_' + json['glossed_text_id'];
                    var new_div_id = 'glossed_text_div_' + json['glossed_text_id'];
                    var new_text_id = 'glossed_text_' + json['glossed_text_id'];
                    $('#new_glossed_text_div').find('#attach_gloss_form').find("#glossed_text_id").attr('value',json['glossed_text_id']);
                    $('#new_glossed_text_div').find('#attach_gloss_form').find("#attach_gloss_button").show();
                    $('#new_glossed_text_div').attr("id",new_div_id);
                    $('#new_glossed_text_form').find('#new_glossed_text').attr("id",new_text_id);
                    $('#new_glossed_text_form').attr("id", new_form_id);
                    $('#new_glossed_text_glosses').attr("id",'glossed_text_' + json['glossed_text_id'] + '_glosses');
                    $('#' +  new_form_id).find('.delete_glossed_text').show();
                }


                //if they just added a grammar, we need to further customize the form
                if (json.hasOwnProperty('grammar_id')) {
                    $("#add_grammar").show(); //now that they've saved the grammar, they can add another

                    //rename div, form and text area
                    var new_form_id = 'grammar_form_' + json['grammar_id'];
                    var new_text_id = 'grammar_text_' + json['grammar_id'];
                    $('#grammars').find('#new_grammar_div').attr("id",'grammar_div_' + json['grammar_id']);
                    $('#grammars').find('#new_grammar_form').attr("id",new_form_id);
                    $('#grammars').find('#new_grammar_text').attr("id",new_text_id);
                    $('#grammars').find('.delete_grammar').show();
                }
            }

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
    var temp_div = '#glossed_text_' + window.modal_glossed_text_id + '_glosses'; //get div that surrouds glosses for given glossed text
    $("form", temp_div).each(function() { // get the value of each order
        order = parseInt($('#order', this).val());
        if(order > next_gloss_order) {
            next_gloss_order = order;
        }
    });
    next_gloss_order += 10;

    var mydata = {};
    mydata['existing_gloss_id'] = gloss_id;
    mydata['glossed_text_id'] = window.modal_glossed_text_id; //set when displaying attach modal
    mydata['order'] = next_gloss_order;

    $.ajax({
        type: "POST",
        url:'/admin2/eieol_glossed_text_gloss/copy_gloss',
        data:mydata,
        dataType: "html",

        success : function(data){
            var json = JSON.parse(data);

            if(json['fail']) {
                alert('Ajax Error: ' + json['msg']);
            }  //json fail

            if(json['success']) {
                var new_div_id = "glossed_text_gloss_" + json['gtg_id'] + "_div";
                var new_form_id = "new_glossed_text_gloss_form_" + json['gtg_id'];
                var new_form_action = "/admin2/eieol_glossed_text_gloss/" + json['gtg_id'];

                var new_div = $( "#new_glossed_text_gloss_div" ).clone(true).attr("id",new_div_id);
                new_div.appendTo( temp_div );
                new_div.show();

                $('#new_glossed_text_gloss_form', '#'+new_div_id).find("#order").attr('value',next_gloss_order);
                $('#new_glossed_text_gloss_form', '#'+new_div_id).find("#glossed_text_id").attr('value',window.modal_glossed_text_id);
                $('#new_glossed_text_gloss_form', '#'+new_div_id).find(".gloss_text").html('<br/>' + gloss_text);
                $('#new_glossed_text_gloss_form', '#'+new_div_id).find(".gloss_text").addClass('gloss_' + json['gloss_id']);
                $('#new_glossed_text_gloss_form', '#'+new_div_id).attr("action",new_form_action);
                $('#new_glossed_text_gloss_form', '#'+new_div_id).attr("id",new_form_id);
                $('.delete_glossed_text_gloss', '#' + new_div_id).show();
                $('.delete_glossed_text_gloss_form', '#'+new_div_id).attr("action",new_form_action);
                $('#glossed_text_gloss_id', '#'+new_div_id).val(json['gtg_id']);

                $('#edit_gloss', '#'+new_div_id).find("#gloss_id").attr('value',json['gloss_id']);

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
    $("#attach_head_word_modal").modal('hide');
} //attach head word

//ajax search for glosses
function searchGlosses(gloss) {
    if (gloss.length===0) { //if the search is blank, reset the result box
        document.getElementById("gloss_search_result").innerHTML="";
        return;
    }
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState===4 && xmlhttp.status===200) { //if ajax is successful, load result box
            document.getElementById("gloss_search_result").innerHTML=xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET","/admin2/eieol_gloss/filtered_list?gloss="+gloss+"&language="+hold_language_id,true);
    xmlhttp.send();
}

//ajax search for head words
function searchHeadWords(head_word) {
    if (head_word.length===0) { //if the search is blank, reset the result box
        document.getElementById("head_word_search_result").innerHTML="";
        return;
    }
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState===4 && xmlhttp.status===200) { //if ajax is successful, load result box
            document.getElementById("head_word_search_result").innerHTML=xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET","/admin2/eieol_head_word/filtered_list?head_word="+head_word+"&language="+hold_language_id,true);
    xmlhttp.send();
}

function previewText(ckeditor_inst) {
    var text = CKEDITOR.instances[ckeditor_inst].getData();
    $("#preview_modal #preview_modal_body").html(text);
    $('#preview_modal').modal({});

}


// --------------------------------document ready-------------------------------------

window.onbeforeunload = function() {
    if ($("form[dirty]").length > 0) {
        return 'You have unsaved changes!  Would you like to leave this page anyway?';
    }
};

$(document).ready(function(){

    //set language js variable so we can use it for the gloss, head word and keyword lookups
    hold_language_id = window.lesson_language_id;

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

    //these two functions prevent users from tabbing out of the keywords fields.  We want them to stay and enter a comma after each word
    $('#new_keywords_tag').keypress(function (e) { //listen for typing
        if(e.keyCode === 9){ // tab
            e.preventDefault();
        }
    });
    $('#edit_keywords_tag').keypress(function (e) { //listen for typing
        if(e.keyCode === 9){ // tab
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
                    language_id: window.lesson_language_id,
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
                    language_id: window.lesson_language_id,
                },
                type : 'GET',
                url: '/admin2/eieol_analysis/filtered_list',
                success: function(data) {
                    response(data)

                }
            });
        }
    }); //analysis autocomplete

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
                //clear old values out
                $('#edit_gloss_form')[0].reset();
                //for some reason the reset doesn't reset all the fields
                for (i=1; i<=6; i++) {
                    $('#element_' + i + '_head_word_id', '#edit_gloss_form').val('');
                }

                //clear comment divs out
                $("#gloss_author_comments").html('');
                $("#gloss_admin_comments").html('');

                if (!window.isAdmin || data['author_comments'] || data['author_done']) {
                    //only show if you are not an admin, or if they were filled in.
                    $("#gloss_author_comments").html('<div class="form-group col-sm-9 col-sm-offset-1">\
						    <label for="author_comments">Author Comments</label>\
						    <textarea class="form-control comment_textarea author_comments" name="author_comments" cols="100" rows="2" id="author_comments"></textarea>\
						</div>\
						<div class="form-group col-sm-1">\
						    <label for="author_done">Done</label>\
						    \<input class="form-control author_done" id="gloss_author_done" \
						        name="author_done" type="checkbox" value="1"\
						        checked='+(data.author_done?'checked':'')+'>\
						</div>');
                }

                if (window.isAdmin) {
                    $("#gloss_admin_comments").html('<div class="form-group col-sm-9 col-sm-offset-1">\
							    <label for="admin_comment">Admin Comments</label>\
					    		<textarea class="form-control comment_textarea admin_comments" name="admin_comments" cols="100" rows="2"></textarea>\
							</div>\
							<div class="form-group col-sm-1">\
						        <input class="btn btn-xs btn-warning comment_clear" type="submit" value="Clear">\
							</div>');
                } else {
                    if (data['admin_comments']) {
                        //Only show admin comments to authors if they exist
                        $("#gloss_admin_comments").html('<div class="form-group col-sm-9 col-sm-offset-1">\
								<label for="admin_comment">Admin Comments</label>\
								<input class="form-control" name="admin_comments" type="hidden">\
								<div class="well" style="white-space: pre-wrap" >' + data['admin_comments'] + '</div>\
							</div>');
                    }
                }

                //load form
                $.each(data, function(key, value){
                    if (key === 'author_done') { //checkboxes behave differently
                        if (value == 1) {
                            $("#gloss_author_done").prop('checked', true);
                        }
                    } else {
                        $('[name='+key+']', '#edit_gloss_form').val(value);
                    }
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
                $('#edit_gloss_form').removeAttr("dirty");
                $("#surface_form", "#edit_gloss_form").focus(); //put cursor in first field

                set_comment_button_color();
                listen_to_forms();
                listen_for_clear_comments();
                $("#gloss_comments").hide(); //close comments box in case they left it open on previous editing

            }, //success

            error : function(xml_http_request, text_status, error_thrown) {
                alert('Ajax Error: ' + text_status + '/ ' + xml_http_request + '/ ' + error_thrown);
            } //error

        }); //ajax call



        return false;
    }); //edit gloss


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
        if (head_word_id === '') {
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

                set_comment_button_color();
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

    //show element
    $('.show_element').click(function() {
        var content = $(this).next();
        $(content).slideToggle('slow');
        return false;
    });

    $('[data-toggle="popover"]').popover();

    //This code is needed when we have one modal open another.
    //Without it, when you close the second modal, the first one becomes unscrollable.
    $('.modal').on('hidden.bs.modal', function () {
        if($('.modal').hasClass('in')) {
            $('body').addClass('modal-open');
        }
    });


});//document ready
