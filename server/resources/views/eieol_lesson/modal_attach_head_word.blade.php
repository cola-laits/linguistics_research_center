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
                            <div id="word_error" class="alert-danger errors"></div>
                        </div>

                        <div class='form-group'>
                            {{ Form::label('definition', 'Definition') }}
                            {{ Form::text('definition', null, ['placeholder' => 'Definition', 'class' => 'form-control', 'id' => 'definition']) }}
                            <div id="definition_error" class="alert-danger errors"></div>
                        </div>

                        <div class='form-group'>
                            {{ Form::label('etyma_id', 'Etyma') }}
                            {{ Form::select('etyma_id', $etymas, null, ['class' => 'form-control etyma', 'id' => 'etyma_id', 'placeholder'=>'Select an etymon']) }}
                            <div id="etyma_id_error" class="alert-danger errors"></div>
                        </div>

                        <div class='form-group'>
                            {{ Form::label('keywords', 'Keywords') }}
                            {{ Form::text('keywords', null, ['class' => 'form-control keywords', 'id' => 'new_keywords']) }}
                            <div class="alert-warning">Separate with commas</div>
                            <div id="keywords_error" class="alert-danger errors"></div>
                        </div>

                        <div class='form-group bottom_button'>
                            {{ Form::submit('Add', ['class' => 'btn btn-sm btn-success']) }}
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
