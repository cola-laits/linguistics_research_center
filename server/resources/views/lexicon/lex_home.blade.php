@extends('lexicon.layout-etym')

@section('title')
    LRC {{$lexicon->name}}
@endsection

@section('page-title')
    {{$lexicon->name}}
@endsection

@section('content')
    <div>
        {!! $lexicon->description !!}
    </div>

    <div>
        <a href="/lexicon/{{$lexicon->slug}}/data">Search this lexicon.</a>
    </div>

    <div><pre style="background-color:#999999;padding:10px;margin:10px;">
    Right now, mostly just focusing on getting all the right information on the page, and working out page-to-page navigation.

    TODO:
        all pages:
            display data (reflexes on etyma pages, cognates on reflex pages, items in sidebar, etc) in some sensible order other than database-id order
        general cleanup / prep for release:
            add page-specific [title] tags
            investigate and add SEO tags
            general style/UI/UX once-over
            responsive (tablet/mobile) testing
    </pre></div>
@endsection
