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
                           'class' => 'form modal_form',
                           'onsubmit' => 'ajax_submit(this); return false;',
                           'id' => 'edit_head_word_form'
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
                            {{ Form::select('etyma_id', $etymas, null, ['class' => 'form-control etyma', 'id' => 'etyma_id']) }}
                            <div id="etyma_id_error" class="alert-danger errors"></div>
                        </div>

                        <div class='form-group'>
                            {{ Form::label('keywords', 'Keywords') }}
                            {{ Form::text('keywords', null, ['class' => 'form-control keywords', 'id' => 'edit_keywords']) }}
                            <div class="alert-warning">Separate with commas</div>
                            <div id="keywords_error" class="alert-danger errors"></div>
                        </div>

                        <div class='form-group bottom_button'>
                            {{ Form::submit('Save', ['class' => 'btn btn-sm btn-primary']) }}
                        </div>

                        {{ Form::close() }}
                    </div>
                </div>

                <div class="card"><div class="card-body" id="head_word_glosses"></div></div>
            </div>

        </div>
    </div>
</div>
