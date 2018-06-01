@extends('admin_layout')
 
@section('title') {{{$action}}} Page @stop
 
@section('content')
 
<div class='col-lg-8 col-lg-offset-2'>
 
    <h1><i class='fa fa-user'></i> {{{$action}}} Page</h1>
    
    @if ($errors->has())
    	<div class='bg-danger alert'>
    		<ul>
	        @foreach ($errors->all() as $error)
	            <li>{{{ $error }}}</li>
	        @endforeach
	        </ul>
        </div>
    @endif
 
 	@if ($action == 'Create')
 		{{ Form::open(['role' => 'form', 'url' => '/admin2/page']) }}
 	@else
    	{{ Form::model($page, ['role' => 'form', 'url' => '/admin2/page/' . $page->id, 'method' => 'PUT']) }}
	@endif
    
    <div class='form-group @if ($errors->has('slug')) has-error @endif  '>
        {{ Form::label('slug', 'Slug') }}
        @if ($action == 'Create')
        {{ Form::text('slug', null, ['placeholder' => 'Slug', 'class' => 'form-control']) }}
        @else
        {{{$slug}}}
        @endif
    </div>
    
    <div class='form-group @if ($errors->has('name')) has-error @endif  '>
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) }}
    </div>
    
    <div class='form-group @if ($errors->has('content')) has-error @endif  '>
        {{ Form::label('last_name', 'Content') }}
        {{ Form::textarea('content', null, ['placeholder' => 'Page content', 'class' => 'form-control', 'size' => '100x10', 'id' => 'new_page_content']) }}
    </div>
 
    <div class='form-group'>
        {{ Form::submit($action, ['class' => 'btn btn-primary']) }}
    </div>
 
    {{ Form::close() }}
 
</div>
 
@stop