@extends('lexicon.layout-etym', ['breadcrumb_segments' => [
    ['text'=>__('lexicon.pages.protolang.breadcrumb_title', ['lexicon_protolang_name'=>$lexicon->protolang_name])]
]])

@section('title')
    {{__('lexicon.pages.protolang.html_head_title', ['lexicon_name'=>$lexicon->name])}}
@endsection

@section('page-title')
    {{__('lexicon.pages.protolang.page_title', ['lexicon_name'=>$lexicon->name])}}
@endsection

@section('content')
    <div>
        {!! $lexicon->protolanguage_page_content !!}
    </div>
@endsection
