@extends('lexicon.layout')

@section('content')

    <h1>{{$etymon->entry}}</h1>
    <div>
        {!! $etymon->gloss !!}
    </div>
    <ul>
        @foreach ($etymon->reflexes as $reflex)
            <li><a href="/lexicon/{{$lexicon->slug}}/word/{{$reflex->id}}">{{$reflex->getLangNameEntriesGlossAttribute()}}</a></li>
        @endforeach
    </ul>

@endsection

