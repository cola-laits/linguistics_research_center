@extends('lexicon.layout-dict')

@section('title')
    LRC {{$lexicon->name}}: {{$word->language->name}} {{$word->getEntriesCSV()}}
@endsection

@section('content')

    <table class="table table-bordered table-responsive">
        <tr>
            <td class="text-end" style="white-space:nowrap;">Language:</td>
            <td class="vw-100">{{$word->language->name}}</td>
        </tr>
        <tr>
            <td class="text-end" style="white-space:nowrap;">Word:</td>
            <td>{{$word->getEntriesCSV()}}</td>
        </tr>
        <tr>
            <td class="text-end" style="white-space:nowrap;">Part of Speech:</td>
            <td>{{$word->getDisplayPartsOfSpeech()}}</td>
        </tr>
        <tr>
            <td class="text-end" style="white-space:nowrap;">Gloss:</td>
            <td>{{$word->gloss}}</td>
        </tr>
        <tr>
            <td class="text-end" style="white-space:nowrap;">Etymology:</td>
            <td>
                @foreach ($word->etyma as $etymon)
                    <p><b><sup>*</sup>{{$etymon->entry}}</b> {{$etymon->gloss}}</p>
                @endforeach
            </td>
        </tr>
        @if ($word->etymaSemanticTags()->count() > 0)
        <tr>
            <td class="text-end" style="white-space:nowrap;">Semantic Tag:</td>
            <td>
                @foreach ($word->etymaSemanticTags() as $tag)
                    <a href="/lexicon/{{$lexicon->slug}}/field/{{$tag->id}}">{{$tag->text}}</a>
                @endforeach
            </td>
        </tr>
        @endif
    </table>

    <div>

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
            FIXME: skip if no cognates
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

