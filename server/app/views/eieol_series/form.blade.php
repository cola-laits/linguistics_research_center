@extends('admin_layout')
 
@section('title') {{$action}} Series Editor @stop
 
@section('content')
 
<div class='col-lg-8 col-lg-offset-2'>
 
    <h1><i class='fa fa-book'></i> {{$action}} Series</h1>
    
    @if (Session::has('message'))
	    <div class="alert alert-info">{{ Session::get('message') }}</div>
	@endif
    
    @if ($errors->has())
    	<div class='bg-danger alert'>
    		<ul>
	        @foreach ($errors->all() as $error)
	            <li>{{ $error }}</li>
	        @endforeach
	        </ul>
        </div>
    @endif
 
 	@if ($action == 'Create')
 		{{ Form::open(['role' => 'form', 'url' => '/admin/eieol_series']) }}
 	@else
    	{{ Form::model($series, ['role' => 'form', 'url' => '/admin/eieol_series/' . $series->id, 'method' => 'PUT']) }}
	@endif
			
    <div class='form-group @if ($errors->has('title')) has-error @endif  '>
        {{ Form::label('title', 'Title') }}
        {{ Form::text('title', null, ['placeholder' => 'Title', 'class' => 'form-control']) }}
    </div>
    
    <div class='form-group @if ($errors->has('order')) has-error @endif  '>
        {{ Form::label('order', 'Order') }}
        {{ Form::text('order', null, ['placeholder' => 'Order', 'class' => 'form-control']) }}
    </div>
    
    <div class='form-group @if ($errors->has('published')) has-error @endif  '>
        {{ Form::label('published', 'Published') }}
        {{ Form::checkbox('published', 1, ['class' => 'form-control']) }}
    </div>
    
    <div class='form-group @if ($errors->has('menu_name')) has-error @endif  '>
        {{ Form::label('menu_name', 'Menu Name') }}
        {{ Form::text('menu_name', null, ['placeholder' => 'Menu Name', 'class' => 'form-control']) }}
    </div>
    
    <div class='form-group @if ($errors->has('menu_order')) has-error @endif  '>
        {{ Form::label('menu_order', 'Menu Order') }}
        {{ Form::text('menu_order', null, ['placeholder' => 'Menu Order', 'class' => 'form-control']) }}
    </div>
    
    <div class='form-group @if ($errors->has('expanded_title')) has-error @endif  '>
        {{ Form::label('expanded_title', 'Expanded Title') }}
        {{ Form::text('expanded_title', null, ['placeholder' => 'Expanded Title', 'class' => 'form-control']) }}
    </div>
 
    <div class='form-group'>
        {{ Form::submit('Update', ['class' => 'btn btn-primary']) }}
    </div>
 
    {{ Form::close() }}
 
</div>
 
@stop