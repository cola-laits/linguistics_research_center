@extends('admin_layout')
 
@section('title') {{{$action}}} Page @stop

@section('head_extra')
    <script>
        window.admin_app_initial_state = {'page':{!! json_encode($page) !!}};
    </script>
@endsection

@section('content')
 
<div class='col-lg-8 col-lg-offset-2'>
 
    <h1><i class='fa fa-user'></i> {{{$action}}} Page</h1>
    
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
 		{{ Form::open(['role' => 'form', 'url' => '/admin2/page']) }}
 	@else
    	{{ Form::model($page, ['role' => 'form', 'url' => '/admin2/page/' . $page->id, 'method' => 'PUT']) }}
	@endif
    
    <div class='form-group @if ($errors->has('slug')) has-error @endif  '>
        @if ($action == 'Create')
        {{ Form::label('slug', 'Slug') }}
        {{ Form::text('slug', null, ['placeholder' => 'Slug', 'class' => 'form-control']) }}
        @else
        <!--<h2>{{{$slug}}}</h2>-->
        @endif
    </div>
    
    <div class='form-group @if ($errors->has('name')) has-error @endif  '>
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) }}
    </div>
    
    <div class='form-group @if ($errors->has('content')) has-error @endif  '>
        {{ Form::label('last_name', 'Content') }}
        <ck-editor html_id="content" html_name="content" :value="page.content"></ck-editor>
    </div>
 
    <div class='form-group'>
        {{ Form::submit($action, ['class' => 'btn btn-primary']) }}
    </div>
 
    {{ Form::close() }}
    
</div>
 
@stop
