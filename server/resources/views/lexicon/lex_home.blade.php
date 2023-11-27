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

    <div>
        @php
        $lang_names = ['en'=>'English','es'=>'Español']
        @endphp
        @forelse ($lexicon->getViewerLangsArray() as $viewer_lang)
            @php($lang_name = $lang_names[$viewer_lang] ?? 'Unknown language name for '.$viewer_lang)
            @if (App::getLocale() === $viewer_lang)
                <button type="button" class="btn btn-primary"
                        disabled
                >{{$lang_name}}</button>
            @else
                <button type="button" class="btn btn-primary"
                        onclick="document.location.href='/lexicon/{{$lexicon->slug}}?switchlang={{$viewer_lang}}'"
                >{{$lang_name}}</button>
            @endif
        @empty
            @if (App::getLocale() !== 'en')
                {{-- Offer an emergency 'back to English' button in case you land an a lexicon without language choices --}}
                <button type="button" class="btn btn-primary"
                        onclick="document.location.href='/lexicon/{{$lexicon->slug}}?switchlang=en'"
                >English</button>
            @endif
        @endforelse
    </div>

    <div>
        <a href="/lexicon/{{$lexicon->slug}}/data">{{__('lexicon.pages.home.search_lexicon_link_text')}}</a>
    </div>

    <div><pre style="background-color:#999999;padding:10px;margin:10px;">
    Right now, mostly just focusing on getting all the right information on the page, and working out page-to-page navigation.

    TODO:
        all pages:
            display data (reflexes on etyma pages, cognates on reflex pages, items in sidebar, etc) in some sensible order other than database-id order
        general cleanup / prep for release:
            investigate and add SEO tags
            general style/UI/UX once-over
            responsive (tablet/mobile) testing
    </pre></div>
@endsection
