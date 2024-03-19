@extends('lexicon.layout')

@php
    function sortSidebarItemsByEntry($items) {
        return $items->sortBy(function($item, $key) {
            $sortkey = mb_strtolower($item->entry);
            return preg_replace('/\W+/', '', \Normalizer::normalize($sortkey, \Normalizer::FORM_D));
        });
    }
@endphp

@section('search-item-list')
    @foreach (sortSidebarItemsByEntry($lexicon->etyma) as $etymon)
        <li data-sidebar-id="{{$etymon->id}}"><sup>*</sup><a href="/lexicon/{{$lexicon->slug}}/etymon/{{$etymon->id}}">{!! $etymon->entry !!} @homograph_number($etymon->homograph_number)</a></li>
    @endforeach
@endsection

@section('sidebar')
    @include('lexicon.layout-sidebar')
@endsection

@section('language-select')
    @include('lexicon.layout-language-select')
@endsection
