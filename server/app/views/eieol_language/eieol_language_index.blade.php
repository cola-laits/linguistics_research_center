@extends('admin_layout')
 
@section('title') Languages @stop
 
@section('content')
 
<div class='col-lg-8 col-lg-offset-2'>
 
    <h1><i class="fa fa-comments"></i> Language Administration</h1>
    
    @if (Session::has('message'))
	    <div class="alert alert-info">{{ Session::get('message') }}</div>
	@endif
 
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
 
            <thead>
                <tr>
                    <th>Language</th>
                    <th>Added</th>
                    <th>Updated</th>
                    <th></th>
                </tr>
            </thead>
 
            <tbody>
                @foreach ($languages as $language)
                <tr>
                    <td>{{{ $language->language }}}</td>
                    <td>{{{ $language->created_at->format('m/d/Y h:ia') }}} <br/> by {{{ $language->created_by }}}</td>
                    <td>{{{ $language->updated_at->format('m/d/Y h:ia') }}} <br/> by {{{ $language->updated_by }}}</td>
                    <td>
                        <a href="/admin/eieol_language/{{{ $language->id }}}/edit" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
 
        </table>
    </div>
 
    <a href="/admin/eieol_language/create" class="btn btn-success">Add Language</a>
 
 
</div>
 
@stop