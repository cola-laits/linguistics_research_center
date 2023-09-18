@extends('lexicon.layout')

@section('search-item-list')
    @foreach ($language->reflexes as $reflex)
        <li data-sidebar-id="{{$reflex->id}}"><a href="/lexicon/{{$lexicon->slug}}/word/{{$reflex->id}}">{{$reflex->getEntriesCSV()}}</a></li>
    @endforeach
@endsection

@section('sidebar')
    @include('lexicon.layout-sidebar')
@endsection

@section('language-select')
    @include('lexicon.layout-language-select')
@endsection
