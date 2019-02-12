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
                    <th>Lang</th>
                    <th>Class</th>
                    <th>Updated</th>
                    <th></th>
                </tr>
            </thead>
 
            <tbody>
                @foreach ($languages as $language)
                <tr>
                    <td>{{{ $language->language }}}</td>
                    <td>{{{ $language->lang_attribute }}}</td>
                    <td>{{{ $language->class_attribute }}}</td>
                    <td>{{{ $language->updated_at->format('m/d/Y h:ia') }}} <br/> by {{{ $language->updated_by }}}</td>
                    <td>
                        <a href="/admin2/eieol_language/{{{ $language->id }}}/edit" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
 
        </table>
    </div>
 
    <a href="/admin2/eieol_language/create" class="btn btn-success">Add Language</a>
 
 
</div>
 
@stop
