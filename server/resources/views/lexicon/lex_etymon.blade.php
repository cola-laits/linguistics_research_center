@extends('lexicon.layout-etym')

@section('content')

    <h1><sup>*</sup>{{$etymon->entry}}</h1>
    <div>
        {!! $etymon->gloss !!}
    </div>
    <h2>Derived Words</h2>
    <ul>
        @foreach ($etymon->reflexes as $reflex)
            <li><a href="/lexicon/{{$lexicon->slug}}/word/{{$reflex->id}}">{{$reflex->getLangNameEntriesGlossAttribute()}}</a></li>
        @endforeach
    </ul>
    @if ($etymon->extra_data)
        <h2>Other info:</h2>
        <div>
            <ul>
                @foreach ($etymon->extra_data as $name=>$value)
                    <li>{{$name}}: {{$value}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <script>
        highlight_sidebar('headword', {{$etymon->id}});
    </script>
@endsection

