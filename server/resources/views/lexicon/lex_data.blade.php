@extends('lexicon.layout', ['breadcrumb_segments' => [
    ['text'=>__('lexicon.pages.data.breadcrumb_title', ['lexicon_name'=>$lexicon->name])]
]])

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
        window.search_use_regex = @json(request()->boolean('use_regex'));

        function update_regex_choice(val) {
            document.location.href = "data?use_regex=" + val;
        }

        function show_regex_help() {
            new bootstrap.Modal(document.getElementById('regex_help_modal'), {
                keyboard: false
            }).show();
        }

        window.datatable_scroll_height = '50vh'; // initial guess; will be refined in a redraw after table is loaded into the dom and can be measured
        function create_data_table() {
            window.tableObj = new DataTable('#datatable', {
                @if (Lang::get('lexicon.pages.data.datatables_translation_file')!=="en-US.json")
                    {{-- if the translation file is not the default (US English), load it --}}
                language: {
                    url: '/assets/datatables/plugins/i18n/{{__('lexicon.pages.data.datatables_translation_file')}}',
                },
                @endif
                serverSide: true,
                ajax: {
                    url: '/api/v1/lexicon/{{$lexicon->slug}}/data',
                },
                search: {
                    regex: window.search_use_regex
                },
                columns: [
                    { data: 'id', name: 'id', render:
                        function(data) {
                            return '<a href="/lexicon/{{$lexicon->slug}}/word/'+data+'" target="_blank"><i class="far fa-file-alt"></i></a>';
                        }
                    },
                    @foreach ($lexicon->getDataColumns() as $column_info)
                    {
                        data: @json($column_info->name),
                        name: @json($column_info->name),
                        title: @json($column_info->display_name),
                    },
                    @endforeach
                ],
                destroy: true,
                orderCellsTop: true,
                fixedColumns: true,
                fixedHeader: true,
                scrollY: window.datatable_scroll_height,
                scrollX: true,
                paging: true,
                lengthMenu: [
                    [ 10, 25, 50 ],
                    [
                        '{{__('lexicon.pages.data.show_n_rows_option', ['num'=>10])}}',
                        '{{__('lexicon.pages.data.show_n_rows_option', ['num'=>25])}}',
                        '{{__('lexicon.pages.data.show_n_rows_option', ['num'=>50])}}'
                    ]
                ],
                columnDefs: [
                    { orderable: false, targets: 0 }
                ],
                order: [[1, 'asc']],
                layout: {
                    top3Start: 'buttons',
                    top2Start: function() {
                        return document.getElementById('custom_regex_picker').content.cloneNode(true);
                    },
                    topStart: 'pageLength',
                    topEnd: 'search',
                    bottomStart: 'info',
                    bottomEnd: 'paging'
                },
                buttons: [
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
                                dt.columns(':gt(1)').visible(false);
                            }
                        }]
                    },
                    {
                        text: '{{__('lexicon.pages.data.clear_search_button_label')}}',
                        action: function(e, dt) {
                            dt.search('');
                            $('.column-text-search').val('');
                            dt.columns().search('');
                            dt.draw();
                        }
                    },
                    {
                        text: '<i class="far fa-question-circle"></i> {{__('lexicon.pages.data.help_button_label')}}',
                        action: function() {
                            new bootstrap.Modal(document.getElementById('instructions_modal'), {
                                keyboard: false
                            }).show();
                        }
                    }
                ],
                initComplete: function () {
                    let api = this.api();

                    // For each column
                    api
                        .columns()
                        .every(function (colIdx) {
                            let column = this;

                            // Set the header cell to contain the input element
                            let filter_headers = $('.filters th');
                            let cell = filter_headers.eq(
                                $(api.column(colIdx).header()).index()
                            );
                            if (colIdx === 0) {
                                $(cell).html('');
                                return;
                            }
                            $(cell).html('<div class="column-text-search-holder d-flex"><i class="far fa-filter"></i> <input type="text" class="column-text-search" id="column-text-search-'+colIdx+'" /></div>');

                            // On every keypress in this input
                            $(
                                'input',
                                filter_headers.eq($(api.column(colIdx).header()).index())
                            )
                                .on('keyup change', function () {
                                    let input = $(cell).find('input')[0];
                                    if (column.search() !== this.value) {
                                        column.search(input.value, window.search_use_regex, false).draw();
                                    }
                                })
                        });
                },
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            $('#datatable thead tr')
                .clone(true)
                .addClass('filters')
                .appendTo('#datatable thead');

            create_data_table();

            window.setTimeout(function() {
                recalcDatatableScrollY();
            }, 0);
        });

        $(window).resize(function() {
            recalcDatatableScrollY();
        });

        function recalcDatatableScrollY() {
            let datatable_wrapper = document.getElementById('datatable_wrapper');
            let scroll_body = datatable_wrapper.getElementsByClassName('dt-scroll-body')[0];
            let footer = datatable_wrapper.getElementsByClassName('row')[4]; // 3 header rows, then body; footer is 5th row
            let extra_bottom_padding_pixels = 30;
            let new_height = window.innerHeight
                - scroll_body.getBoundingClientRect().top
                - footer.clientHeight
                - extra_bottom_padding_pixels;

            window.datatable_scroll_height = new_height + 'px';
            create_data_table();
        }
    </script>
@endsection

@section('content')
    <template id="custom_regex_picker">
        <div class="dt-search">
            <label for="regex-picker" onclick="show_regex_help()" style="cursor:pointer;">{{__('lexicon.pages.data.use-regex-checkbox.label')}} <i class="far fa-question-circle"></i>:</label>
            <input type="checkbox" id="regex_picker"
                   @checked(request()->boolean('use_regex'))
                   onchange="update_regex_choice(this.checked);">
        </div>
    </template>

    <div class="modal" id="instructions_modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('lexicon.pages.data.help.modal.title')}}</i></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{!! __('lexicon.pages.data.help.modal.content', [
                        'lexicon_name'=>$lexicon->name,
                        'filter_icon'=><<<END
<i class="far fa-filter"></i>
END
                        ]) !!}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('lexicon.pages.data.help.modal.close')}}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="regex_help_modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('lexicon.pages.data.help-regex.modal.title')}}</i></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{!! __('lexicon.pages.data.help-regex.modal.content', [
                        'lexicon_name'=>$lexicon->name,
                        ]) !!}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('lexicon.pages.data.help-regex.modal.close')}}</button>
                </div>
            </div>
        </div>
    </div>


    <div>
<table id="datatable" class="table table-bordered">
    <thead>
    <tr>
        <th></th>
        @foreach ($lexicon->getDataColumns() as $column)
            <th>{{__('lexicon.pages.data.column_header_'.$column->display_name)}}</th>
        @endforeach
    </tr>
    </thead>
</table>
</div>

@endsection
