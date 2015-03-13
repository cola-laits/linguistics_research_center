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
    
    <div class='form-group @if ($errors->has('lang_attribute')) has-error @endif  '>
        {{ Form::label('lang_attribute', 'Lang Attribute') }}
        {{ Form::text('lang_attribute', null, ['placeholder' => 'Lang Attribute', 'class' => 'form-control']) }}
        <div class="alert-warning">
        	This will be added to all span tags in lessons that use this language - &lt;span lang='xxx' class='yyy'&gt;
        </div>
    </div>
    
    <div class='form-group @if ($errors->has('class_attribute')) has-error @endif  '>
        {{ Form::label('class_attribute', 'Class Attribute') }}
        {{ Form::text('class_attribute', null, ['placeholder' => 'Class Attribute', 'class' => 'form-control']) }}
        <div class="alert-warning">
        	This will be added to all span tags in lessons that use this language - &lt;span lang='xxx' class='yyy'&gt;
        </div>
    </div>

    
    <div class='form-group @if ($errors->has('custom_keyboard_layout')) has-error @endif  '>
        {{ Form::label('custom_keyboard_layout', 'Custom Keyboard Layout') }}
        {{ Form::textarea('custom_keyboard_layout', null, ['placeholder' => 'Custom Keyboard Layout', 'class' => 'form-control', 'size' => '150x4']) }}
        <div class="alert-warning">
        	This should be a list of characters (either in unicode code points or pasted in) <br/>
        	Example: 'µ','ā','œ','\u042f', '\u03da', '\u03db', '\u03c0'
        </div>
    </div>
    
    <div class='form-group @if ($errors->has('custom_sort')) has-error @endif  '>
        {{ Form::label('custom_sort', 'Custom Sort') }}
        @if ($action == 'Create')
        	{{ Form::textarea('custom_sort', 'A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z', ['placeholder' => 'Custom Sort', 'class' => 'form-control', 'size' => '150x4']) }}
        @else
        	{{ Form::textarea('custom_sort', null, ['placeholder' => 'Custom Sort', 'class' => 'form-control', 'size' => '150x4']) }}
        @endif
        <div class="alert-warning">
        	This should be a comma separated list of characters in the order the Gloss and Dictionary should be sorter.<br/>
        	Do not use unicode code points, just paste in unicode characters.<br/>
        	Example: A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z,a,b,Ϛ,c,d,e,f,g,h,i,j,k,l,m,n,o,ō,p,π,q,r,s,t,u,v,w,x,y,z<br/>
        	If character aren't separated by a comma, they are considered equal. In the next example, p,P and π are considered the same.<br/>
        	Example: aAā,bB,cϚC,dD,eE,fF,gG,hH,iI,Jj,Kk,Ll,Mm,Nn,Oōo,Ppπ,Qq,Rr,Ss,Tt,Uu,Vv,Ww,Xx,Yy,Zz
        </div>
    </div>
 
    <div class='form-group'>
        {{ Form::submit($action, ['class' => 'btn btn-primary']) }}
    </div>
 
    {{ Form::close() }}
 
</div>
 
@stop