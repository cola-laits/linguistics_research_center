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
                                    {{ Form::button('Pick Head Word', ['class' => 'btn btn-primary btn-xs pick_head_word_button', 'onclick' => 'element_id =' . $i]) }}
                                    <div id ="element_{{$i}}_head_word_id_error" class="alert-danger errors"></div>
                                </div>

                                @if ($i == 1)
                                    <div class='form-group col-sm-2'>
                                        {{ Form::label('contextual_gloss', 'Contextual Gloss') }}
                                        {{ Form::text('contextual_gloss', null, ['placeholder' => 'Contextual Gloss', 'class' => 'form-control', 'id' => 'contextual_gloss']) }}
                                        <div id ="contextual_gloss_error" class="alert-danger errors"></div>
                                    </div>

                                    <div class='form-group col-sm-1 bottom_button'>
                                        {{ Form::submit('Add', ['class' => 'btn btn-xs btn-success']) }}
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

                <div class='row'>
                    <div class='form-group col-sm-12'>
                        {{ Form::label('underlying_form', 'Underlying Form') }}
                        {{ Form::text('underlying_form', null, ['placeholder' => 'Underlying Form', 'class' => 'form-control', 'id' => 'underlying_form']) }}
                        <div id ="underlying_form_gloss_error" class="alert-danger errors"></div>
                    </div>
                </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
