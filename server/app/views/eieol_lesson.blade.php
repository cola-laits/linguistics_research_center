@extends('layout')

@section('title') {{strip_tags($lesson->title)}}@stop

@section('content')

@include('menu_eieol')
@include('menu_series', array('data'=>'data'))
@include('menu_resources', array('data'=>'data'))


	</div> <!-- close menu div -->
</div> <!-- close container for menu -->

<div id="contentmain"> <!-- open div for main content section -->

<!-- end Standard Header for new CoLA-style design -->

<h1>{{$series->title}}</h1>
{{$lesson->intro_text}}

@foreach ($lesson->glossed_texts as $glossed_text)
	<div class="glossed_text"><span lang='{{$lesson->language->lang_attribute}}' class='{{$lesson->language->class_attribute}}'>{{$glossed_text->glossed_text}}</span></div>
	<br/>
	<ul>
	@foreach ($glossed_text->glosses as $gloss)
   		<li>
   			<a name='glossed_text_gloss_{{$gloss->pivot->id}}'></a>
    		{{$gloss->getDisplayGloss()}}
    	</li>
    @endforeach
    </ul>
    <br/>
@endforeach

@if ($lesson_text != '')
	<h2>Lesson Text</h2>
	<blockquote><span lang='{{$lesson->language->lang_attribute}}' class='{{$lesson->language->class_attribute}}'>{{$lesson_text}}</span></blockquote>
@endif
	      
@if ($lesson->lesson_translation != '')	        
	<h2>Translation</h2>
	{{$lesson->lesson_translation}}
@endif

@if (count($lesson->grammars) != 0)
	<h2>Grammar</h2>
	@foreach ($lesson->grammars as $grammar)
		<a name='grammar_{{$grammar->id}}'></a>
		<h5>{{$grammar->section_number}} {{$grammar->title}}</h5>
		{{$grammar->grammar_text}}
	@endforeach
@endif


<!-- If intro, display the list of lessons -->
@if ($lesson->order == 0) 
	<h5>The {{$series->menu_name}} Lessons</h5>
	<ol>
	@foreach ($lessons as $lesson)
		@if ($lesson->order != 0)
			<li>
				<a href='/eieol_lesson/{{$series->id}}?id={{$lesson->id}}'>{{$lesson->title}}</a>
			</li>
		@endif
	@endforeach
	</ol>
	 
	<h6>Options:</h6>

	<ul>
		<li>Show full {{ HTML::link('eieol_toc/' . $series->id, "Table of Contents")}} with Grammar Points index</li>
		@foreach($languages as $language)
			<li>Open a {{ HTML::link('eieol_master_gloss/' . $series->id . '/' . $language->id, "Master Glossary window")}} for these {{$language->language}} texts</li>
		@endforeach
		@foreach($languages as $language)
			<li>Open a {{ HTML::link('eieol_base_form_dictionary/' . $series->id . '/' . $language->id, "Base Form Dictionary window")}} for these {{$language->language}} texts</li>
		@endforeach
		@foreach($languages as $language)
			<li>Open an {{ HTML::link('eieol_english_meaning_index/' . $series->id . '/' . $language->id, "English Meaning Index window")}} for these {{$language->language}} texts</li>
		@endforeach
	</ul>
	 
@endif

@stop