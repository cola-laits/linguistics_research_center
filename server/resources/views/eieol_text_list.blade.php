@extends('layout')

@section('title') Early Indo-European Texts @stop

@section('content')

    <h1>Early Indo-European Texts</h1>

<div class="skinny">

    <ul>
    @foreach($text_list as $text)
        @if ($text['language_id'] == 0)
            <li><a href='/eieol_text_toc/{{$text['id']}}'>{{$text['title']}}</a></li>
        @else
            <li><a href='/eieol_text_toc/{{$text['id']}}?language_id={{$text['language_id']}}'>{{$text['title']}}</a></li>
        @endif
    @endforeach
    </ul>

</div>
@stop


@section('menu')
    @include('menu_menu')
@stop
