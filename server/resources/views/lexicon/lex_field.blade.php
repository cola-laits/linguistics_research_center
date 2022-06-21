@extends('lexicon.layout-etym')

@section('content')

    <h1>{{$field->semantic_category->text}}</h1>
    <h2>{{$field->text}}</h2>
    <ul>
        @forelse ($field->etyma as $etymon)
            <li><a href="/lexicon/{{$lexicon->slug}}/etymon/{{$etymon->id}}">{{$etymon->entry}}</a> <span>{!! $etymon->gloss !!}</span></li>
        @empty
            <li>No words found.</li>
        @endforelse
    </ul>

@endsection
