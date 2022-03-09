@extends('layout')

@section('title') {{$book->name}}: {{$section->name}} @stop

@section('content')
    <script>
        function jump_to(slug) {
            document.location.href=slug;
        }
    </script>

<h1>{{$book->name}}</h1>

<p class="center">
    @if ($prev_section)
        <a href="/books/{{$book->slug}}/{{$prev_section->slug}}">&lt;&nbsp;previous&nbsp;section</a>
    @else
        &lt;&nbsp;previous&nbsp;section
    @endif
        ｜
    Jump to:  <select onchange="jump_to(this.selectedOptions[0].value);">
            @foreach ($all_sections as $menu_section)
          <option value="{{$menu_section->slug}}"
          @if ($menu_section->id === $section->id)
              selected
          @endif
          >{{$menu_section->name}}</option>
            @endforeach
      </select>
        ｜
    @if ($next_section)
        <a href="/books/{{$book->slug}}/{{$next_section->slug}}">next&nbsp;section&nbsp;&gt;</a>
    @else
        next&nbsp;section&nbsp;&gt;
    @endif
</p>

<div class="skinny">

{!! $section->content !!}

</div>

    <p class="center">
        @if ($prev_section)
            <a href="/books/{{$book->slug}}/{{$prev_section->slug}}">&lt;&nbsp;previous&nbsp;section</a>
        @else
            &lt;&nbsp;previous&nbsp;section
        @endif
        ｜
        Jump to:  <select onchange="jump_to(this.selectedOptions[0].value);">
            @foreach ($all_sections as $menu_section)
                <option value="{{$menu_section->slug}}"
                        @if ($menu_section->id === $section->id)
                        selected
                    @endif
                >{{$menu_section->name}}</option>
            @endforeach
        </select>
        ｜
        @if ($next_section)
            <a href="/books/{{$book->slug}}/{{$next_section->slug}}">next&nbsp;section&nbsp;&gt;</a>
        @else
            next&nbsp;section&nbsp;&gt;
        @endif
    </p>

@stop


@section('menu')
    @include('menu_menu')
@stop
