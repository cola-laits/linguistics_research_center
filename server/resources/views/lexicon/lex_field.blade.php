@extends('lexicon.layout-etym')

@section('title')
    LRC {{$lexicon->name}}: {{$field->semantic_category->text}}: {{$field->text}}
@endsection

@section('content')

    <table class="table table-bordered table-responsive">
        <tr>
            <td class="text-end" style="white-space:nowrap;">Semantic Category:</td>
            <td class="vw-100">{{$field->semantic_category->text}}</td>
        </tr>
        <tr>
            <td class="text-end" style="white-space:nowrap;">Semantic Field:</td>
            <td class="vw-100">{{$field->text}}</td>
        </tr>
        <tr>
            <td class="text-end" style="white-space:nowrap;">Etyma:</td>
            <td class="vw-100">
                <ul>
                    @forelse ($field->etyma as $etymon)
                        <li><sup>*</sup><a href="/lexicon/{{$lexicon->slug}}/etymon/{{$etymon->id}}">{{$etymon->entry}}</a> <span>{!! $etymon->gloss !!}</span></li>
                    @empty
                        <li>No words found.</li>
                    @endforelse
                </ul>
            </td>
        </tr>
        <tr>
            <td class="text-end" style="white-space:nowrap;">Descendent Words:</td>
            <td class="vw-100">
                <ul>
                    @forelse ($field->etyma as $etymon)
                        @foreach ($etymon->reflexes as $reflex)
                            <li><a href="/lexicon/{{$lexicon->slug}}/word/{{$reflex->id}}">{{$reflex->getLangNameEntriesGlossAttribute()}}</a></li>
                        @endforeach
                    @empty
                        <li>None found.</li>
                    @endforelse
                </ul>
            </td>
        </tr>
    </table>

    <script>
        highlight_sidebar('category', {{$field->id}});
    </script>

@endsection
