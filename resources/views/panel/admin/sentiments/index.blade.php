{{--
* Project: Sentiment
* 
* @category ZStarter
* @ref zCRUD GENERATOR 
* 
* @license Proprietary - Unauthorized copying, use, or distribution is strictly prohibited.
* License details: https://www.defenzelite.com/license
* 
* (c) Defenzelite. All rights reserved.
* @contact hq@defenzelite.com
* 
* @version zStarter: 1.1.2
--}}
@extends('layouts.main')
@section('title', 'Sentiments')
@section('content')
    @php
        $breadcrumb_arr = [['name' => 'Sentiments', 'url' => 'javascript:void(0);', 'class' => 'active']];
    @endphp
    <!-- push external head elements to head -->
    @push('head')
        <style>
            #recent_searches {
                height: 30px;
            }

            .custom-badge {
                padding: 8px 8px;
                background-color: #80808052;
                border-radius: 10px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            }

            .custom-badge:hover {
                background-color: #80808087;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            }
        </style>
    @endpush
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>Sentiments </h5>
                            <span>@lang('admin/ui.list_of') Sentiments</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb')
                </div>
            </div>
        </div>
        <!-- start message area-->
        <div class="ajax-message text-center"></div>
        <!-- end message area-->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3>Sentiments @if (request()->get('trash') == 1)
                                Trashed
                            @endif
                        </h3>
                        <span class="font-weight-bold border-bottom trash-option   d-none ">Trash</span>
                        <div class="d-flex justicy-content-right">
                            @if ($permissions->contains('add_sentiment'))
                                <a href="{{ route('panel.admin.sentiments.create') }}"
                                    class="btn btn-sm btn-outline-primary mr-2" title="Add New Sentiment">
                                    <i class="fa fa-plus" aria-hidden="true"></i> @lang('admin/ui.add')
                                </a>
                            @endif
                            <div class="dropdown d-flex justicy-content-left">
                                <button class="dropdown-toggle p-0 custom-dopdown bulk-btn btn btn-light" type="button"
                                    id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                        class="ik ik-more-vertical fa-lg pl-1"></i></button>
                                <ul class="dropdown-menu dropdown-position multi-level" role="menu"
                                    aria-labelledby="dropdownMenu">

                                    <a href="javascript:void(0)" class="dropdown-item action"
                                        data-action="is_published-1">Mark as Publish</a>
                                    <a class="dropdown-item action" data-action="is_published-0">Mark as Unpublish</a>
                                    @php
                                    $arrPrompt = [
                                        0 => 'Mark as Disable',
                                        1 => 'Mark as Enable',
                                    ];
                            @endphp
                            @foreach (getSelectValues($arrPrompt) as $key => $option)
                            <a href="javascript:void(0)" class="dropdown-item action"
                                data-action="is_ai_enabled-{{ $key }}">{{ $option }}</a>
                        @endforeach
                            <hr>
                                    <a href="javascript:void(0)" data-action="Move To Trash"
                                        class="dropdown-item action trash-option text-danger fw-700 pt-0"><i
                                            class="ik ik-trash mr-2"></i> @lang('admin/ui.bulk_delete')</a>

                                </ul>
                            </div>
                        </div>
                    </div>
                    <div id="ajax-container">
                        @include('panel.admin.sentiments.load')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('panel.admin.sentiments.includes.filter')
    <!-- push external js -->
    @push('script')
        @include('panel.admin.include.more-action', [
            'actionUrl' => 'admin/sentiments',
            'routeClass' => 'sentiments',
        ])
        <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
        <script>
            $('#reset').click(function() {
                let url = "{{ route('panel.admin.sentiments.index') }}";
                fetchData(url);
                window.history.pushState("", "", url);
                $('#TableForm').trigger("reset");
                $(document).find('.close.off-canvas').trigger('click');
            });

            function getTableContent(key) {
                var url = removeQueryParam(key);
                fetchData(url);
            }

            function removeQueryParam(key) {
                var url = window.location.href;
                var urlObject = new URL(url);
                urlObject.searchParams.delete(key);
                var newUrl = urlObject.pathname + '?' + urlObject.searchParams.toString();
                window.history.replaceState({}, document.title, newUrl);
                return newUrl; // Return the modified URL
            }
        </script>
        <script>
            function tableHeadIconFixer(clonedTable) {
                clonedTable.find('i.icon-head').each(function() {
                    var dataTitle = $(this).data('title');
                    $(this).replaceWith(dataTitle);
                });
                return clonedTable;
            }

            function html_table_to_excel(type) {
                let table_core = $("#table").clone();
                let clonedTable = $("#table").clone();
                clonedTable.find('[class*="no-export"]').remove();
                clonedTable.find('[class*="d-none"]').remove();
                clonedTable = tableHeadIconFixer(clonedTable);
                $("#table").html(clonedTable.html());

                // Use in reverse format beacuse we are prepending it.
                var report_format = [{
                        'label': "Date Range",
                        'value': "{{ request()->get('from') ?? 'N/A' }} - {{ request()->get('to') ?? 'N/A' }}"
                    },
                    {
                        'label': "Report Name",
                        'value': "Sentiments Report"
                    },
                    {
                        'label': "Company",
                        'value': "{{ env('APP_NAME') }}"
                    }
                ];

                var report_name = report_format[2]['value'] + " | " + Date.now();
                // Create a single blank row
                var blankRow = document.createElement('tr');
                var blankCell = document.createElement('th');
                blankCell.colSpan = clonedTable.find('thead tr th').length;
                blankRow.appendChild(blankCell);

                // Append the blank row to the cloned table's thead
                clonedTable.find('thead').prepend(blankRow);

                // Iterate through the report_format array and add metadata rows to the cloned table's thead
                $.each(report_format, function(index, item) {
                    var metadataRow = document.createElement('tr');
                    var labelCell = document.createElement('th');
                    var valueCell = document.createElement('th');

                    labelCell.innerHTML = item.label;
                    valueCell.innerHTML = item.value;

                    metadataRow.appendChild(labelCell);
                    metadataRow.appendChild(valueCell);

                    clonedTable.find('thead').prepend(metadataRow);
                });

                var data = clonedTable[0]; // Use the cloned table for export

                var file = XLSX.utils.table_to_book(data, {
                    sheet: "sheet1"
                });

                // Write and download the Excel file
                XLSX.write(file, {
                    bookType: type,
                    bookSST: true,
                    type: 'base64'
                });
                XLSX.writeFile(file, report_name + '.' + type);

                $("#table").html(table_core.html());

            }

            $(document).on('click', '#export_button', function() {
                html_table_to_excel('xlsx');
            })
        </script>

        <script></script>

        <script></script>
    @endpush
@endsection
