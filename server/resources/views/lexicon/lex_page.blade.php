@extends('lexicon.layout')

@section('content')

    <h1>{{$page->name}}</h1>
    <div>
        {!! $page->content !!}
    </div>

@endsection
