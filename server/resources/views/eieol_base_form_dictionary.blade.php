@extends('layout')

@section('title') 
{{$series->title}}
@stop

@section('content')

<h1>{{$series->title}}</h1>
<h2>{{$language->language}}: Base Form Dictionary</h2>

<p>This Base Form Dictionary index lists, in an alphabetical order suitable to the language and
the script employed for it, every unique base form underlying one or more surface (word) forms
in lesson texts.  For each base form a general meaning (if any) is shown, along with links to
every usage, in every numbered lesson, of the associated surface forms.  With this index, one
may click on any link to perform a quick "usage look-up," and thereby study how surface forms
in texts are constructed from base forms.</p>

<p>A new, experimental feature is being introduced to these EIEOL lessons, namely the addition
of pointers to Proto-Indo-European roots identified by Julius Pokorny in his monumental work,
<i>Indogermanisches Etymologisches W&ouml;rterbuch</i> (2 vols. Bern: Franke, 1959-1969; reprinted
in 1989).  Because Pokorny is becoming increasingly outdated, we may revise these links in the 
future; however, for the time being, this information might prove interesting to those who are 
curious about Indo-European etymology. Notice of potential error is always welcome.</p>



<div class="skinny" id="no_bullets">
@foreach ($head_words as $head_word)
    {!! $head_word['display'] !!} --
    @if($head_word['etyma'] != null)
        [<a href="/lex/master/#P{{$head_word['etyma']['id']}}">Pokorny</a>
        <span class='Unicode' lang='ine'>{!! $head_word['etyma']['entry'] !!} </span> <strong>::</strong> {!! $head_word['etyma']['gloss'] !!}] &nbsp; --
    @endif

    <ul>
        @foreach ($head_word['glossed_text_gloss_ids'] as $id => $lesson)
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
    @include('menu_series', array('data'=>'data'))
    @include('menu_resources', array('data'=>'data'))
@stop
