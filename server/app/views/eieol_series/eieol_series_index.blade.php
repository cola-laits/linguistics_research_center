@extends('admin_layout')
 
@section('title') Series @stop
 
@section('content')
 
<div class='col-lg-12'>
 
    <h1><i class="fa fa-book"></i> Series Administration</h1>
    
    @if (Session::has('message'))
	    <div class="alert alert-info">{{ Session::get('message') }}</div>
	@endif
 
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
			
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Title</th>
                    <th>Published</th>
                    <th>Menu Name</th>
                    <th>Menu Order</th>
                    <th>Expanded Title</th>
                    <th>Updated</th>
                    <th></th>
                </tr>
            </thead>
 
            <tbody>
                @foreach ($serieses as $series)
                <tr>
                    <td>{{ $series->order }}</td>
                    <td>{{ $series->title }}</td>
                    <td>
                    	@if ($series->published == True)
                    		<i class="fa fa-check" style="color:green"></i>
                    	@else
                    		<i class="fa fa-times" style="color:red"></i>
                    	@endif
                    
                    </td>
                    <td>{{ $series->menu_name }}</td>
                    <td>{{ $series->menu_order }}</td>
                    <td>{{ $series->expanded_title }}</td>
                    <td>{{ $series->updated_at->format('m/d/Y h:ia') }} by {{ $series->updated_by }}</td>
                    <td>
                        <a href="/admin/eieol_series/{{ $series->id }}/edit" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
 
        </table>
    </div>
 
    <a href="/admin/eieol_series/create" class="btn btn-success">Add New Series</a>
 
 
</div>
 
@stop