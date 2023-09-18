@extends('lexicon.layout')

@section('title')
LRC {{$lexicon->name}}: Data
@endsection

@section('header_extras')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new DataTable('#datatable', {
                'paging': false,
                columnDefs: [
                    { orderable: false, targets: 0 }
                ],
                order: [[1, 'asc']]
            });
        });
    </script>
@endsection

@section('content')

    <div>
        <h1>Data</h1>
        <div>
<table id="datatable" class="table table-bordered">
    <thead>
    <tr>
        <th></th>
        @foreach ($columns as $column)
        <th>{{$column->display_name}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach ($reflexes as $item)
    <tr>
        <td>
            <a href="/lexicon/semitilex/word/{{$item->id}}" target="_blank">show</a>
        </td>
        @foreach ($columns as $column)
        <td>{{ $display_value_lookup_fn($item, $column->name) }}</td>
        @endforeach
    </tr>
    @endforeach
    </tbody>
</table>

    </div>
</div>

@endsection
