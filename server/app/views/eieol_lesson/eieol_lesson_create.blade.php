@extends('admin_layout')
 
@section('title') Create Lesson @stop
 
@section('content')
 
<div class='col-lg-12'>
 
    <h1><i class='fa fa-file-text'></i> Create Lesson for {{ HTML::link('admin2/eieol_series/' . $series->id . '/edit', $series->title , array('title' => 'Return to series' )) }}</h1>
    
    @if (Session::has('message'))
	    <div class="alert alert-info">{{ Session::get('message') }}</div>
	@endif
    
    @if ($errors->has())
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
	        {{ Form::select('language', $languages, null, ['class' => 'form-control']) }}
	    </div>
    
    </div>
	    
    <br/>
    
    <div class='form-group @if ($errors->has('intro_text')) has-error @endif  '>
        {{ Form::label('intro_text', 'Intro Text') }}
        {{ Form::textarea('intro_text', null, ['placeholder' => 'Intro Text', 'class' => 'form-control', 'size' => '100x10']) }}
    </div>

	<br/>
 
    <div class='form-group col-sm-1'>
        {{ Form::submit('Create', ['class' => 'btn btn-primary']) }}
    </div>
	
    {{ Form::close() }}
    
</div>

<script>
	CKEDITOR.plugins.addExternal( 'onchange', '/js/', 'onchangeplugin.js' );
	CKEDITOR.replace( 'intro_text',{toolbar : $mytoolbar, 
									contentsCss : '/css/lrcstyle.css', 
									disableNativeSpellChecker:false,
									allowedContent : true, 
									extraPlugins : 'onchange'
									} );
	
</script>
 
@stop