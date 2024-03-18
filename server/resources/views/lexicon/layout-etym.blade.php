@extends('lexicon.layout')

@section('search-item-list')
    @foreach ($lexicon->etyma as $etymon)
        <li data-sidebar-id="{{$etymon->id}}"><sup>*</sup><a href="/lexicon/{{$lexicon->slug}}/etymon/{{$etymon->id}}">{!! $etymon->entry !!}</a></li>
    @endforeach
@endsection

@section('sidebar')
    @include('lexicon.layout-sidebar')
@endsection

@section('language-select')
    @include('lexicon.layout-language-select')
@endsection
