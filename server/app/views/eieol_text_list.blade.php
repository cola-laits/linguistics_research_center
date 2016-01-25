@extends('layout')

@section('title') Early Indo-European Texts @stop

@section('content')

    <h1>Early Indo-European Texts</h1>
	
<div class="skinny">

    <ul>
    @foreach($serieses as $series)
        <li>{{ HTML::link('eieol_text_toc/' . $series->id, $series->title, array('title' => $series->expanded_title )) }} </li>
    @endforeach
  	</ul>
  	
</div>
@stop


@section('menu')
	@include('menu_menu')
	@include('menu_series', array('data'=>'data'))
	@include('menu_book_links')
	@include('menu_more_info')
@stop