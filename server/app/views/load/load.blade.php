@extends('admin_layout')
 
@section('title') Load @stop
 
@section('content')

	{{ Form::open(['url' => '/admin/eieol_delete/', 'method' => 'POST']) }}
    {{ Form::submit('EIEOL Delete', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
    
    {{ Form::open(['url' => '/admin/eieol_load/', 'method' => 'POST']) }}
    {{ Form::submit('EIEOL Load', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
    
    {{ Form::open(['url' => '/admin/index_load/', 'method' => 'POST']) }}
    {{ Form::submit('Index Load', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
    
    {{ Form::open(['url' => '/admin/pos_analysis_load/', 'method' => 'POST']) }}
    {{ Form::submit('POS/Analysis Load', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
    
	<hr/>
	
	{{ Form::open(['url' => '/admin/element_count/', 'method' => 'POST']) }}
    {{ Form::submit('Element Count', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
    
    <hr/>
	
	{{ Form::open(['url' => '/admin/lex_sources_load/', 'method' => 'POST']) }}
    {{ Form::submit('Load Lex Sources', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
    
    {{ Form::open(['url' => '/admin/lex_pos_load/', 'method' => 'POST']) }}
    {{ Form::submit('Load Lex POS', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
    
    {{ Form::open(['url' => '/admin/lex_lang_load/', 'method' => 'POST']) }}
    {{ Form::submit('Load Lex Lang', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
    
    {{ Form::open(['url' => '/admin/lex_sem_load/', 'method' => 'POST']) }}
    {{ Form::submit('Load Lex Semantics', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
 
@stop