@extends('admin_layout')

@section('title') New Issue @endsection

@section('content')

    <issue-create pointer="{{$pointer}}" issue_json="{{json_encode($issue)}}" languages_json="{{json_encode($languages)}}"></issue-create>

@endsection
