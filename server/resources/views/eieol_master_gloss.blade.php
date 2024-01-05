@extends('layout')

@section('title')
{{$series->title}}
@stop

@section('content')


<h1>{{$series->title}}</h1>
<h2>{{$language->language}}: Master Glossary</h2>
This Master Glossary page lists, in an alphabetical order suitable to the language and the script employed for it, every unique word
form that appears in lesson texts and, for each word, its unique glosses. In addition to the gloss information, sans contextual translation,
links are provided to every appearance, in every numbered lesson, of the word/gloss in question. With this index one may perform a quick
"word look-up" and, in addition, study how words are used in context by clicking on their links.
<br/><br/><br/>

<div class="skinny" id="no_bullets">
@foreach ($glosses as $gloss)
    <strong><span lang='{{$language->lang_attribute}}'>{!! $gloss['surface_form'] !!}</span></strong> -
    @foreach ($gloss['gloss']->elements as $element)
            {{$element->part_of_speech}};
            {{$element->analysis}}
            <span style='white-space: nowrap' lang='{{$element->head_word->language->lang_attribute}}'> &lt;{!! $element->head_word->word_without_surrounding_angle_brackets !!}&gt;</span> {!! $element->head_word->definition !!}
          @if (!$loop->last)
              +
          @endif
    @endforeach
    <ul>
        @foreach ($gloss['glossed_text_gloss_ids'] as $id => $lesson)
            <li>
                <a href='/eieol/{{$series->slug}}/{{$lesson->order}}#glossed_text_gloss_{{$id}}'>{!! $lesson->title !!}</a>
            </li>
        @endforeach
    </ul>
@endforeach
</div>
@stop


@section('menu')
    @include('menu_menu')
    @include('menu_resources', array('data'=>'data'))
@stop
