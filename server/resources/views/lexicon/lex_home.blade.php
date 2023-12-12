@extends('lexicon.layout-etym')

@section('title')
    {{__('lexicon.pages.home.html_head_title', ['lexicon_name'=>$lexicon->name])}}
@endsection

@section('page-title')
    {{__('lexicon.pages.home.page_title', ['lexicon_name'=>$lexicon->name])}}
@endsection

@section('content')
    <div>
        {!! $lexicon->description !!}
    </div>
@endsection
