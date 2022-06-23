@extends('lexicon.layout')

@section('search-sidebar')
    <div id="sidebar-header" class="sidebar-header">
    <p>[FIXME search bar here]</p>
    <p>
        <a href="#" onclick="choose_selector_type('headword');return false;">Headwords</a>
        |
        <a href="#" onclick="choose_selector_type('category');return false;">Category</a></p>
    </div>
    <div id="sidebar-content" class="sidebar-content">
        <div class="selector_type" id="selector_type_headword"@if ($selected_sidebar!=='headword') style="display:none;"@endif>
            <ul>
                @foreach ($language->reflexes as $reflex)
                    <li data-sidebar-id="{{$reflex->id}}"><a href="/lexicon/{{$lexicon->slug}}/word/{{$reflex->id}}">{{$reflex->getEntriesCSV()}}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="selector_type" id="selector_type_category"@if ($selected_sidebar!=='category') style="display:none;"@endif>
            <ul>
                @foreach ($lexicon->semantic_categories as $category)
                    <li>
                        {{$category->text}}
                        <ul>
                            @foreach ($category->semantic_fields as $field)
                                <li data-sidebar-id="{{$field->id}}"><a href="/lexicon/{{$lexicon->slug}}/field/{{$field->id}}">{{$field->text}}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
