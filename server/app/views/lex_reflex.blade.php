@extends('layout')

@section('title') Early Indo-European Online: Introduction to the Language Lessons @stop

@section('content')

@include('menu_lex')


	</div> <!-- close menu div -->
</div> <!-- close container for menu -->

<div id="contentmain"> <!-- open div for main content section -->

<!-- end Standard Header for new CoLA-style design -->

<h1>Indo-European Lexicon</h1>
<h2>PIE Etymon and IE Reflexes</h2>

<p>Below we display: a Proto-Indo-European (PIE) etymon adapted from Pokorny, with our 
own English gloss; our Semantic Field <nobr>assignment(s)</nobr> for the etymon, linked 
to information about the <nobr>field(s)</nobr>; an optional Comment; and Reflexes (derived 
words) in various Indo-European languages, organized by family/group in west-to-east order 
where Germanic is split into West/North/East families and English, our language of primary 
emphasis, is artificially separated from West Germanic. IE Reflexes appear most often as 
single words with any optional <nobr>letter(s)</nobr> enclosed in parentheses; but 
alternative full spellings are separated by '/' and "principal parts" appear in a standard 
order (e.g. masculine, feminine, and neuter forms) separated by commas.</p>

<p>Reflexes are annotated with: Part-of-Speech and/or other Grammatical <nobr>feature(s)</nobr>; 
a short Gloss which, especially for modern English reflexes, may be confined to the 
<i>oldest</i> sense; and some Source <nobr>citation(s)</nobr> with 'LRC' <i>always</i> 
understood as editor. Keys to PoS/Gram feature abbreviations and Source codes appear 
below the reflexes; at the end are links to the previous/next etyma [in Pokorny's
alphabetic order] that have reflexes.</p>

<p>All reflex pages are currently <b>under active construction</b>; as time goes on,
corrections may be made and/or more etyma &amp; reflexes may be added.</p>




<p><b>Pokorny Etymon</b>: <span class='Unicode' lang='ine'>{{$etyma->entry}}</span> &nbsp; ({{$etyma->gloss}})</p>
<p><b>Semantic Field(s)</b>: 
@foreach($etyma->semantic_fields as $index => $semantic_field)
	{{ HTML::link('lex_semantic_field/' . $semantic_field->id, $semantic_field->text ) }}</a>@if ($index+1 != count($etyma->semantic_fields)),@endif
@endforeach
</p>

<p>&nbsp;</p>

<p><b>Indo-European Reflexes</b>:</p>
<center><table border='0' summary='Indo-European reflexes'>
<tr>
	<th scope='col'>Family/Language</th>
	<th scope='col'><nobr>Reflex(es)</nobr></th>
	<th scope='col' class='center'>PoS/Gram.</th>
	<th scope='col'>Gloss</th>
	<th scope='col' class='center'><nobr>Source(s)</nobr></th>
</tr>

{{-- */$prev_lang='';/* --}}
{{-- */$prev_family='';/* --}}
@foreach($etyma->reflexes as $reflex)
	@if ($prev_family != $reflex->language->language_sub_family->language_family->name)
		<tr>
			<td><strong>{{$reflex->language->language_sub_family->language_family->name}}</strong></td>
			<td colspan='8'>&nbsp;</td>
		</tr>
		{{-- */$prev_family=$reflex->language->language_sub_family->language_family->name;/* --}}
	@endif
	
	<tr>
		@if ($prev_lang == $reflex->language->name)
			<td class='right'></td>
		@else
			<td class='right' id='{{$reflex->language->abbr}}'>{{$reflex->language->name}}:</td>
			{{-- */$prev_lang=$reflex->language->name;/* --}}
		@endif
		<td><span class='{{$reflex->class_attribute}}' lang='{{$reflex->lang_attribute}}'>{{$reflex->reflex}}</span></td>
		<td class='center'>{{$reflex->getDisplayPartsOfSpeech()}}</td>
		<td>{{$reflex->gloss}}</td>
		<td class='center'>{{$reflex->getDisplaySources()}}</td>
	</tr>
@endforeach

</table></center>
<p>&nbsp;</p>

<p><b>Key to Part-of-Speech/Grammatical feature abbreviations</b>:</p>
<table border='0' summary='part-of-speech and grammatical feature abbreviations'>
	<tr><th scope='col'>Abbrev.</th><td>&nbsp;</td><th scope='col'>Meaning</th></tr>
	@foreach($poses as $pos => $display)
		<tr><td>{{$pos}}</td><td>=</td><td>{{$display}}</td></tr>
	@endforeach
</table>

<p><b>Key to information Source codes</b> (<i>always</i> with 'LRC' as editor):</p>
<table border='0' summary='information source codes'>
	<tr><th scope='col'>Code</th><td>&nbsp;</td><th scope='col'>Citation</th></tr>
	@foreach($sources as $code => $display)
		<tr><td>{{$code}}</td><td>=</td><td>{{$display}}</td></tr>
	@endforeach
</table>

<p class='center'>Nearby etymon: &nbsp;&nbsp; 
	@if ($prev_etyma)
		{{ HTML::link('lex_reflex/' . $prev_etyma->id, 'previous', array('title' => 'previous etymon with reflexes' )) }}
	@else
		first
	@endif
	&nbsp; | &nbsp;
	@if ($next_etyma)
		{{ HTML::link('lex_reflex/' . $next_etyma->id, 'next', array('title' => 'next etymon with reflexes' )) }}
	@else
		last
	@endif
</p>
    
@stop