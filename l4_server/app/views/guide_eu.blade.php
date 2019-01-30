@extends('layout')

@section('title') Early Indo-European Online: Author Guide @stop

@section('content')

<h1>Early Indo-European Online</h1>
<h2>User Guide</h2>
<div class="skinny">

{{ $content }}  

</div>  

@stop


@section('menu')
	@include('menu_menu')
@stop