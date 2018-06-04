@extends('layout')


@section('content')



<h1 class="header-text">Welcome to the LRC</h1>
	
	
{{ $content }}
    
    

@stop


@section('menu')
	@include('menu_menu')
@stop