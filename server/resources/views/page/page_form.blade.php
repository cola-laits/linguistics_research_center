@extends('admin_layout')
 
@section('title') {{{$action}}} Page @stop
 
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
        {{ Form::textarea('content', null, ['placeholder' => 'Page content', 'class' => 'form-control', 'size' => '100x10', 'id' => 'page_content']) }}
    </div>
 
    <div class='form-group'>
        {{ Form::submit($action, ['class' => 'btn btn-primary']) }}
    </div>
 
    {{ Form::close() }}
 
     <script>
     
      var toolbar =
      [
        { name: 'document', items : [ 'Source'] },
        { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
        { name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker' ] },
        { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
        { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','-',
                                 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
        { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
        { name: 'insert', items : [ 'Table','HorizontalRule','SpecialChar'] },
        { name: 'styles', items : [ 'Format','FontSize' ] },
        { name: 'colors', items : [ 'TextColor','BGColor' ] },
        { name: 'insert', items : [ 'Image' ]  },
        { name: 'tools', items : [ 'Maximize'] }
      ];
     
     
      var ckeditor_parms = {
        toolbar : toolbar,
        contentsCss : '/css/lrcstyle.css',
        disableNativeSpellChecker : false, 
        allowedContent : true,
        enterMode : 'CKEDITOR.ENTER_BR'
      };

      CKEDITOR.replace( 'page_content', ckeditor_parms);
          
                
    </script>
    
</div>
 
@stop
