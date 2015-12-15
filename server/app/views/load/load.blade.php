@extends('admin_layout')
 
@section('title') Load @stop
 
@section('content')
Languages and Sessions need to be copied over using MYSQL export/imports<hr/>


	{{ Form::open(['url' => '/admin2/eieol_delete/', 'method' => 'POST']) }}
    {{ Form::submit('EIEOL Delete', ['class' => 'btn btn-danger'])}}
    The delete function deletes all tables except EIOL POS, EIEOL Analysis, user, migrations and sessions.  Use if you need to reload.
    {{ Form::close() }}
    
    <hr/>
    
    {{ Form::open(['url' => '/admin2/eieol_load/', 'method' => 'POST']) }}
    {{ Form::submit('EIEOL Load', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
    
    {{ Form::open(['url' => '/admin2/index_load/', 'method' => 'POST']) }}
    {{ Form::submit('Index Load', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
    
    {{ Form::open(['url' => '/admin2/pos_analysis_load/', 'method' => 'POST']) }}
    {{ Form::submit('POS/Analysis Load', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
    
    {{ Form::open(['url' => '/admin2/gloss_sweep/', 'method' => 'POST']) }}
    {{ Form::submit('Gloss Sweep', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
       
	<hr/>
	
	{{ Form::open(['url' => '/admin2/element_count/', 'method' => 'POST']) }}
    {{ Form::submit('Element Count', ['class' => 'btn btn-default'])}}
    Not a load, just for reporting
    {{ Form::close() }}
    
    <hr/>
	
	{{ Form::open(['url' => '/admin2/lex_sources_load/', 'method' => 'POST']) }}
    {{ Form::submit('Load Lex Sources', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
    
    {{ Form::open(['url' => '/admin2/lex_pos_load/', 'method' => 'POST']) }}
    {{ Form::submit('Load Lex POS', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
    
    {{ Form::open(['url' => '/admin2/lex_lang_load/', 'method' => 'POST']) }}
    {{ Form::submit('Load Lex Lang', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
    
    {{ Form::open(['url' => '/admin2/lex_sem_load/', 'method' => 'POST']) }}
    {{ Form::submit('Load Lex Semantics', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
    
    {{ Form::open(['url' => '/admin2/lex_load/', 'method' => 'POST']) }}
    {{ Form::submit('Load Lexes', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
    
    {{ Form::open(['url' => '/admin2/lex_cross_load/', 'method' => 'POST']) }}
    {{ Form::submit('Load Cross Listed Etymas', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
    
    {{ Form::open(['url' => '/admin2/sem_etyma_load/', 'method' => 'POST']) }}
    {{ Form::submit('Semantic-Etyma Load', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
    
    {{ Form::open(['url' => '/admin2/default_alpha/', 'method' => 'POST']) }}
    {{ Form::submit('Set default alphabet on Language', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
    
    {{ Form::open(['url' => '/admin2/link_headword_to_eytma/', 'method' => 'POST']) }}
    {{ Form::submit('Link Headwords to etymas, dude', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
    
    <hr/>
	
	{{ Form::open(['url' => '/admin2/paren_count/', 'method' => 'POST']) }}
    {{ Form::submit('Paren Count', ['class' => 'btn btn-default'])}}
    Not a load, just for reporting
    {{ Form::close() }}
    
    <hr/>
    
    {{ Form::open(['url' => '/admin2/sweep_anal_and_pos/', 'method' => 'POST']) }}
    {{ Form::submit('Sweep Anal and POS', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
    
    {{ Form::open(['url' => '/admin2/delete_langless_anal_and_pos/', 'method' => 'POST']) }}
    {{ Form::submit('Delete Language-less Anal and POS', ['class' => 'btn btn-danger'])}}
    {{ Form::close() }}
    
@stop