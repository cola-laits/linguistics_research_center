@extends('lexicon.layout-dict')

@section('title')
    {{__('lexicon.general.html_head_title', ['lexicon_name'=>$lexicon->name, 'page_title'=>$language->name])}}
@endsection

@section('page-title')
    {{__('lexicon.pages.language.page_title', ['lexicon_name'=>$lexicon->name, 'language_name'=>$language->name])}}
@endsection

@section('content')
    <h2>{{__('lexicon.pages.language.table_header.Family')}}: {{$language->language_sub_family->name}} > {{$language->language_sub_family->language_family->name}}</h2>
    <div>
        {!! $language->description !!}
    </div>

@endsection
