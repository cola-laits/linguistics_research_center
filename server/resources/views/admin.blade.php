@extends('admin_layout')

@section('title') LRC Admin @stop

@section('content')
    <router-view :key="$route.fullPath"></router-view>
    <br><br><br>


@stop
