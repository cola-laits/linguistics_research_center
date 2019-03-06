@extends('layout')

@section('title') 
{!! $series->title !!}
@stop

@section('content')


<h1>{{$series->title}}</h1>
<h2>Table of Contents</h2>

<div class="skinny" id="no_bullets">

<h5>Lessons</h5>
<ol start="0">
@foreach ($series->lessons as $lesson)
	<li><a href='/eieol/{{$series->slug}}/{{$lesson->order}}'>{!! $lesson->title !!}</a></li>
@endforeach
</ol>

<h5>Grammar Points</h5>

<ul>
@foreach ($series->lessons as $lesson)
	@foreach($lesson->grammars as $grammar)
		<li>
			@for ($i = 1; $i <= substr_count($grammar->section_number, '.'); $i++)
				&nbsp;&nbsp;&nbsp;
			@endfor
			<a href='/eieol/{{$series->slug}}/{{$lesson->order}}#grammar_{{$grammar->id}}'>{{$grammar->section_number}}. {!! $grammar->title !!}.</a>
		</li>
	@endforeach
@endforeach
</ul>

</div>
@stop


@section('menu')
	@include('menu_menu')
	@include('menu_series', array('data'=>'data'))
	@include('menu_resources', array('data'=>'data'))
@stop
