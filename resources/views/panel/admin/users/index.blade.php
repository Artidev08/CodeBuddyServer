@extends('layouts.main')
@section('title', @$label ?? 'Agents')
@section('content')

    @push('head')
        {{-- INITIALIZE SHIMMER & INIT LOAD --}}
        <script>
            window.onload = function() {
                $('#ajax-container').show();
                document.getElementById('reset').click();
            };
        </script>
        {{-- END INITIALIZE SHIMMER & INIT LOAD --}}
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ @$label ?? 'Agents' }}</h5>
                            <span> @lang('admin/ui.list_of') {{ @$label ?? 'Agents' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 d-sm-flex d-lg-block">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('panel.admin.dashboard.index') }}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="#">{{ @$label ?? 'Agents' }}</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3>{{ @$label ?? 'Agents' }}</h3>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('panel.admin.agent-content-registers.destroy', [0, 'type' => 'all']) }}"
                                class="dropdown-item  delete-item btn btn-sm btn-outline-danger mr-2" data-message="Do you want to delete all agent's registers?" title="Reset All Registers"> @lang('admin/ui.reset_all_registers')
                            </a>
                            @if ($permissions->contains('add_user'))
                                <a href="{{ route('panel.admin.users.create', ['role' => request()->get('role')]) }}"
                                    class="btn btn-sm btn-outline-primary mr-2" title="Add New Users"><i class="fa fa-plus"
                                        aria-hidden="true"></i> @lang('admin/ui.add') </a>
                            @endif
                            <form action="{{ route('panel.admin.users.bulk-action') }}" method="POST" id="bulkAction"
                                class="">
                                @csrf
                                <input type="hidden" name="ids" id="bulk_ids">
                                <div>
                                    <button class="dropdown-toggle p-0 custom-dopdown bulk-btn btn btn-light" type="button"
                                        id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false"><i class="ik ik-more-vertical fa-lg pl-1"></i></button>
                                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                        <a href="javascript:void(0);" class="dropdown-item text-primary fw-700"
                                            data-toggle="modal" data-target="#BulkStoreAgentModal"><i
                                                class="ik ik-upload"></i> Bulk Upload</a>
                                        <hr class="m-1">
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
                                        <hr class="m-1">
                                        <button type="submit" class="dropdown-item bulk-action text-danger fw-700"
                                            data-value="" data-message="You want to delete these Users?"
                                            data-action="delete" data-callback="bulkDeleteCallback"><i class="ik ik-trash">
                                            </i> Bulk Delete
                                        </button>

                                    </ul>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="ajax-container" style="display: none;">
                            @include('panel.admin.users.load')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('panel.admin.users.includes.modal.delegate-access')
    @if (getSetting('user_management_table_filter', @$setting))
        @include('panel.admin.users.includes.filter')
    @endif
    @include('panel.admin.modal.add-user-wallet')
    @include('panel.admin.users.includes.bulk-upload')

@endsection

@push('script')
@include('panel.admin.include.more-action', [
    'actionUrl' => 'admin/users',
    'routeClass' => 'users',
])
    {{-- START SELECT 2 BUTTON INIT --}}
    <script>
        $('.select2').select2();
    </script>
    {{-- END SELECT 2 BUTTON INIT --}}

    {{-- START UPDATE STATUS INIT --}}
    <script>
        // UPDATE USER STATUS USING AJAX
        $(document).on('click', '.statusChanger', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var currentStatus = $('.status' + id).data('status');
            var currentBadgeClass = $('.status' + id).data('class');
            var status = $(this).data('status');
            var badgeClass = $(this).data('class');
            var value = $(this).data('value');
            var url = $(this).data('url');
        })
    </script>
    {{-- END UPDATE STATUS INIT --}}

    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            var response = postData(method, route, 'json', data, null, null);
            if (typeof(response) != "undefined" && response !== null && response.status == "success") {
                console.log(response);
                $('#walletModal').modal('toggle');
                $('.amount').val('');
                $('.transationType').prop('checked', false);
                let id = response.user_id;
                let route = "{{ url('/admin/wallet-logs/user') }}";
                window.location.href = route + '/' + id;
            }
        });
    </script>
    {{-- END AJAX FORM INIT --}}

    {{-- START WALLET LOG INIT --}}
    <script>
        $(document).on('click', '.walletLogButton', function() {
            var user_record = $(this).data('id');
            $('#uuid').val(user_record);
            $('#walletModal').modal('show');
        });
    </script>
    {{-- END WALLET LOG INIT --}}

    {{-- START HTML TO EXCEL INIT --}}
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script>
        function html_table_to_excel(type) {
            var table_core = $("#table").clone();
            var clonedTable = $("#table").clone();
            clonedTable.find('[class*="no-export"]').remove();
            clonedTable.find('[class*="d-none"]').remove();
            clonedTable = tableHeadIconFixer(clonedTable);
            $("#table").html(clonedTable.html());

            // Use in reverse format beacuse we are prepending it.
            var report_format = [{
                    'label': "Status",
                    'value': "{{ request()->get('status') ?? 'All Status' }}"
                },
                {
                    'label': "Date Range",
                    'value': "{{ request()->get('from') ?? 'N/A' }} - {{ request()->get('to') ?? 'N/A' }}"
                },
                {
                    'label': "Report Name",
                    'value': "Users Report"
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

        function tableHeadIconFixer(clonedTable) {

            clonedTable.find('i.icon-head').each(function() {
                var dataTitle = $(this).data('title');
                $(this).replaceWith(dataTitle);
            });

            return clonedTable;

        }
    </script>

    {{-- END HTML TO EXCEL INIT --}}

    {{-- START JS HELPERS INIT --}}

    <script>
        $('#getDataByRole').change(function() {
            if (checkUrlParameter('role')) {
                url = updateURLParam('role', $(this).val());
            } else {
                url = updateURLParam('role', $(this).val());
            }
            fetchData(url);
        });
    </script>
    {{-- END JS HELPERS INIT --}}

    {{-- START RESET BUTTON INIT --}}
    <script>
        $('#reset').click(function() {
            var currentUrl = '{{ url()->full() }}'
            fetchData(currentUrl);
            window.history.pushState("", "", currentUrl);
            $('#TableForm').trigger("reset");
            $(document).find('.close.off-canvas').trigger('click');
        });
    </script>
    {{-- END RESET BUTTON INIT --}}

    {{-- START DELEGATE ACCESS BUTTON INIT --}}
    <script>
        $(document).on('click', '.loginAsBtn', function(e) {
            e.preventDefault();
            let user_id = $(this).data('user_id');
            let first_name = $(this).data('first_name');
            $('.delegateUserId').val(user_id);
            $('.delegateUserName').html(first_name);
            $('#DelegateAccessModel').modal('show');
        });
    </script>
    {{-- END DELEGATE ACCESS BUTTON INIT --}}

    @include('panel.admin.include.bulk-script')
@endpush
