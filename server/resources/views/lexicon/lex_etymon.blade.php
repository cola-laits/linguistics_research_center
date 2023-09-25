@extends('lexicon.layout-etym')

@section('title')
    LRC {{$lexicon->name}}: {{$etymon->entry}}
@endsection

@section('content')

    <h1>{{$lexicon->protolang_name}} Dictionary</h1>

    <table class="table table-bordered table-responsive">
        <tr>
            <td class="text-end" style="white-space:nowrap;">Etymon:</td>
            <td class="vw-100"><sup>*</sup>{{$etymon->entry}}</td>
        </tr>
        <tr>
            <td class="text-end" style="white-space:nowrap;">Gloss:</td>
            <td class="vw-100">{!! $etymon->gloss !!}</td>
        </tr>
        <tr>
            <td class="text-end" style="white-space:nowrap;">Derived Words:</td>
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
            <td class="text-end" style="white-space:nowrap;">Semantic Field:</td>
            <td class="vw-100">
                @foreach ($etymon->semantic_fields as $field)
                    <p><a href="/lexicon/{{$lexicon->slug}}/field/{{$field->id}}">{{$field->semantic_category->text}}: {{$field->text}}</a></p>
                @endforeach
            </td>
        </tr>
        @endif
    </table>

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
    </table>

    <script>
        highlight_sidebar('headword', {{$etymon->id}});
        @foreach ($etymon->semantic_fields as $field)
        highlight_sidebar('category', {{$field->id}});
        @endforeach
    </script>
@endsection

