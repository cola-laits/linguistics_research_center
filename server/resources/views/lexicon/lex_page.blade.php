@extends('lexicon.layout-etym')

@section('content')

    <h1>{{$page->name}}</h1>
    <div>
        {!! $page->content !!}
    </div>

@endsection
