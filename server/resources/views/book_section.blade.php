@extends('layout')

@section('title') {{$book->name}}: {{$section->name}} @stop

@section('content')

<h1>{{$book->name}}</h1>
<div class="skinny">

{!! $section->content !!}

    <p class="center">
        @if ($prev_section)
            <a href="/books/{{$book->slug}}/{{$prev_section->slug}}">previous section</a>
        @else
            previous section
        @endif
        &nbsp; | &nbsp;
        @if ($next_section)
            <a href="/books/{{$book->slug}}/{{$next_section->slug}}">next section</a>
        @else
            next section
        @endif
    </p>
</div>

@stop


@section('menu')
    @include('menu_menu')
@stop
