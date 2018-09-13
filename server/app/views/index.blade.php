@extends('layout')


@section('content')



<h1 class="header-text">Welcome to the LRC's Lessons & Lexicon</h1>
	
	
{{ $content }}
    
    

@stop


@section('menu')
	@include('menu_menu')
@stop