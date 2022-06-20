@extends('lexicon.layout')

@section('content')

    <h1>{{$language->name}}</h1>
    <div>
        {!! $language->description !!}
    </div>

@endsection
