@extends('lexicon.layout')

@section('search-sidebar')
    <div id="sidebar-header" class="sidebar-header">
        <p>[FIXME search bar here]</p>
        <p>
            <a href="#" class="selector_type_link{{$selected_sidebar=='headword' ? ' selected' : ''}}"
               onclick="choose_selector_type('headword');return false;" id="selector_type_link_headword">Headwords</a>
            |
            <a href="#" class="selector_type_link{{$selected_sidebar=='category' ? ' selected' : ''}}"
               onclick="choose_selector_type('category');return false;" id="selector_type_link_category">Categories</a>
        </p>
    </div>
    <div id="sidebar-content" class="sidebar-content">
        <div class="selector_type" id="selector_type_headword"@if ($selected_sidebar!=='headword') style="display:none;"@endif>
            <ul>
                @foreach ($lexicon->etyma as $etymon)
                    <li data-sidebar-id="{{$etymon->id}}"><sup>*</sup><a href="/lexicon/{{$lexicon->slug}}/etymon/{{$etymon->id}}">{{$etymon->entry}}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="selector_type" id="selector_type_category"@if ($selected_sidebar!=='category') style="display:none;"@endif>
            <div class="accordion" id="accordionCategory">
                @foreach ($lexicon->semantic_categories as $category)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="accordion_category_heading_{{$category->id}}">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordion_category_collapse_{{$category->id}}" aria-expanded="true" aria-controls="accordion_category_collapse_{{$category->id}}">
                            {{$category->text}}
                        </button>
                    </h2>
                    <div id="accordion_category_collapse_{{$category->id}}" class="accordion-collapse collapse" aria-labelledby="accordion_category_heading_{{$category->id}}" data-bs-parent="#accordionCategory">
                        <div class="accordion-body">
                            <ul>
                                @foreach ($category->semantic_fields as $field)
                                    <li data-sidebar-id="{{$field->id}}"><a href="/lexicon/{{$lexicon->slug}}/field/{{$field->id}}">{{$field->text}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
