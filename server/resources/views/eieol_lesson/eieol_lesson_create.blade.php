@extends('admin_layout')
 
@section('title') Create Lesson @stop

@section('content')
 
<div class='col-lg-12'>
 
    <h1><i class='fa fa-file-text'></i> Create Lesson for <a href="/admin2/eieol_series/{{$series->id}}/edit" title="Return to series">{{$series->title}}</a> </h1>
    <p><a href="/guides/eieol_author" target=_new>Author Guide</a></p>
    
    @if (Session::has('message'))
	    <div class="alert alert-info">{{ Session::get('message') }}</div>
	@endif
    
    @if (count($errors)>0)
    	<div class='bg-danger alert'>
    		<ul>
	        @foreach ($errors->all() as $error)
	            <li>{{{ $error }}}</li>
	        @endforeach
	        </ul>
        </div>
    @endif
 
 	{{ Form::open(['role' => 'form', 'url' => '/admin2/eieol_lesson', 'class' => 'form']) }}
	
	{{ Form::hidden('series_id', $series->id) }}
	
	<div class='row'>
		<div class='form-group col-sm-1 @if ($errors->has('order')) has-error @endif  '>
	        {{ Form::label('order', 'Order') }}
	        {{ Form::text('order', null, ['placeholder' => 'Order', 'class' => 'form-control']) }}
	    </div>
	    	
	    <div class='form-group col-sm-3 @if ($errors->has('title')) has-error @endif  '>
	        {{ Form::label('title', 'Title') }}
	        {{ Form::text('title', null, ['placeholder' => 'Title', 'class' => 'form-control']) }}
	    </div>
	    
	    <div class='form-group col-sm-2 @if ($errors->has('language')) has-error @endif  '>
	        {{ Form::label('language', 'Language') }}<br/>
	        {{ Form::select('language', $languages, null, ['placeholder'=>'Select a Language','class' => 'form-control']) }}
	    </div>
    
    </div>
	    
    <br/>
    
    <div class='form-group @if ($errors->has('intro_text')) has-error @endif  '>
        <label for="intro_text">Intro Text</label>
        <ck-editor html_id="intro_text" html_name="intro_text"></ck-editor>
    </div>

	<br/>
 
    <div class='form-group col-sm-1'>
        <input class="btn btn-primary" type="submit" value="Create">
    </div>

    </form>
    
</div>
 
@stop
