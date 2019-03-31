@extends('admin_layout')
 
@section('title') Pages @stop
 
@section('content')
 
<div class='col-lg-8 offset-2'>
 
    <h1><i class="fa fa-users"></i> Page Administration</h1>
    
    @if (Session::has('message'))
	    <div class="alert alert-info">{{ Session::get('message') }}</div>
	@endif
 
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
 
            <thead>
                <tr>
                    <th>Route</th>
                    <th>Name</th>
                    <th>Updated</th>
                    <th></th>
                </tr>
            </thead>
 
            <tbody>
                @foreach ($pages as $page)
                <tr>
                    <td>{{{ $page->slug }}}</td>
                    <td>{{{ $page->name }}}</td>
                    <td>{{{ $page->updated_at->format('m/d/Y h:ia') }}} </td>
                    <td>
                        <a href="/admin2/page/{{{ $page->id }}}/edit" class="btn btn-primary pull-left" style="margin-right: 3px;">Edit</a>
                        {{-- Form::open(['url' => '/admin2/page/' . $page->id, 'method' => 'DELETE', 'style' => 'display:inline']) }}
                        {{ Form::submit('Delete', ['class' => 'btn btn-danger delete'])}}
                        {{ Form::close() --}}
                    </td>
                </tr>
                @endforeach
            </tbody>
 
        </table>
    </div>
 
    <!--<a href="/admin2/page/create" class="btn btn-success">Add Page</a>-->
 
 
</div>
 
@stop
