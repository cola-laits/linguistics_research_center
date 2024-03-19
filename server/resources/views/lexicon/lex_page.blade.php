@extends('lexicon.layout')

@section('title')
    {{__('lexicon.general.html_head_title', ['lexicon_name'=>$lexicon->name, 'page_title'=>$page->name])}}
@endsection

@section('page-title')
    {{__('lexicon.general.html_head_title', ['lexicon_name'=>$lexicon->name, 'page_title'=>$page->name])}}
@endsection

@section('content')
    <div>
        {!! $page->content !!}
    </div>

@endsection
