@extends('lexicon.layout-dict')

@section('title')
    LRC {{$lexicon->name}}: {{$word->language->name}} {{$word->getEntriesCSV()}}
@endsection

@section('page-title')
    {{$word->language->name}} Dictionary
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
                    <p>From {{$etymon->lexicon->protolang_name}} <a href="/lexicon/{{$etymon->lexicon->slug}}/etymon/{{$etymon->id}}"><b><sup>*</sup>{{$etymon->entry}}</b> {{$etymon->gloss}}</a></p>
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

        @if ($word->cross_references->count() > 0)
            <h2>Related Words:</h2>
            <div>
                <ul>
                    @foreach ($word->cross_references as $crossref)
                        <li>
                            {{$crossref->language->name}}: {{$crossref->getEntriesCSV()}} "{{$crossref->gloss}}"
                            @if ($crossref->pivot->relationship)
                            ({{$crossref->pivot->relationship}})
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

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

        @php
        // 'cognates' are other words that share the same etymon
        $cognates = collect();
        foreach ($word->etyma as $etymon) {
            foreach ($etymon->reflexes as $reflex) {
                if ($word->id === $reflex->id) {
                    continue;
                }
                $cognates->push($reflex);
            }
        }
        @endphp
        @if ($cognates->count() > 0)
        <h2>Cognates</h2>
        <div>
            <ul>
            @foreach ($cognates as $reflex)
                <li>{{$reflex->language->name}}: <a href="/lexicon/{{$lexicon->slug}}/word/{{$reflex->id}}">{{$reflex->getEntriesCSV()}}</a></li>
            @endforeach
            </ul>
        </div>
        @endif
    </div>

    <script>
        highlight_sidebar('headword', {{$word->id}});
        @foreach ($word->etymaSemanticTags() as $tag)
        highlight_sidebar('category', {{$tag->id}});
        @endforeach
    </script>
@endsection

