@extends('lexicon.layout-dict')

@section('title')
    {{__('lexicon.general.html_head_title', ['lexicon_name'=>$lexicon->name, 'page_title'=>$word->language->name."".$word->getEntriesCSV()])}}
@endsection

@section('page-title')
    {{__('lexicon.pages.word.page_title', [
        'lexicon_name'=>$lexicon->name,
        'language_name'=>$word->language->name,
        'word'=>$word->getEntriesCSV()
    ])}}
@endsection

@section('content')
    <table class="table table-bordered table-responsive">
        <tr>
            <td class="text-end" style="white-space:nowrap;">{{__('lexicon.pages.word.table_header.Language')}}:</td>
            <td class="vw-100">{{$word->language->name}}</td>
        </tr>
        <tr>
            <td class="text-end" style="white-space:nowrap;">{{__('lexicon.pages.word.table_header.Word')}}:</td>
            <td>{{$word->getEntriesCSV()}}</td>
        </tr>
        <tr>
            <td class="text-end" style="white-space:nowrap;">{{__('lexicon.pages.word.table_header.Part of Speech')}}:</td>
            <td>{{$word->getDisplayPartsOfSpeech()}}</td>
        </tr>
        <tr>
            <td class="text-end" style="white-space:nowrap;">{{__('lexicon.pages.word.table_header.Gloss')}}:</td>
            <td>{{$word->gloss}}</td>
        </tr>
        <tr>
            <td class="text-end" style="white-space:nowrap;">{{__('lexicon.pages.word.table_header.Etymology')}}:</td>
            <td>
                @foreach ($word->etyma as $etymon)
                    <p>From {{$etymon->lexicon->protolang_name}} <a href="/lexicon/{{$etymon->lexicon->slug}}/etymon/{{$etymon->id}}"><b><sup>*</sup>{{$etymon->entry}}</b> {{$etymon->gloss}}</a></p>
                @endforeach
            </td>
        </tr>
        @if ($word->etymaSemanticTags()->count() > 0)
        <tr>
            <td class="text-end" style="white-space:nowrap;">{{__('lexicon.pages.word.table_header.Semantic Tag')}}:</td>
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
            <h2>{{__('lexicon.pages.word.table_header.Related Words')}}:</h2>
            <div>
                <ul>
                    @foreach ($word->cross_reference_pivots as $crossref)
                        <li>
                            {{$crossref->to_reflex->language->name}}: {{$crossref->to_reflex->getEntriesCSV()}} "{{$crossref->to_reflex->gloss}}"
                            @if ($crossref->relationship)
                            ({{$crossref->relationship}})
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($word->extra_data)
        <h2>{{__('lexicon.pages.word.table_header.Other info')}}:</h2>
        <div>
            <ul>
            @foreach ($word->extra_data as $extra_datum)
                <li>{{$extra_datum->key}}: {{$extra_datum->value}}</li>
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
        <h2>{{__('lexicon.pages.word.table_header.Cognates')}}</h2>
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

