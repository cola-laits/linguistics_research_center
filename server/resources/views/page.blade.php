@extends('layout')

@section('title') {{$page->name}} @stop

@section('content')

<h1>{{$page->name}}</h1>
<div class="skinny">

{!! $page->content !!}

</div>

@stop


@section('menu')
    @include('menu_menu')
@stop
