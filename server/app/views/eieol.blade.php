@extends('layout')

@section('content')
    <h2>Early Indo-European Online</h2>
    
    @foreach($serieses as $series)
        <p>{{ $series->title }}</p>
    @endforeach
@stop