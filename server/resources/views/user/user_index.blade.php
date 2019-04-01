@extends('admin_layout')
 
@section('title') Users @stop
 
@section('content')
 
<div class='col-lg-8 offset-2'>
 
    <h1><i class="fa fa-users"></i> User Administration</h1>
    
    @if (Session::has('message'))
	    <div class="alert alert-info">{{ Session::get('message') }}</div>
	@endif
 
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
 
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Added</th>
                    <th>Updated</th>
                    <th></th>
                </tr>
            </thead>
 
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td>{{{ $user->getFullName() }}}</td>
                    <td>{{{ $user->username }}}</td>
                    <td>{{{ $user->email }}}</td>
                    <td>{{{ $user->created_at->format('m/d/Y h:ia') }}} <br/> by {{{ $user->created_by }}}</td>
                    <td>{{{ $user->updated_at->format('m/d/Y h:ia') }}} <br/> by {{{ $user->updated_by }}}</td>
                    <td>
                        <a href="/admin2/user/{{{ $user->id }}}/edit" class="btn btn-primary pull-left" style="margin-right: 3px;">Edit</a>
                        <a href="/admin2/user/password_form/{{{ $user->id }}}" class="btn btn-info pull-left" style="margin-right: 3px;">Password</a>
                        {{ Form::open(['url' => '/admin2/user/' . $user->id,
                            'method' => 'DELETE',
                            'style' => 'display:inline',
                            'onsubmit' => 'return confirm("Are you sure you want to delete this user?");'
                            ]) }}
                        {{ Form::submit('Delete', ['class' => 'btn btn-danger delete'])}}
                        {{ Form::close() }}
                    </td>
                </tr>
                @endforeach
            </tbody>
 
        </table>
    </div>
 
    <a href="/admin2/user/create" class="btn btn-success">Add User</a>
 
 
</div>
 
@stop
