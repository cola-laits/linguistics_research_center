@extends($printable ? 'printable_layout' : 'layout')

@section('title') {!! strip_tags($lesson->title) !!}@stop

@section('content')
@if (!$printable)

<script type="text/javascript">
    window.clicked_gloss_ids = new Set();
    function update_gloss_visibility() {
        $('.click_gloss').removeClass('clicked');
        $('.gloss').hide();
        window.clicked_gloss_ids.forEach(function (id) {
            $('.click_gloss').each(function (ix, el) {
                var has_any_clicked_gloss = false;
                if ($(el).data('gloss-ids').indexOf(id) > -1) {
                    has_any_clicked_gloss = true;
                }
                if (has_any_clicked_gloss) {
                    $(el).addClass('clicked');
                    $('#gloss_pivot_' + id).show();
                }
            });
        });
    }

    $(document).ready(function(){

        //if the came from the gloss, dictionary or meaning index, we need to open that gloss
        var anchor = window.location.hash.substring(1);
        if (anchor != '') {
            var splits = anchor.split("_");
            var id = splits[splits.length - 1];
            window.clicked_gloss_ids.add(parseInt(id));
            update_gloss_visibility();
        }

        $(".click_gloss").click(function(e){
            // are we trying to open glosses, or close them?
            var closing = $(this).hasClass('clicked');

            var this_gloss_ids = $(this).data('gloss-ids');
            this_gloss_ids.forEach(function(id) {
                if (closing) {
                    window.clicked_gloss_ids.delete(id);
                } else {
                    window.clicked_gloss_ids.add(id);
                }
            });
            update_gloss_visibility();
        }); //click_gloss

        $(".click_gloss").mouseenter(function(e) {
            var this_el = $(this);
            var this_gloss_ids = this_el.data('gloss-ids');
            this_el.parent('span').find('.click_gloss').each(function (ix, el) {
                var that_gloss_ids = $(el).data('gloss-ids');
                var gloss_ids_in_common = this_gloss_ids.filter(function(id) { return that_gloss_ids.indexOf(id) !== -1; });
                if (gloss_ids_in_common.length === 0) {
                    return;
                }
                $(el).css('text-decoration','underline');
                this_gloss_ids.forEach(function(id) {
                    var temp_id = '#gloss_pivot_' + id;
                    $(temp_id).css('text-decoration','underline');
                });
            });
        }).mouseleave(function(e) {
            var this_el = $(this);
            var this_gloss_ids = this_el.data('gloss-ids');
            this_el.parent('span').find('.click_gloss').each(function (ix, el) {
                var that_gloss_ids = $(el).data('gloss-ids');
                var gloss_ids_in_common = this_gloss_ids.filter(function(id) { return that_gloss_ids.indexOf(id) !== -1; });
                if (gloss_ids_in_common.length === 0) {
                    return;
                }
                $(el).css('text-decoration','none');
                this_gloss_ids.forEach(function(id) {
                    var temp_id = '#gloss_pivot_' + id;
                    $(temp_id).css('text-decoration','none');
                });
            });
        });

        $(".expand_all, collapse_all").click(function(e){
            if ($(this).attr('class') == "expand_all"){
                $(this).html("<i class='fa fa-minus-square-o'></i> Collapse All");
                $(this).next('ul').children('li').each(function () {
                    $(this).show();
                });
                $(this).parent().prev(".glossed_text").children(":first").children('a').each(function () {
                    $(this).addClass("clicked");
                });
            } else {
                $(this).html("<i class='fa fa-plus-square-o'></i> Expand All");
                $(this).next('ul').children('li').each(function () {
                    $(this).hide();
                });
                $(this).parent().prev(".glossed_text").children(":first").children('a').each(function () {
                    $(this).removeClass("clicked");
                });
            }
            $(this).toggleClass("expand_all");
            $(this).toggleClass("collapse_all");
        });

    });//document ready

    var old_audio_id = '';
    function playAudio(id) {
        if (old_audio_id && document.getElementById(old_audio_id)) {
            document.getElementById(old_audio_id).pause();
            document.getElementById(old_audio_id).currentTime=0;
        }
        document.getElementById(id).play();
        old_audio_id = id;
    }
</script>

@endif

