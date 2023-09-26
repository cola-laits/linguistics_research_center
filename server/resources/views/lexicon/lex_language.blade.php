@extends('lexicon.layout-dict')

@section('page-title')
    {{$language->name}}
@endsection

@section('content')
    <h2>Family: {{$language->language_sub_family->name}} > {{$language->language_sub_family->language_family->name}}</h2>
    <div>
        {!! $language->description !!}
    </div>

@endsection
