@extends('lexicon.layout-etym')

@section('content')

    <h1>{{$lexicon->name}}</h1>
    <div>
        {!! $lexicon->description !!}
    </div>

    <div>
        <a href="/lexicon/{{$lexicon->slug}}/data">Search this lexicon.</a>
    </div>
@endsection
