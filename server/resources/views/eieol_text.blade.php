@extends('layout')

@section('title') {{strip_tags($lesson->title)}}@stop

@section('content')


<h1>Early Indo-European Texts</h1>
<h2>{{$series->title}}</h2>
<h3>{!! $lesson->title !!}</h3>


<div class="skinny">

    <blockquote><span lang='{{$lesson->language->lang_attribute}}' class='{{$lesson->language->class_attribute}}'>{!! $lesson->getLessonText() !!}</span></blockquote>
     
    <h3>Translation</h3>
    {!! $lesson->lesson_translation !!}

</div>
@stop


@section('menu')
    @include('menu_menu')
@stop
