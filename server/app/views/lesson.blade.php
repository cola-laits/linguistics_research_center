@extends('layout')

@section('title') {{$lesson->title}}@stop

@section('content')

@include('menu_eieol')
@include('menu_lesson', array('data'=>'data'))


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

<h2>Lesson Text</h2>
{{$lesson_text}}
	        
<h2>Translation</h2>
{{$lesson->lesson_translation}}

<h2>Grammar</h2>
@foreach ($lesson->grammars as $grammar)
	<h5>{{$grammar->section_number}} {{$grammar->title}}</h5>
	{{$grammar->grammar_text}}
@endforeach

@stop