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

    <form method="POST" action="/admin2/eieol_lesson" accept-charset="UTF-8" role="form" class="form">
        {{csrf_field()}}

        <input name="series_id" type="hidden" value="{{$series->id}}">

	<div class='form-row'>
		<div class='form-group col-sm-1 @if ($errors->has('order')) has-error @endif  '>
            <label for="order">Order</label>
            <input placeholder="Order" class="form-control" name="order" type="text" id="order">
	    </div>

	    <div class='form-group col-sm-3 @if ($errors->has('title')) has-error @endif  '>
            <label for="title">Title</label>
            <input placeholder="Title" class="form-control" name="title" type="text" id="title">
	    </div>

	    <div class='form-group col-sm-2 @if ($errors->has('language')) has-error @endif  '>
            <label for="language">Language</label><br/>
            <select class="form-control" id="language" name="language">
                <option selected="selected" value="">Select a Language</option>
                @foreach ($languages as $key=>$value)
                    <option value="{{$key}}">{{$value}}</option>
                @endforeach
            </select>
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
