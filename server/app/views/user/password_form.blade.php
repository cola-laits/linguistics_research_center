@extends('admin_layout')
 
@section('title') User Password @stop
 
@section('content')
 
<div class='col-lg-8 col-lg-offset-2'>
 
    <h1><i class='fa fa-lock'></i> User Password</h1>
    
    @if ($errors->has())
    	<div class='bg-danger alert'>
    		<ul>
	        @foreach ($errors->all() as $error)
	            <li>{{{ $error }}}</li>
	        @endforeach
	        </ul>
        </div>
    @endif
 
 	{{ Form::open(['role' => 'form', 
 				   'method' => 'PUT', 
 				   'url' => '/admin/user/change_password/' . $id]) }}
 
    <div class='form-group @if ($errors->has('password')) has-error @endif  '>
        {{ Form::label('password', 'Password') }}
        {{ Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) }}
    </div>
 
    <div class='form-group @if ($errors->has('password')) has-error @endif  '>
        {{ Form::label('password_confirmation', 'Confirm Password') }}
        {{ Form::password('password_confirmation', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) }}
    </div>
 
    <div class='form-group'>
        {{ Form::submit('Change', ['class' => 'btn btn-primary']) }}
    </div>
 
    {{ Form::close() }}
 
</div>
 
@stop