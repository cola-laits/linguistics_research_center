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
{{$lesson->lesson_translation}}


@stop