@extends('layout')

@section('title') Semantic Fields: {{$cat->number}}. {!! $cat->text !!} @stop

@section('content')

<h1>Semantic Fields</h1>
<h2>{{$cat->number}}. {!! $cat->text !!}</h2>

<p>This page lists subcategories for the
    <a href="/lex/semantic" title="Semantic Fields">semantic field</a>
<b>{!! $cat->text !!}</b>.
 
Category numbers are as defined by Carl Darling Buck (cf. <i>A Dictionary of 
Selected Synonyms in the Principal Indo-European Languages</i>, 1949); our field 
&amp; subcategory labels are sometimes adapted from those of Buck: in our scheme 
they are brief but globally unique, parentheses may enclose clues (such as part 
of speech) to interpreting a label, and verb infinitives are introduced by 'to'.</p>

<p>If we have assigned, to a semantic subcategory, Proto-Indo-European lexical 
entries (PIE etyma) drawn from Julius Pokorny's <i lang='de'>Indogermanisches 
etymologisches W&ouml;rterbuch</i> (2 vols, 1959-69), the subcategory label will 
be linked to a page that lists those etyma. On that page, links to PIE etyma will 
lead into our monolithic listing of same, and possibly to separate pages where 
etyma and more modern reflexes thereof (derived words, e.g. in English) are 
shown.</p>

<blockquote><b>N.B.</b> These pages are <b>under construction</b>; as time goes 
on corrections may be made, and more links from these semantic subcategories to 
pages of lexical entries in them, drawn from Pokorny's etyma and reflexes thereof, 
may be added.</blockquote>

<div class="skinny" id="no_bullets">
<ul>
@foreach($fields as $field)
    <li>
        @if ($field->etymas_count > 0)
            <a href="/lex/semantic/field/{{$field->abbr}}" title="PIE etyma in semantic subcategory {!! $field->text  !!}">{{$field->number}}. {!! $field->text !!}</a>
        @else
            {{$field->number}}. {!! $field->text !!}
        @endif
    </li>
@endforeach

</ul>
</div>
@stop


@section('menu')
    @include('menu_menu')
    @include('menu_lex')
    @include('menu_lex_semantic')
@stop
