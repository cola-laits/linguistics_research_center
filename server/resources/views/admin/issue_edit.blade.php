@extends('admin_layout')

@section('title') Issue #{{$issue->id}} @endsection

@section('content')

    <issue-display id="{{$issue->id}}" issue_json="{{json_encode($issue)}}" languages_json="{{json_encode($languages)}}"></issue-display>

@endsection
