@extends('admin_layout')
 
@section('title') {{{$action}}} Language @stop
 
@section('content')
 
<div class='col-lg-8 col-lg-offset-2'>
 
    <h1><i class='fa fa-comment'></i> {{{$action}}} Language</h1>
    
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
 		{{ Form::open(['role' => 'form', 'url' => '/admin/eieol_language']) }}
 	@else
    	{{ Form::model($language, ['role' => 'form', 'url' => '/admin/eieol_language/' . $language->id, 'method' => 'PUT']) }}
	@endif
 
    <div class='form-group @if ($errors->has('language')) has-error @endif  '>
        {{ Form::label('language', 'Language') }}
        {{ Form::text('language', null, ['placeholder' => 'Language', 'class' => 'form-control']) }}
    </div>
    
    <div class='form-group @if ($errors->has('custom_keyboard_layout')) has-error @endif  '>
        {{ Form::label('custom_keyboard_layout', 'Custom Keyboard Layout') }}
        {{ Form::textarea('custom_keyboard_layout', null, ['placeholder' => 'Custom Keyboard Layout', 'class' => 'form-control', 'size' => '150x25']) }}
    </div>
 
    <div class='form-group'>
        {{ Form::submit($action, ['class' => 'btn btn-primary']) }}
    </div>
 
    {{ Form::close() }}
 
</div>
 
@stop