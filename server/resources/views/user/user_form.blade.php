@extends('admin_layout')
 
@section('title') {{{$action}}} User @stop
 
@section('content')
 
<div class='col-lg-8 col-lg-offset-2'>
 
    <h1><i class='fa fa-user'></i> {{{$action}}} User</h1>
    
    @if (count($errors)>0)
    	<div class='bg-danger alert'>
    		<ul>
	        @foreach ($errors->all() as $error)
	            <li>{{{ $error }}}</li>
	        @endforeach
	        </ul>
        </div>
    @endif
 
 	@if ($action == 'Create')
 		{{ Form::open(['role' => 'form', 'url' => '/admin2/user']) }}
 	@else
    	{{ Form::model($user, ['role' => 'form', 'url' => '/admin2/user/' . $user->id, 'method' => 'PUT']) }}
	@endif
 
    <div class='form-group @if ($errors->has('first_name')) has-error @endif  '>
        {{ Form::label('first_name', 'First Name') }}
        {{ Form::text('first_name', null, ['placeholder' => 'First Name', 'class' => 'form-control']) }}
    </div>
 
    <div class='form-group @if ($errors->has('last_name')) has-error @endif  '>
        {{ Form::label('last_name', 'Last Name') }}
        {{ Form::text('last_name', null, ['placeholder' => 'Last Name', 'class' => 'form-control']) }}
    </div>
 
    <div class='form-group @if ($errors->has('username')) has-error @endif  '>
        {{ Form::label('username', 'Username') }}
        {{ Form::text('username', null, ['placeholder' => 'Username', 'class' => 'form-control']) }}
    </div>
 
    <div class='form-group @if ($errors->has('email')) has-error @endif  '>
        {{ Form::label('email', 'Email') }}
        {{ Form::email('email', null, ['placeholder' => 'Email', 'class' => 'form-control']) }}
    </div>
    
    @if ($action == 'Create')
	    <div class='form-group @if ($errors->has('password')) has-error @endif  '>
	        {{ Form::label('password', 'Password') }}
	        {{ Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) }}
	    </div>
	 
	    <div class='form-group @if ($errors->has('password')) has-error @endif  '>
	        {{ Form::label('password_confirmation', 'Confirm Password') }}
	        {{ Form::password('password_confirmation', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) }}
	    </div>
	@endif
	
	<div class='form-group @if ($errors->has('permissions')) has-error @endif  '>
		{{ Form::label('permissions', 'Permissions') }}<br/>
		{{ Form::select('permissions[]', $roles, $selected_permissions, ['multiple' => true, 'size' => count($roles)]) }}
		<div class="alert-warning">
			Users with Administrator Permission can update anything.<br/>
			Users Authorized for a series can only update that series and it's related language(s)<br/>
			If you need to authorize a user for more than one series, use CTRL-click.
		</div>
	</div>
 
    <div class='form-group'>
        {{ Form::submit($action, ['class' => 'btn btn-primary']) }}
    </div>
 
    {{ Form::close() }}
 
</div>
 
@stop
