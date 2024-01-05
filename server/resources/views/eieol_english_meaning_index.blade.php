@extends('layout')

@section('title')
{{$series->title}}
@stop

@section('content')

<h1>{{$series->title}}</h1>
<h2>{{$language->language}}: English Meaning Index</h2>

<p>This English Index lists, in alphabetical order, <i>seemingly significant</i> words used in
the "general meaning" glosses of Base Forms underlying one or more surface (word) forms in lesson
texts.  For each English word, base forms having that word in their general meanings are shown,
along with links to every usage, in every numbered lesson, of the associated surface forms.  With
this index, one may click on any link to perform a quick "usage look-up," and thereby study how
surface forms in texts are constructed from base forms sharing a general English meaning word.
In order to reduce clutter in this index, certain high-frequency English "function" words are
<i>ignored</i> unless there are no "content" words in the meaning.</p>

<p>This index was created by software, not by human hand, using lesson materials authored with
very few constraints on the expression of "meaning" other than it being in English.  Selecting
<i>significant</i> words from free-form text via software is a notoriously difficult task.
For example, while most meanings in these lessons are expressed as "glosses," some or parts may be
comments directed toward the human reader, who is expected to discern the difference.  Computer
software cannot yet make such subtle linguistic distinctions, so all words in a meaning are treated
alike.  With few exceptions, then, all words are indexed.  The results are neither produced nor
presented with any claims to superior linguistic sensitivity, but at least the full meaning texts
are shown for your inspection and judgment.  Modest effort was invested in tuning the software to
ignore [apparently] superfluous, high-frequency words (e.g., the preposition "of") that, in the
presence of [apparently] more important words, contribute little or no useful information to an
index; unfortunately this may result in some words, in some contexts, being unfairly omitted.</p>
<br/><br/><br/>

<div class="skinny" id="no_bullets">
@foreach ($keywords as $keyword)
    <strong>{{$keyword['keyword']}}</strong> :
        <span style='white-space: nowrap' lang='{{$keyword['head_word']->language->lang_attribute}}'> &lt;{!! $keyword['head_word']->word_without_surrounding_angle_brackets !!}&gt; </span> {!! $keyword['head_word']->definition !!}
        --
    <ul>
        @foreach ($keyword['glossed_text_gloss_ids'] as $id => $lesson)
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
