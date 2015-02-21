@extends('layout')

@section('title') 
{{$series->title}}
@stop

@section('content')

@include('menu_eieol')
@include('menu_series', array('data'=>'data'))
@include('menu_resources', array('data'=>'data'))


	</div> <!-- close menu div -->
</div> <!-- close container for menu -->

<div id="contentmain"> <!-- open div for main content section -->

<!-- end Standard Header for new CoLA-style design -->


<h1>{{$series->title}}</h1>
<h2>Table of Contents</h2>

<h5>Lessons</h5>
@foreach ($lessons as $lesson)
	<a href='/eieol_lesson/{{$series->id}}?id={{$lesson->id}}'>{{$lesson->title}}</a><br/>
@endforeach

<h5>Grammar Points</h5>

@foreach ($lessons as $lesson)
	@foreach($lesson->grammars as $grammar)
		<!-- Add tabs each period -->
		@for ($i = 1; $i <= substr_count($grammar->section_number, '.'); $i++) 
			&nbsp;&nbsp;&nbsp;
		@endfor
		{{ HTML::link('eieol_lesson/' . $series->id . '?id=' . $lesson->id . '#grammar_' . $grammar->id,
					  $grammar->section_number . '.' . $grammar->title,
					  array('title' => $grammar->title )) }}<br/>
	@endforeach
@endforeach

@stop