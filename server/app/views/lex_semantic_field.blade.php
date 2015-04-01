@extends('layout')

@section('title') Early Indo-European Online: Introduction to the Language Lessons @stop

@section('content')

<img src="http://www.utexas.edu/cola/centers/lrc/images/dieboldsm.jpg" alt="A. Richard Diebold Center for Indo-European Language and Culture" border="2" /><br /><br />
@include('menu_lex_semantic')


	</div> <!-- close menu div -->
</div> <!-- close container for menu -->

<div id="contentmain"> <!-- open div for main content section -->

<!-- end Standard Header for new CoLA-style design -->



<h1>Proto-Indo-European Etyma</h1>
<h2>{{$field->semantic_category->number}}. {{$field->semantic_category->text}}</h2>
<h3>{{$field->number}}. {{$field->text}}</h3>

<p>This page lists Proto-Indo-European lexical entries (PIE etyma) drawn from Julius 
Pokorny's <i lang='de'>Indogermanisches etymologisches W&ouml;rterbuch</i> (2 vols, 
1959-69) assigned to the 
{{ HTML::link('lex_semantic', 'semantic field', array('title' => 'Semantic Fields' )) }}
<b>{{$field->semantic_category->text}}</b>, subcategory <b>{{$field->number}}. {{$field->text}}</b>. Category numbers are as defined by Carl 
Darling Buck (cf. <i>A Dictionary of Selected Synonyms in the Principal Indo-European 
Languages</i>, 1949); our field &amp; subcategory labels are sometimes adapted from 
those of Buck: in our scheme they are brief but globally unique, parentheses may 
enclose clues (such as part of speech) to interpreting a label, and verb infinitives 
are introduced by 'to'.</p>

Links to PIE etyma will lead into a monolithic tabular listing of same, which opens in a new 
window; if available, links are given to separate pages showing etyma and more modern 
Indo-European <b>reflexes</b> thereof (i.e. derived words, glossed in English).</p>

<blockquote><b>N.B.</b> These pages are <b>under construction</b>; as time goes on 
corrections may be made, and more links from this semantic subcategory to lexical 
entries in it, drawn from Pokorny's etyma, may be added. Finally, derived reflexes 
of PIE etyma, in any number of IE languages, may be added at any time.</blockquote>

<ul>

@foreach($field->etymas as $etyma)
	<li>
		<span class='Unicode' lang='ine'>{{$etyma->entry}}</span> 
		&nbsp;
		{{ HTML::link('lex_pokorny/#P' . $etyma->id, $etyma->gloss, array('title' => 'Pokorny PIE Data', 'target' => 'new' )) }}
		@if (count($etyma->reflex_count) > 0)
			&nbsp; 
			{{ HTML::link('lex_reflex/' . $etyma->id, 'reflex', array('title' => 'Pokorny PIE Data with reflexes' )) }}
		@endif
	</li>
@endforeach

</ul>

    
    
@stop