{{--
* Project: Content Category
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
@section('title', 'Content Categories')
@section('content')
    @php
        $breadcrumb_arr = [['name' => 'Content Categories', 'url' => 'javascript:void(0);', 'class' => 'active']];
        
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
                            <h5>Content Categories </h5>
                            <span>@lang('admin/ui.list_of') Content Categories</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.member.include.breadcrumb')
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
                        <h3>Content Categories @if (request()->get('trash') == 1)
                                Trashed
                            @endif
                        </h3>
                        <span class="font-weight-bold border-bottom trash-option   d-none ">Trash</span>
                        <div class="d-flex justicy-content-right">
                            @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'add_content_category'))
                           
                                <a href="{{ route('panel.member.content-categories.create') }}"
                                    class="btn btn-sm btn-outline-primary mr-2" title="Add New Content"><i
                                        class="fa fa-plus" aria-hidden="true"></i> @lang('admin/ui.add') </a>
                            
                            @endif
                            @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) && isUserHasPermission($authUser->permissions['permissions'], 'delete_content_category') && isUserHasPermission($authUser->permissions['permissions'], 'enable_disable_content_category'))
                            <div class="dropdown d-flex justicy-content-left">
                                <button class="dropdown-toggle p-0 custom-dopdown bulk-btn btn btn-light" type="button"
                                    id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                        class="ik ik-more-vertical fa-lg pl-1"></i></button>
                                <ul class="dropdown-menu dropdown-position multi-level" role="menu"
                                    aria-labelledby="dropdownMenu">
                                    @php
                                        $arrPrompt = [
                                            0 => 'Mark as Disable',
                                            1 => 'Mark as Enable',
                                        ];
                                    @endphp
                                    @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'enable_disable_content_category'))
                                    @foreach (getSelectValues($arrPrompt) as $key => $option)
                                        <a href="javascript:void(0)" class="dropdown-item action"
                                            data-action="is_ai_enabled-{{ $key }}">{{ $option }}</a>
                                    @endforeach
                                    @endif
                                    <hr>
                                    @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'delete_content_category'))
                                    <a href="javascript:void(0)" data-action="Move To Trash"
                                        class="dropdown-item action trash-option text-danger fw-700  pt-0"><i
                                            class="ik ik-trash mr-2 "></i> @lang('admin/ui.bulk_delete')</a>
                                            @endif
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div id="ajax-container">
                        @include('panel.member.content-categories.load')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('panel.member.content-categories.includes.filter')
    <!-- push external js -->
    @push('script')
        @include('panel.member.include.more-action', [
            'actionUrl' => 'member/content-categories',
            'routeClass' => 'content-categories',
        ])
        <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
        <script>
            $('#reset').click(function() {
                let url = "{{ route('panel.member.content-categories.index') }}";
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
                        'value': "Content Categories Report"
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
