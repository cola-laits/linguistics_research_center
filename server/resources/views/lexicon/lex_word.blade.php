@extends('lexicon.layout-dict', ['breadcrumb_segments' => [
    [
        'text'=>__('lexicon.pages.language.breadcrumb_title', ['language_name'=>$word->language->name]),
        'url'=>'/lexicon/'.$lexicon->slug.'/language/'.$word->language->id
    ],
    [
        'text'=>__('lexicon.pages.word.breadcrumb_title', [
                    'lexicon_name'=>$lexicon->name,
                    'language_name'=>$word->language->name,
                    'word'=>$word->getEntriesCSV()])
    ]
]]
)

@section('title')
    {{__('lexicon.general.html_head_title', ['lexicon_name'=>$lexicon->name, 'page_title'=>$word->language->name.": ".$word->getEntriesCSV()])}}
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
        @if ($word->getDisplayPartsOfSpeech())
        <tr>
            <td class="text-end" style="white-space:nowrap;">{{__('lexicon.pages.word.table_header.Part of Speech')}}:</td>
            <td>{{$word->getDisplayPartsOfSpeech()}}</td>
        </tr>
        @endif
        <tr>
            <td class="text-end" style="white-space:nowrap;">{{__('lexicon.pages.word.table_header.Gloss')}}:</td>
            <td>{{$word->gloss}}</td>
        </tr>
        @if (count($word->etyma) > 0)
        <tr>
            <td class="text-end" style="white-space:nowrap;">{{__('lexicon.pages.word.table_header.Etymology')}}:</td>
            <td>
                @foreach ($word->etyma as $etymon)
                    <p>From {{$etymon->lexicon->protolang_name}} <a href="/lexicon/{{$etymon->lexicon->slug}}/etymon/{{$etymon->id}}"><b><sup>*</sup>{!! $etymon->entry !!} @homograph_number($etymon->homograph_number)</b> {{$etymon->gloss}}</a></p>
                @endforeach
            </td>
        </tr>
        @endif
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
        @if ($word->cross_references_to->count() > 0 || $word->cross_references_from->count() > 0)
            <h2>{{__('lexicon.pages.word.table_header.Related Words')}}:</h2>
            @if ($word->cross_references_from->count() > 0)
            <div>
                <h3>{{__('lexicon.pages.word.table_header.Related Words Borrowed From')}}:</h3>
                <ul>
                    @foreach ($word->cross_references_from as $crossref)
                        <li>
                            {{$crossref->language->name}}: <a href="/lexicon/{{$crossref->language->language_sub_family->language_family->lexicon->slug}}/word/{{$crossref->id}}">{{$crossref->getEntriesCSV()}}</a> "{{$crossref->gloss}}"
                            @if ($crossref->pivot->relationship)
                                ({{$crossref->pivot->relationship}})
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if ($word->cross_references_to->count() > 0)
            <div>
                <h3>{{__('lexicon.pages.word.table_header.Related Words Borrowed Into')}}:</h3>
                <ul>
                    @foreach ($word->cross_references_to as $crossref)
                        <li>
                            {{$crossref->language->name}}: <a href="/lexicon/{{$crossref->language->language_sub_family->language_family->lexicon->slug}}/word/{{$crossref->id}}">{{$crossref->getEntriesCSV()}}</a> "{{$crossref->gloss}}"
                            @if ($crossref->pivot->relationship)
                                ({{$crossref->pivot->relationship}})
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif
        @endif

        @if ($word->sources)
            <div>
                <h2>{{__('lexicon.pages.word.table_header.Sources')}}:</h2>
                <div>
                    <table class="table table-bordered table-responsive">
                        <tr>
                            <th>{{__('lexicon.pages.word.table_header.Source')}}</th>
                            <th style="width:100%;">{{__('lexicon.pages.word.table_header.Original Entry')}}</th>
                        </tr>
                        @foreach ($word->sources as $source)
                        <tr>
                            <td style="white-space:nowrap;">
                                {{$source->display}}
                                @if ($source->pivot->page_number)
                                    p. {{$source->pivot->page_number}}
                                @endif
                            </td>
                            <td>
                                {{$source->pivot->original_text}}
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        @endif

        @if ($word->extra_data)
        <h2>{{__('lexicon.pages.word.table_header.Other Info')}}:</h2>
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

