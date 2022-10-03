@extends('lexicon.layout-dict')

@section('content')

    <h1>{{$word->language->name}}</h1>
    <div>
    <h2>Word: {{$word->getEntriesCSV()}}</h2>
    <h3>POS: {{$word->getDisplayPartsOfSpeech()}}</h3>
    <h3>Gloss: {{$word->gloss}}</h3>
    </div>
    <div>
        <h2>Etymology</h2>
        <div>
            @foreach ($word->etyma as $etymon)
                <p><b><sup>*</sup>{{$etymon->entry}}</b> {{$etymon->gloss}}</p>
            @endforeach
        </div>
        @if ($word->extra_data)
        <h2>Other info:</h2>
        <div>
            <ul>
            @foreach ($word->extra_data as $name=>$value)
                    <li>{{$name}}: {{$value}}</li>
            @endforeach
            </ul>
        </div>
        @endif
        <h2>Cognates</h2>
        <div>
            <ul>
            @foreach ($word->etyma as $etymon)
                @foreach ($etymon->reflexes as $reflex)
                    @if ($word->id === $reflex->id)
                        @continue
                    @endif
                    <li>{{$reflex->language->name}}: <a href="/lexicon/{{$lexicon->slug}}/word/{{$reflex->id}}">{{$reflex->getEntriesCSV()}}</a></li>
                @endforeach
            @endforeach
            </ul>
        </div>
    </div>

    <script>
        highlight_sidebar('headword', {{$word->id}});
    </script>
@endsection

