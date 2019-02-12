@extends($printable ? 'printable_layout' : 'layout')

@section('title') {!! strip_tags($lesson->title) !!}@stop

@section('meta') {{$lesson->series->meta_tags}} @stop

@section('content')
@if (!$printable) 

<script type="text/javascript">
    $(document).ready(function(){

        //if the came from the gloss, dictionary or meaning index, we need to open that gloss
        var anchor = window.location.hash.substring(1);
        if (anchor != '') {
            splits = anchor.split("_");
            id = splits[splits.length - 1];
            var temp_id = '#gloss_pivot_' + id;
            $(temp_id).slideToggle('fast');
            var temp_id = '#pivot_' + id;
            $(temp_id).toggleClass("clicked");
        }

        $(".click_gloss").click(function(e){
            //when they click on a word in the glossed_text, this opens the corresponding gloss
            $(this).toggleClass('clicked');
            var temp_id = '#gloss_' + $(this).attr('id');
            $(temp_id).toggleClass('gloss');
        }); //click_gloss

        $(".expand_all, collapse_all").click(function(e){
            if ($(this).attr('class') == "expand_all"){
                $(this).html("<i class='fa fa-minus-square-o'></i> Collapse All");
                $(this).next('ul').children('li').each(function () {
                    $(this).removeClass('gloss');
                });
                $(this).parent().prev(".glossed_text").children(":first").children('a').each(function () {
                    $(this).addClass("clicked");
                });
            } else {
                $(this).html("<i class='fa fa-plus-square-o'></i> Expand All");
                $(this).next('ul').children('li').each(function () {
                    $(this).addClass('gloss');
                });
                $(this).parent().prev(".glossed_text").children(":first").children('a').each(function () {
                    $(this).removeClass("clicked");
                });
            }
            $(this).toggleClass("expand_all");
            $(this).toggleClass("collapse_all");
        });

    });//document ready
</script>

@endif

<h1>{{$series->title}}</h1>
{!! $lesson->intro_text !!}
<div class="skinny">
@foreach ($lesson->glossed_texts as $glossed_text)
    @if ($clickable)
        <div class="glossed_text"><span lang='{{$lesson->language->lang_attribute}}' class='{{$lesson->language->class_attribute}}'>{!! $glossed_text->clickable_gloss_text() !!}</span></div>
        <div class="boxey">
            <a href="#" onclick="return false" class="expand_all"><i class='fa fa-plus-square-o'></i> Expand All</a>
            <ul>
                @foreach ($glossed_text->glosses as $gloss)
                    <li id='gloss_pivot_{{$gloss->pivot->id}}' class='gloss'>
                        <a name='glossed_text_gloss_{{$gloss->pivot->id}}'></a>
                        {!! $gloss->getDisplayGloss() !!}
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="glossed_text"><span lang='{{$lesson->language->lang_attribute}}' class='{{$lesson->language->class_attribute}}'>{!! $glossed_text->glossed_text !!}</span></div>
        <div class="boxey">
            <ul>
                @foreach ($glossed_text->glosses as $gloss)
                    <li id='old_gloss_pivot_{{$gloss->pivot->id}}'>
                        <a name='glossed_text_gloss_{{$gloss->pivot->id}}'></a>
                        {!! $gloss->getDisplayGloss() !!}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    <br/>
@endforeach

@if ($lesson->getLessonText() != '')
    <h2>Lesson Text</h2>
    <div class='unbreakable'>
        <blockquote>
            <span lang='{{$lesson->language->lang_attribute}}' class='{{$lesson->language->class_attribute}}'>
                {!! $lesson->getLessonText() !!}
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
    @foreach ($lessons as $temp_lesson)
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
        @foreach($languages as $language)
            <li>Open a <a href="/eieol_master_gloss/{{$series->slug}}/{{$language->id}}">Master Glossary window</a> for these {{$language->language}} texts</li>
        @endforeach
        @foreach($languages as $language)
            <li>Open a <a href="/eieol_base_form_dictionary/{{$series->slug}}/{{$language->id}}">Base Form Dictionary window</a> for these {{$language->language}} texts</li>
        @endforeach
        @foreach($languages as $language)
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
    @include('menu_series', array('data'=>'data'))
    @include('menu_resources', array('data'=>'data'))
@stop
