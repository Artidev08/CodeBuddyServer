@extends('layouts.main')
@section('title', $label)
@section('content')
    @php
        $breadcrumb_arr = [
            [
                'name' => $agent ? $agent->getPrefix() : 'Agents',
                'url' => route('panel.member.users.index', ['role' => 'Member']),
                'class' => '',
            ],
            ['name' => 'Agents', 'url' => 'javascript:void(0);', 'class' => 'active'],
        ];
    @endphp
    <!-- push external head elements to head -->
    @push('head')
        <style>
            .featured-icon i {
                font-size: 14px;
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
                            <h5>{{ $label }} </h5>
                            <span>@lang('admin/ui.list_of') {{ $label }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.member.include.breadcrumb')
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3>{{ $label }}</h3>
                        <div class="d-flex align-items-center">
                            @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'delete_register'))
                                <a href="{{ route('panel.member.agent-content-registers.destroy', [0, 'agent_id' => request()->agent_id, 'type' => 'all']) }}"
                                    class="dropdown-item  delete-item btn btn-sm btn-outline-danger mr-2" title="Reset All Registers"> @lang('admin/ui.reset_registers')
                                </a>
                            @endif
                            @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'add_register'))
                                <a href="{{ route('panel.member.agent-content-registers.create', ['agent_id' => request()->agent_id]) }}"
                                    class="btn btn-sm btn-outline-primary mr-2" title="Add New Register"><i class="fa fa-plus"
                                    aria-hidden="true"></i> @lang('admin/ui.add') </a>
                            @endif

                            @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'delete_register'))
                                <form action="{{ route('panel.member.agent-content-registers.bulk-action') }}" method="POST"
                                    id="bulkAction" class="">
                                    @csrf
                                    <input type="hidden" name="ids" id="bulk_ids">
                                    <div>
                                        <button class="dropdown-toggle p-0 custom-dopdown bulk-btn btn btn-light" type="button"
                                            id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false"><i class="ik ik-more-vertical fa-lg pl-1"></i></button>
                                        <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                            @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'delete_register'))
                                            <button type="submit" class="dropdown-item bulk-action text-danger fw-700"
                                                data-value="" data-message="You want to delete these?" data-action="delete"
                                                data-callback="bulkDeleteCallback"><i class="ik ik-trash">
                                                </i> Bulk
                                                Delete
                                            </button>
                                            @endif
                                        </ul>
                                    </div>
                                </form>
                            @endif

                        </div>
                    </div>
                    <div id="ajax-container">
                        @include('panel.member.agent-content-registers.load')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('panel.member.agent-content-registers.include.filter')

    <!-- push external js -->
    @push('script')
        @include('panel.member.include.bulk-script')
        {{-- START HTML TO EXCEL BUTTON INIT --}}
        <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

        <script>
            $(document).ready(function() {
                // $('#department_id').trigger('change');
            });

            function html_table_to_excel(type) {
                var table_core = $("#table").clone();
                var clonedTable = $("#table").clone();
                clonedTable.find('[class*="no-export"]').remove();
                clonedTable.find('[class*="d-none"]').remove();
                $("#table").html(clonedTable.html());

                // Use in reverse format beacuse we are prepending it.
                var report_format = [{
                        'label': "Category",
                        'value': "{{ $category->name ?? 'All Category' }}"
                    },
                    {
                        'label': "Date Range",
                        'value': "{{ request()->get('from') ?? 'N/A' }} - {{ request()->get('to') ?? 'N/A' }}"
                    },
                    {
                        'label': "Report Name",
                        'value': "Agent Report"
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
            });
        </script>
        <script>
            $('#reset').click(function() {
                fetchData("{{ route('panel.member.agent-content-registers.index') }}");
                window.history.pushState("", "", "{{ route('panel.member.agent-content-registers.index') }}");
                $('#TableForm').trigger("reset");
                $(document).find('.close.off-canvas').trigger('click');
            });
        </script>
        <script>
            function changeStatus(status, id, element) {
                // Get the selected value from the select element
                var selectedValue = element.value;
                // Send the selected value using AJAX
                $.ajax({
                    url: '{{ route('panel.member.agent-content-registers.update-status') }}',
                    method: 'POST',
                    data: {
                        status: status,
                        id: id,
                        value: selectedValue,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        pushNotification(response.message, response.title, response.status);
                    },
                    error: function(xhr, status, error) {
                        pushNotification(error, error, status);
                    }
                });
            }
        </script>
        {{-- END RESET BUTTON INIT --}}
    @endpush
@endsection
