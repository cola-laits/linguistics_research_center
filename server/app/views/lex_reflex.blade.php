@extends('layout')

@section('title') Early Indo-European Online: Introduction to the Language Lessons @stop

@section('content')

<h1>Indo-European Lexicon</h1>
<h2>PIE Etymon and IE Reflexes</h2>

<p>Below we display: a Proto-Indo-European (PIE) etymon adapted from Pokorny, with our 
own English gloss; our Semantic Field <span style="white-space: nowrap">assignment(s)</span> for the etymon, linked 
to information about the <span style="white-space: nowrap">field(s)</span>; an optional Comment; and Reflexes (derived 
words) in various Indo-European languages, organized by family/group in west-to-east order 
where Germanic is split into West/North/East families and English, our language of primary 
emphasis, is artificially separated from West Germanic. IE Reflexes appear most often as 
single words with any optional <span style="white-space: nowrap">letter(s)</span> enclosed in parentheses; but 
alternative full spellings are separated by '/' and "principal parts" appear in a standard 
order (e.g. masculine, feminine, and neuter forms) separated by commas.</p>

<p>Reflexes are annotated with: Part-of-Speech and/or other Grammatical <span style="white-space: nowrap">feature(s)</span>; 
a short Gloss which, especially for modern English reflexes, may be confined to the 
<i>oldest</i> sense; and some Source <span style="white-space: nowrap">citation(s)</span> with 'LRC' <i>always</i> 
understood as editor. Keys to PoS/Gram feature abbreviations and Source codes appear 
below the reflexes; at the end are links to the previous/next etyma [in Pokorny's
alphabetic order] that have reflexes.</p>

<p>All reflex pages are currently <b>under active construction</b>; as time goes on,
corrections may be made and/or more etyma &amp; reflexes may be added.</p>




<p><b>Pokorny Etymon</b>: <span class='Unicode' lang='ine'>{{$etyma->entry}}</span> &nbsp; '{{$etyma->gloss}}'</p>
<p><b>Semantic Field(s)</b>: 
@foreach($etyma->semantic_fields as $index => $semantic_field)
	{{ HTML::link('lex_semantic_field/' . $semantic_field->id, $semantic_field->text ) }}@if ($index+1 != count($etyma->semantic_fields)),@endif
@endforeach
</p>

<p>&nbsp;</p>

<p><b>Indo-European Reflexes</b>:</p>
<table>
<tr>
	<th scope='col'>Family/Language</th>
	<th scope='col'><span style="white-space: nowrap">Reflex(es)</span></th>
	<th scope='col' class='center'>PoS/Gram.</th>
	<th scope='col'>Gloss</th>
	<th scope='col' class='center'><span style="white-space: nowrap">Source(s)</span></th>
</tr>

{{-- */$prev_lang='';/* --}}
{{-- */$prev_family='';/* --}}
@foreach($etyma->reflexes as $reflex)
	@if ($prev_family != $reflex->language->displayFamily())
		<tr>
			<td><strong>{{$reflex->language->displayFamily()}}</strong></td>
			<td colspan='4'>&nbsp;</td>
		</tr>
		{{-- */$prev_family=$reflex->language->displayFamily();/* --}}
	@endif
	
	<tr>
		@if ($prev_lang == $reflex->language->name)
			<td></td>
		@else
			<td id='{{$reflex->language->abbr}}'><span class='right'>{{$reflex->language->name}}: </span></td>
			{{-- */$prev_lang=$reflex->language->name;/* --}}
		@endif
		<td>
			@foreach($reflex->entries as $index => $entry)
				<span class='{{$reflex->class_attribute}}' lang='{{$reflex->lang_attribute}}'>{{$entry->entry}}</span>@if ($index+1 != count($reflex->entries)),@endif
			@endforeach
		</td>
		<td class='center'>{{$reflex->getDisplayPartsOfSpeech()}}</td>
		<td>{{$reflex->gloss}}</td>
		<td class='center'>{{$reflex->getDisplaySources()}}</td>
	</tr>
@endforeach

</table>
<p>&nbsp;</p>

<p><b>Key to Part-of-Speech/Grammatical feature abbreviations</b>:</p>
<table>
	<tr><th scope='col'>Abbrev.</th><td>&nbsp;</td><th scope='col'>Meaning</th></tr>
	@foreach($etyma->getPOSes() as $pos => $display)
		<tr><td>{{$pos}}</td><td>=</td><td>{{$display}}</td></tr>
	@endforeach
</table>

<p><b>Key to information Source codes</b> (<i>always</i> with 'LRC' as editor):</p>
<table>
	<tr><th scope='col'>Code</th><td>&nbsp;</td><th scope='col'>Citation</th></tr>
	@foreach($etyma->getSources() as $code => $display)
		<tr><td>{{$code}}</td><td>=</td><td>{{$display}}</td></tr>
	@endforeach
</table>

<p class='center'>Nearby etymon: &nbsp;&nbsp; 
	@if ($etyma->prevEtyma())
		{{ HTML::link('lex/' . $etyma->prevEtyma()->old_id, 'previous', array('title' => 'previous etymon with reflexes' )) }}
	@else
		first
	@endif
	&nbsp; | &nbsp;
	@if ($etyma->nextEtyma())
		{{ HTML::link('lex/' . $etyma->nextEtyma()->old_id, 'next', array('title' => 'next etymon with reflexes' )) }}
	@else
		last
	@endif
</p>
@stop


@section('menu')
	@include('menu_menu')
	@include('menu_lex')
@stop