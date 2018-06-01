@extends('layout')

@section('title') Indo-European Lexicon: User Guide @stop

@section('content')

<h1>Indo-European Lexicon</h1>
<h2>User Guide</h2>
<div class="skinny">

{{ $content }}  

</div>  

@stop


@section('menu')
	@include('menu_menu')
@stop