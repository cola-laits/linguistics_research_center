@extends('layout')

@section('title') 
{{$series->title}}
@stop

@section('content')


<h1>{{$series->title}}</h1>
<h2>Table of Contents</h2>

<div class="skinny">

<h5>Lessons</h5>
<ol start="0">
@foreach ($lessons as $lesson)
	<a href='/eieol_lesson/{{$series->id}}?id={{$lesson->id}}'><li>{{$lesson->title}}</li></a>
@endforeach
</ol>

<h5>Grammar Points</h5>

<ul style="list-style: none;">
@foreach ($lessons as $lesson)
	@foreach($lesson->grammars as $grammar)
		<li>
			<!-- Add tabs each period -->
			@for ($i = 1; $i <= substr_count($grammar->section_number, '.'); $i++) 
				&nbsp;&nbsp;&nbsp;
			@endfor
			<a href='/eieol_lesson/{{$series->id}}?id={{$lesson->id}}#grammar_{{$grammar->id}}'>{{$grammar->section_number}}. {{$grammar->title}}.</a>
		</li>
	@endforeach
@endforeach
</ul>

</div>

<!--    
*******************************************
OFFICE NAVIGATION - RELATED LINKS - CONTACT
******************************************* -->
</div>
</div>
<div class="medium-3 medium-pull-9 columns content-secondary-page-navigation"><!-- Office Navigation -->
<hr class="show-for-small-only"/>

@include('menu_menu')
@include('menu_series', array('data'=>'data'))
@include('menu_resources', array('data'=>'data'))
</div>
</div>
 

    
@stop