<h1>{{$series->title}}</h1>
{!! $lesson->intro_text !!}
<div class="skinny">
@foreach ($lesson->glossed_texts as $glossed_text)
    @if ($clickable)
            <div class="glossed_text">
                @if (!$printable && $glossed_text->audio_url)
                <a href="{{$glossed_text->audio_url}}" onclick="playAudio('audio_{{$glossed_text->id}}');return false;"><i class="fa fa-volume-up"></i></a>
                <audio id="audio_{{$glossed_text->id}}" src="{{$glossed_text->audio_url}}"></audio>
                @endif
                <span lang='{{$lesson->language->lang_attribute}}'>{!! $glossed_text->clickable_gloss_text() !!}</span>
            </div>
        <div class="boxey">
            <a href="#" onclick="return false" class="expand_all"><i class='fa fa-plus-square-o'></i> Expand All</a>
            <ul>
                @foreach ($glossed_text->glosses as $gloss)
                    <li id='gloss_pivot_{{$gloss->id}}' class='gloss'>
                        <a name='glossed_text_gloss_{{$gloss->id}}'></a>

                        <span lang='{{$gloss->language->lang_attribute}}'>{!! $gloss->surface_form !!}</span>
                        <span style="white-space: nowrap">--</span>
                        @foreach ($gloss->elements as $element)
                            {{$element->part_of_speech}};
                            {{$element->analysis}}
                            <span style='white-space: nowrap' lang='{{$element->head_word->language->lang_attribute}}'> {{$element->head_word->word}}</span> {!! $element->head_word->definition !!}
                            @if (!$loop->last)
                                +
                            @endif
                        @endforeach
                        <span style="white-space: nowrap">--</span>
                        <strong>{{$gloss->contextual_gloss}}</strong>
                        @if ($gloss->comments)
                            # {!! $gloss->comments !!}
                        @endif

                        @if ($gloss->underlying_form)
                            <br/>
                            <span lang="{{$gloss->language->lang_attribute}}" style="margin-left:10px;">({{$gloss->underlying_form}})</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="glossed_text"><span lang='{{$lesson->language->lang_attribute}}'>{!! $glossed_text->glossed_text !!}</span></div>
        <div class="boxey">
            <ul>
                @foreach ($glossed_text->glosses as $gloss)
                    <li id='old_gloss_pivot_{{$gloss->id}}'>
                        <a name='glossed_text_gloss_{{$gloss->id}}'></a>

                        <span lang='{{$gloss->language->lang_attribute}}'>{!! $gloss->surface_form !!}</span>
                        <span style="white-space: nowrap">--</span>
                        @foreach ($gloss->elements as $element)
                            {{$element->part_of_speech}};
                            {{$element->analysis}}
                            <span style='white-space: nowrap' lang='{{$element->head_word->language->lang_attribute}}'> {{$element->head_word->word}}</span> {!! $element->head_word->definition !!}
                            @if (!$loop->last)
                                +
                            @endif
                        @endforeach
                        <span style="white-space: nowrap">--</span>
                        <strong>{{$gloss->contextual_gloss}}</strong>
                        @if ($gloss->comments)
                          # {!! $gloss->comments !!}
                        @endif

                        @if ($gloss->underlying_form)
                            <br/>
                            <span lang="{{$gloss->language->lang_attribute}}" style="margin-left:10px;">({{$gloss->underlying_form}})</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    <br/>
@endforeach

@if ($lesson->lesson_text)
    <h2>Lesson Text</h2>
    <div class='unbreakable'>
        <blockquote>
            <span lang='{{$lesson->language->lang_attribute}}'>
                {!! $lesson->lesson_text !!}
            </span>
        </blockquote>
    </div>
@endif

@if ($lesson->lesson_translation != '')
    <h2>Translation</h2>
    <div class='unbreakable'>
        {!! $lesson->lesson_translation !!}
    </div>
@endif

@if (count($lesson->grammars) != 0)
    <h2>Grammar</h2>
    @foreach ($lesson->grammars as $grammar)
        <a name='grammar_{{$grammar->id}}'></a>
        <h5>{{$grammar->section_number}} {!! $grammar->title !!}</h5>
        {!! $grammar->grammar_text !!}
    @endforeach
@endif


<!-- If intro, display the list of lessons -->
@if ($lesson->order == 0 and !$printable)
    <h5>The {{$series->menu_name}} Lessons</h5>
    <ol>
    @foreach ($series->lessons as $temp_lesson)
        @if ($temp_lesson->order != 0)
            <li>
                <a href='/eieol/{{$series->slug}}/{{$temp_lesson->order}}'>{!! $temp_lesson->title !!}</a>
            </li>
        @endif
    @endforeach
    </ol>

    <h6>Options:</h6>

    <ul>
        <li>Show full <a href="/eieol_toc/{{$series->slug}}">Table of Contents</a> with Grammar Points index</li>
        @foreach($series->lesson_languages as $language)
            <li>Open a <a href="/eieol_master_gloss/{{$series->slug}}/{{$language->id}}">Master Glossary window</a> for these {{$language->language}} texts</li>
        @endforeach
        @foreach($series->lesson_languages as $language)
            <li>Open a <a href="/eieol_base_form_dictionary/{{$series->slug}}/{{$language->id}}">Base Form Dictionary window</a> for these {{$language->language}} texts</li>
        @endforeach
        @foreach($series->lesson_languages as $language)
            <li>Open an <a href="/eieol_english_meaning_index/{{$series->slug}}/{{$language->id}}">English Meaning Index window</a> for these {{$language->language}} texts</li>
        @endforeach
    </ul>

@endif




@if (!$printable)

    <p class='center'>
        @if ($lesson->prevLesson())
            <a href="/eieol/{{$series->slug}}/{{$lesson->prevLesson()->order}}" title="previous lesson">previous lesson</a>
        @else
            first lesson
        @endif
        &nbsp; | &nbsp;
        @if ($lesson->nextLesson())
            <a href="/eieol/{{$series->slug}}/{{$lesson->nextLesson()->order}}" title="next lesson">next lesson</a>
        @else
            last lesson
        @endif
    </p>

@endif

</div>
@stop


@section('menu')
    @include('menu_menu')
    @include('menu_resources', array('data'=>'data'))
@stop
