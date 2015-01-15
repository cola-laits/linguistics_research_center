@extends('layout')

@section('title') {{$lesson->title}}@stop

@section('content')

@include('menu_eieol')
@include('menu_series', array('data'=>'data'))
@include('menu_resources', array('data'=>'data'))


	</div> <!-- close menu div -->
</div> <!-- close container for menu -->

<div id="contentmain"> <!-- open div for main content section -->

<!-- end Standard Header for new CoLA-style design -->

<h1>{{$lesson->title}}</h1>
{{$lesson->intro_text}}

@foreach ($lesson->glossed_texts as $glossed_text)
	<div class="glossed_text">{{$glossed_text->glossed_text}}</div>
	<br/><br/>
	<ul>
	@foreach ($glossed_text->glosses as $gloss)
   		<li>
   			<a name='gloss_{{$gloss->id}}'></a>
    		{{$gloss->getDisplayGloss()}}<br/>
    	</li>
    @endforeach
    </ul>
    <br/>
@endforeach

@if ($lesson_text != '')
	<h2>Lesson Text</h2>
	{{$lesson_text}}
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


<!-- If there is no lesson text, assume this is the intro and display the list of lessons -->
@if ($lesson_text == '') 
	<h1>The Lessons</h1>
	<ul>
	@foreach ($lessons as $lesson)
		<li>{{ HTML::link('eieol_lesson/' . $series->id . '?id=' . $lesson->id, $lesson->title, array('title' => $lesson->title )) }}</li>
	@endforeach
	</ul>
	 
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