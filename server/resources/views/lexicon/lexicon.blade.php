@extends('admin_layout')

@section('title') Lexicon Admin @stop

@section('content')
    <router-view :key="$route.fullPath"></router-view>
    <br><br><br>


@stop
