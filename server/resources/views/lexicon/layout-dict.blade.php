@extends('lexicon.layout')

@php
    function sortSidebarItemsByEntries($items) {
        return $items->sortBy(function($item, $key) {
            $sortkey = mb_strtolower($item->getEntriesCSV());
            return preg_replace('/\W+/', '', \Normalizer::normalize($sortkey, \Normalizer::FORM_D));
        });
    }
@endphp

@section('search-item-list')
    @foreach (sortSidebarItemsByEntries($language->reflexes) as $reflex)
        <li data-sidebar-id="{{$reflex->id}}"><a href="/lexicon/{{$lexicon->slug}}/word/{{$reflex->id}}">{{$reflex->getEntriesCSV()}}</a></li>
    @endforeach
@endsection

@section('sidebar')
    @include('lexicon.layout-sidebar')
@endsection

@section('language-select')
    @include('lexicon.layout-language-select')
@endsection
