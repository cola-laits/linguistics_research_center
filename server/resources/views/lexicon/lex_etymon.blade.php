@extends('lexicon.layout-etym')

@section('title')
    {{__('lexicon.general.html_head_title', ['lexicon_name'=>$lexicon->name, 'page_title'=>$etymon->entry])}}
@endsection

@section('page-title')
    {{__('lexicon.pages.etymon.page_title', ['lexicon_name'=>$lexicon->name, 'lexicon_protolang_name'=>$lexicon->protolang_name])}}
@endsection

@section('content')
    <table class="table table-bordered table-responsive">
        <tr>
            <td class="text-end" style="white-space:nowrap;">{{__('lexicon.pages.etymon.table_header.Etymon')}}:</td>
            <td class="vw-100"><sup>*</sup>{!! $etymon->entry !!}</td>
        </tr>
        <tr>
            <td class="text-end" style="white-space:nowrap;">{{__('lexicon.pages.etymon.table_header.Gloss')}}:</td>
            <td class="vw-100">{!! $etymon->gloss !!}</td>
        </tr>
        <tr>
            <td class="text-end" style="white-space:nowrap;">{{__('lexicon.pages.etymon.table_header.Derived Words')}}:</td>
            <td class="vw-100">
                <ul>
                @foreach ($etymon->reflexes as $reflex)
                    <li><a href="/lexicon/{{$lexicon->slug}}/word/{{$reflex->id}}">{{$reflex->getLangNameEntriesGlossAttribute()}}</a></li>
                @endforeach
                </ul>
            </td>
        </tr>
        @if ($etymon->semantic_fields->count() > 0)
        <tr>
            <td class="text-end" style="white-space:nowrap;">{{__('lexicon.pages.etymon.table_header.Semantic Field')}}:</td>
            <td class="vw-100">
                @foreach ($etymon->semantic_fields as $field)
                    <p><a href="/lexicon/{{$lexicon->slug}}/field/{{$field->id}}">{{$field->semantic_category->text}}: {{$field->text}}</a></p>
                @endforeach
            </td>
        </tr>
        @endif
    </table>

        @if ($etymon->extra_data)
            <h2>{{__('lexicon.pages.etymon.table_header.Other Info')}}:</h2>
            <div>
                <ul>
                    @foreach ($etymon->extra_data as $extra_datum)
                        <li>{{$extra_datum->key}}: {{$extra_datum->value}}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </table>

    <script>
        highlight_sidebar('headword', {{$etymon->id}});
        @foreach ($etymon->semantic_fields as $field)
        highlight_sidebar('category', {{$field->id}});
        @endforeach
    </script>
@endsection

