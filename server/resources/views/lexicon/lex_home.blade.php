@extends('lexicon.layout')

@section('content')

    <h1>{{$lexicon->name}}</h1>
    <div>
        {!! $lexicon->description !!}
    </div>

    <div>
        <a href="/lexicon/{{$lexicon->slug}}/search">Search this lexicon.</a>
    </div>

    <div>
        Here are the languages in this lexicon:
        <ul>
        @foreach ($lexicon->language_families as $family)
            <li>{{$family->name}}</li>
            <ul>
                @foreach ($family->language_sub_families as $subfamily)
                    <li>{{$subfamily->name}}</li>
                    <ul>
                        @foreach ($subfamily->languages as $language)
                            <li><a href="/lexicon/{{$lexicon->slug}}/language/{{$language->id}}">{{$language->name}}</a></li>
                        @endforeach
                    </ul>
                @endforeach
            </ul>
        @endforeach
        </ul>
    </div>
@endsection
