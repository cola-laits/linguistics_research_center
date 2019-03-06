@extends('layout')

@section('title') 
{{$series->title}}
@stop

@section('content')


<h1>Early Indo-European Texts</h1>
<h2>{{$series->title}}</h2>

<div class="skinny" id="no_bullets">

<ol>
@foreach ($series->lessons as $lesson)
    @if ($lesson->order == 0 || strstr($lesson->title, 'Bibliography') == true || strstr($lesson->title, 'Appendix') == true)

    @else
        <li><a href='/eieol_text/{{$series->id}}?id={{$lesson->id}}&language_id={{$language_id}}'>{{$lesson->title}}</a></li>
    @endif
@endforeach
</ol>

</div>
@stop


@section('menu')
    @include('menu_menu')
@stop
