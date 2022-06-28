@extends('lexicon.layout-etym')

@section('content')

    <h1><sup>*</sup>{{$etymon->entry}}</h1>
    <div>
        {!! $etymon->gloss !!}
    </div>
    <ul>
        @foreach ($etymon->reflexes as $reflex)
            <li><a href="/lexicon/{{$lexicon->slug}}/word/{{$reflex->id}}">{{$reflex->getLangNameEntriesGlossAttribute()}}</a></li>
        @endforeach
    </ul>

    <script>
        highlight_sidebar('headword', {{$etymon->id}});
    </script>
@endsection

