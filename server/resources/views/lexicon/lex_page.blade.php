@extends('lexicon.layout-etym')

@section('page-title')
    {{$page->name}}
@endsection

@section('content')
    <div>
        {!! $page->content !!}
    </div>

@endsection
