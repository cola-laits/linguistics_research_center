@extends('lexicon.layout-etym')

@section('title')
    {{__('lexicon.general.html_head_title', ['lexicon_name'=>$lexicon->name, 'page_title'=>$field->text])}}
@endsection

@section('page-title')
    {{__('lexicon.pages.field.page_title', [
        'lexicon_name'=>$lexicon->name,
        'semantic_category'=>$field->semantic_category->text,
        'semantic_field'=>$field->text
    ])}}
@endsection

@section('content')
    <table class="table table-bordered table-responsive">
        <tr>
            <td class="text-end" style="white-space:nowrap;">{{__('lexicon.pages.field.table_header.Semantic Category')}}:</td>
            <td class="vw-100">{{$field->semantic_category->text}}</td>
        </tr>
        <tr>
            <td class="text-end" style="white-space:nowrap;">{{__('lexicon.pages.field.table_header.Semantic Field')}}:</td>
            <td class="vw-100">{{$field->text}}</td>
        </tr>
        <tr>
            <td class="text-end" style="white-space:nowrap;">{{__('lexicon.pages.field.table_header.Etyma')}}:</td>
            <td class="vw-100">
                <ul>
                    @forelse ($field->etyma as $etymon)
                        <li><sup>*</sup><a href="/lexicon/{{$lexicon->slug}}/etymon/{{$etymon->id}}">{{$etymon->entry}}</a> <span>{!! $etymon->gloss !!}</span></li>
                    @empty
                        <li>{{__('lexicon.pages.field.no_etyma_found_message')}}</li>
                    @endforelse
                </ul>
            </td>
        </tr>
        <tr>
            <td class="text-end" style="white-space:nowrap;">{{__('lexicon.pages.field.table_header.Descendent Words')}}:</td>
            <td class="vw-100">
                <ul>
                    @forelse ($field->etyma as $etymon)
                        @foreach ($etymon->reflexes as $reflex)
                            <li><a href="/lexicon/{{$lexicon->slug}}/word/{{$reflex->id}}">{{$reflex->getLangNameEntriesGlossAttribute()}}</a></li>
                        @endforeach
                    @empty
                        <li>{{__('lexicon.pages.field.no_descendent_words_found_message')}}</li>
                    @endforelse
                </ul>
            </td>
        </tr>
    </table>

    <script>
        highlight_sidebar('category', {{$field->id}});
    </script>

@endsection
