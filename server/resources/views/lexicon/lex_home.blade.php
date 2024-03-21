@extends('lexicon.layout-home')

@section('title')
    {{__('lexicon.pages.home.html_head_title', ['lexicon_name'=>$lexicon->name])}}
@endsection

@section('page-title')
    {{__('lexicon.pages.home.page_title', ['lexicon_name'=>$lexicon->name])}}
@endsection

@php
    // look for [highlight-link:foo], [/highlight-link] and replace with <a href="javascript: show_me_highlight('foo');">...</a>
    $parsed_content = preg_replace('/\[\/highlight-link\]/', '</a>', $lexicon->landing_page_content);
    $parsed_content = preg_replace('/\[highlight-link:(.*?)\]/', '<a href="javascript: show_me_highlight(\'$1\');">', $parsed_content);
@endphp

@section('content')
    <div>
        {!! $parsed_content !!}
    </div>
@endsection
