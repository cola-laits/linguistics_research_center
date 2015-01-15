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
		<h5>{{$grammar->section_number}} {{$grammar->title}}</h5>
		{{$grammar->grammar_text}}
	@endforeach
@endif

@if ($lesson_text == '') <!-- If there is no lesson text, assume this is the intro and display the list of lessons -->
	<h1>The Lessons</h1>
	<ul>
	@foreach ($lessons as $lesson)
		<li>{{ HTML::link('lesson/' . $series_id . '?id=' . $lesson->id, $lesson->title, array('title' => $lesson->title )) }}</li>
	 @endforeach
	 </ul>
	 
	 <h6>Options:</h6>

	<ul>
		<li>Show full {{ HTML::link('toc/' . $series_id, "Table of Contents")}} with Grammar Points index</li>
		@foreach($languages as $language)
			<li>Open a {{ HTML::link('master_gloss/' . $series_id . '/' . $language->id, "Master Glossary window")}} for these {{$language->language}} texts</li>
		@endforeach
		@foreach($languages as $language)
			<li>Open a {{ HTML::link('base_form_dictionary/' . $series_id . '/' . $language->id, "Base Form Dictionary window")}} for these {{$language->language}} texts</li>
		@endforeach
		@foreach($languages as $language)
			<li>Open an {{ HTML::link('english_meaning_index/' . $series_id . '/' . $language->id, "English Meaning Index window")}} for these {{$language->language}} texts</li>
		@endforeach
	</ul>
	 
@endif

@stop