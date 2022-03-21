@extends('admin_layout')

@section('content')

<div class='col-lg-12'>

    <h1><i class="fa fa-book"></i> Series Administration</h1>
    <p><a href="/guides/eieol_author" target=_new>Author Guide</a></p>

    @if (Session::has('message'))
	    <div class="alert alert-info">{{ Session::get('message') }}</div>
	@endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>Title</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @foreach ($serieses as $series)
                <tr>
                    <td>{{ $series->title }}</td>
                    <td>
                        <a href="/admin2/eieol_series/{{ $series->id }}/edit" class="btn btn-info pull-left" style="margin-right: 3px;">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

</div>

@stop
