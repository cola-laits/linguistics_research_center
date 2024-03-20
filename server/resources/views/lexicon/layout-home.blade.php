@extends('lexicon.layout')

@section('sidebar')
    <div id="sidebar" class="sidebar d-flex flex-column">

        <div id="sidebar-content" class="sidebar-content flex-grow-1">
            <div class="p-2">
                <ul>
                    <li><a href="/lexicon/{{$lexicon->slug}}/language/protolanguage">{{$lexicon->protolang_name}}</a></li>
                    @foreach ($lexicon->language_families as $family)
                        @foreach ($family->language_sub_families as $subfamily)
                            <li>{{$family->name}}: {{strip_tags($subfamily->name)}}
                                <ul>
                                    @foreach ($subfamily->languages as $s_language)
                                        <li><a href="/lexicon/{{$lexicon->slug}}/language/{{$s_language->id}}">{{$s_language->name}}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    @endforeach
                </ul>
            </div>
        </div>

    </div>

@endsection
