@extends('lexicon.layout-etym')

@section('content')

    <h1><sup>*</sup>{{$etymon->entry}}</h1>
    <div>
        {!! $etymon->gloss !!}
    </div>
    <h2>Derived Words</h2>
    <ul>
        @foreach ($etymon->reflexes as $reflex)
            <li><a href="/lexicon/{{$lexicon->slug}}/word/{{$reflex->id}}">{{$reflex->getLangNameEntriesGlossAttribute()}}</a></li>
        @endforeach
    </ul>

    @if ($etymon->semantic_fields->count() > 0)
    <h2>Semantic Field</h2>
    @foreach ($etymon->semantic_fields as $field)
    <p>{{$field->semantic_category->text}}: {{$field->text}}</p>
    @endforeach
    @endif

    @if ($etymon->extra_data)
        <h2>Other info:</h2>
        <div>
            <ul>
                @foreach ($etymon->extra_data as $name=>$value)
                    <li>{{$name}}: {{$value}}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <script>
        highlight_sidebar('headword', {{$etymon->id}});
    </script>
@endsection

