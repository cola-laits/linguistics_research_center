@extends('layout')

@section('title') 
{{$series->title}}
@stop

@section('content')


<h1>Early Indo-European Texts</h1>
<h2>{{$series->title}}</h2>

<div class="skinny" id="no_bullets">

<ol>
@foreach ($lessons as $lesson)
	@if ($lesson->order == 0 || strstr($lesson->title, 'Bibliography') == true || strstr($lesson->title, 'Appendix') == true)
	
	@else
		<a href='/eieol_text/{{$series->id}}?id={{$lesson->id}}&language_id={{$language_id}}'><li>{{$lesson->title}}</li></a>
	@endif
@endforeach
</ol>

</div>
@stop


@section('menu')
	@include('menu_menu')
@stop