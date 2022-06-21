@extends('lexicon.layout')

@section('search-sidebar')
<p>[search bar here]</p>
<p>
    <a href="#" onclick="choose_selector_type('headword');return false;">Headwords</a>
    |
    <a href="#" onclick="choose_selector_type('category');return false;">Category</a></p>
<div style="height:300px;overflow:scroll;">
    <div class="selector_type" id="selector_type_headword">
        <ul>
            @foreach ($lexicon->etyma as $etymon)
                <li><a href="/lexicon/{{$lexicon->slug}}/etymon/{{$etymon->id}}">{{$etymon->entry}}</a></li>
            @endforeach
        </ul>
    </div>
    <div class="selector_type" id="selector_type_category">
        <ul>
            @foreach ($lexicon->semantic_categories as $category)
                <li>
                    {{$category->text}}
                    <ul>
                        @foreach ($category->semantic_fields as $field)
                            <li><a href="/lexicon/{{$lexicon->slug}}/field/{{$field->id}}">{{$field->text}}</a></li>
                        @endforeach
                    </ul>
                </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
