@extends('lexicon.layout-dict')

@section('content')

    <h1>{{$language->name}}</h1>
    <h2>Family: {{$language->language_sub_family->name}} > {{$language->language_sub_family->language_family->name}}</h2>
    <div>
        {!! $language->description !!}
    </div>

@endsection
