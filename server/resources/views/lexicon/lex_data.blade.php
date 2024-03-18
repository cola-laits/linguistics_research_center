@extends('lexicon.layout')

@section('title')
    {{__('lexicon.pages.data.html_head_title', ['lexicon_name'=>$lexicon->name])}}
@endsection

@section('page-title')
    {{__('lexicon.pages.data.page_title', ['lexicon_name'=>$lexicon->name])}}
@endsection

@section('header_extras')
    <!-- datatables (bootstrap 5 styling), jquery 3, buttons/colvis/export, fixedcolumns, fixedheader -->
    <link href="/assets/datatables/datatables.min.css" rel="stylesheet">
    <script src="/assets/datatables/datatables.min.js"></script>

    <style>
        .column-text-search {
            width: 100%;
            border-left-width: 0;
            border-right-width: 0;
            border-top-width: 0;
        }

        .colvis-control-button {
            background-color: lightgrey;
        }

        .colvis-control-button:hover {
            background-color: lightgrey;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#datatable thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#datatable thead');

            var table = new DataTable('#datatable', {
                @if (Lang::get('lexicon.pages.data.datatables_translation_file')!=="en-US.json")
                    {{-- if the translation file is not the default (US English), load it --}}
                language: {
                    url: '/assets/datatables/plugins/i18n/{{__('lexicon.pages.data.datatables_translation_file')}}',
                },
                @endif
                orderCellsTop: true,
                fixedColumns: true,
                fixedHeader: true,
                scrollY: true,
                scrollX: true,
                paging: true,
                lengthMenu: [
                    [ 10, 25, 50, -1 ],
                    [
                        '{{__('lexicon.pages.data.show_n_rows_option', ['num'=>10])}}',
                        '{{__('lexicon.pages.data.show_n_rows_option', ['num'=>25])}}',
                        '{{__('lexicon.pages.data.show_n_rows_option', ['num'=>50])}}',
                        '{{__('lexicon.pages.data.show_all_rows_option')}}',
                    ]
                ],
                columnDefs: [
                    { orderable: false, targets: 0 }
                ],
                order: [[1, 'asc']],
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'pageLength',
                    },
                    {
                        text: '{{__('lexicon.pages.data.csv_export_button_label')}}',
                        extend: 'csv',
                    },
                    {
                        extend: 'colvis',
                        text: '{{__('lexicon.pages.data.column_visibility_button_label')}}',
                        collectionLayout: 'dropdown columns',
                        columns: 'th:not(:first-child)',
                        prefixButtons: [{
                            text: '{{__('lexicon.pages.data.show_all_columns_button_label')}}',
                            className: 'colvis-control-button',
                            action: function(e, dt) {
                                dt.columns().visible(true);
                            }
                        }, {
                            text: '{{__('lexicon.pages.data.hide_all_columns_button_label')}}',
                            className: 'colvis-control-button',
                            action: function(e, dt) {
                                var cols = dt.columns()[0];
                                cols = cols.filter(function (ix) {
                                    // leave the 'show column' and at least one data column visible
                                    return ix > 1;
                                });
                                dt.columns(cols).visible(false);
                            }
                        }]
                    },
                    {
                        text: '{{__('lexicon.pages.data.clear_search_button_label')}}',
                        action: function() {
                            $('.column-text-search').val('');
                            var table = $('#datatable').DataTable();
                            table
                                .search('')
                                .columns()
                                .search('')
                                .draw();
                        }
                    }
                ],
                initComplete: function () {
                    var api = this.api();

                    // For each column
                    api
                        .columns()
                        .eq(0)
                        .each(function (colIdx) {
                            // Set the header cell to contain the input element
                            var cell = $('.filters th').eq(
                                $(api.column(colIdx).header()).index()
                            );
                            if (colIdx === 0) {
                                $(cell).html('');
                                return;
                            }
                            $(cell).html('<div class="column-text-search-holder d-flex"><svg fill="#000000" width="24px" height="24px" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M 5 4 L 5 6.34375 L 5.21875 6.625 L 13 16.34375 L 13 28 L 14.59375 26.8125 L 18.59375 23.8125 L 19 23.5 L 19 16.34375 L 26.78125 6.625 L 27 6.34375 L 27 4 Z M 7.28125 6 L 24.71875 6 L 17.53125 15 L 14.46875 15 Z M 15 17 L 17 17 L 17 22.5 L 15 24 Z"/></svg> <input type="text" class="column-text-search" /></div>');

                            // On every keypress in this input
                            $(
                                'input',
                                $('.filters th').eq($(api.column(colIdx).header()).index())
                            )
                                .off('keyup change')
                                .on('change', function () {
                                    api
                                        .column(colIdx)
                                        .search(
                                            this.value,
                                            this.value != '',
                                            this.value == ''
                                        )
                                        .draw();
                                })
                                .on('keyup', function (e) {
                                    e.stopPropagation();

                                    $(this).trigger('change');
                                });
                        });
                },
            });

            window.setTimeout(function() {
                recalcDatatableScrollY();
            }, 0);
        });

        $(window).resize(function() {
            recalcDatatableScrollY();
        });

        function recalcDatatableScrollY() {
            var dataTablesScrollBody = $('.dataTables_scrollBody');
            // set the height of the scrollable area to the remaining vertical space after the scrollable area starts,
            // minus height of the info and pagination elements, minus a little extra for padding
            var newHeight = document.documentElement.clientHeight
                - dataTablesScrollBody.offset().top
                - $('#datatable_info').outerHeight()
                - $('#datatable_paginate').outerHeight()
                - 20;
            dataTablesScrollBody.css('height', newHeight + 'px');
        }

        $(document).ready(function() {
            var instructions_modal = new bootstrap.Modal(document.getElementById('instructions_modal'), {
                keyboard: false
            });
            instructions_modal.show();
        });
    </script>
@endsection

@section('content')

    <div class="modal" id="instructions_modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('lexicon.pages.data.help.modal.title')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{!! __('lexicon.pages.data.help.modal.content', [
                        'lexicon_name'=>$lexicon->name,
                        'filter_icon'=><<<END
<svg fill="#000000" width="24px" height="24px" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M 5 4 L 5 6.34375 L 5.21875 6.625 L 13 16.34375 L 13 28 L 14.59375 26.8125 L 18.59375 23.8125 L 19 23.5 L 19 16.34375 L 26.78125 6.625 L 27 6.34375 L 27 4 Z M 7.28125 6 L 24.71875 6 L 17.53125 15 L 14.46875 15 Z M 15 17 L 17 17 L 17 22.5 L 15 24 Z"></path></svg>
END
                        ]) !!}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('lexicon.pages.data.help.modal.close')}}</button>
                </div>
            </div>
        </div>
    </div>


    <div>
        <div>
<table id="datatable" class="table table-bordered">
    <thead>
    <tr>
        <th></th>
        @foreach ($columns as $column)
        <th>{{__('lexicon.pages.data.column_header_'.$column->display_name)}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach ($reflexes as $item)
    <tr>
        <td>
            <a href="/lexicon/semitilex/word/{{$item->id}}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"><path d="M4 21.4V2.6a.6.6 0 0 1 .6-.6h11.652a.6.6 0 0 1 .424.176l3.148 3.148A.6.6 0 0 1 20 5.75V21.4a.6.6 0 0 1-.6.6H4.6a.6.6 0 0 1-.6-.6ZM8 10h8m-8 8h8m-8-4h4"/><path d="M16 2v3.4a.6.6 0 0 0 .6.6H20"/></g></svg></a>
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
