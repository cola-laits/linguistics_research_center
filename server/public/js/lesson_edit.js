/*
FIXME todo:
Ajax spinner logic needs to move to axios instead
headword save is still stubbed
 */

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
                flash_modal(json['message']);
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


window.onbeforeunload = function() {
    if ($("form[dirty]").length > 0) {
        return 'You have unsaved changes!  Would you like to leave this page anyway?';
    }
};

$(document).ready(function(){

    //set language js variable so we can use it for the gloss, head word and keyword lookups
    hold_language_id = window.lesson_language_id;

    $('[data-toggle="popover"]').popover();

});
