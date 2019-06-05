@extends('layout')

@section('title') Indo-European Lexicon: PIE Etyma and IE Reflexes @stop

@section('content')
    
<h1>Indo-European Lexicon</h1>

<h2>PIE Etyma and IE Reflexes</h2>

<h3 class="AUTH">Jonathan Slocum</h3>

<div class="skinny">

{!! $content !!}

</div>

@stop


@section('menu')
	@include('menu_menu')
@stop
