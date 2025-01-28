@extends('layouts.main')
@section('title', __('admin/ui.enquiry'))
@section('content')
    @php
        @$breadcrumb_arr = [['name' => __('admin/ui.enquiry'), 'url' => 'javascript:void(0);', 'class' => 'active']];
    @endphp

    @push('head')
        <style>
            .daterangepicker.dropdown-menu.ltr.show-calendar.opensright {
                width: 455px !important;
            }

            .card .card-footer {
                padding-bottom: 0 !important;

            }
        </style>

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
                            <h5>{{ __('admin/ui.enquiry') ?? '--' }}</h5>
                            <span> @lang('admin/ui.list_of') {{ __('admin/ui.enquiry') ?? '--' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb')
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-right">
                        <h3>{{ __('admin/ui.enquiry') ?? '' }}</h3>
                        <div class="d-flex justify-content-right">
                            @if ($permissions->contains('add_user'))
                                <a href="{{ route('panel.admin.website-enquiries.create') }}"
                                    class="btn btn-sm btn-outline-primary mr-2" title="Add Website Enquiry"><i
                                        class="fa fa-plus" aria-hidden="true"></i> @lang('admin/ui.add') </a>
                            @endif
                            @if (getSetting('website_enquiry_bulk_status_update', @$setting) ||
                                    getSetting('website_enquiry_bulk_delete', @$setting) ||
                                    getSetting('website_enquiry_bulk_upload', @$setting))
                                <form action="{{ route('panel.admin.website-enquiries.bulk-action') }}" method="POST"
                                    id="bulkAction">
                                    @csrf

                                    {{-- <x-input name="ids" placeholder="Enter Name" type="" tooltip=""
                                    regex="" validation="" value="" /> --}}
                                    <input type="hidden" name="ids" id="bulk_ids">

                                    <div class="dropdown d-flex justicy-content-left">
                                        <button class="dropdown-toggle p-0 custom-dopdown bulk-btn btn btn-light "
                                            type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false"><i class="ik ik-more-vertical pl-1"></i></button>
                                        <ul class="dropdown-menu dropdown-position multi-level" role="menu"
                                            aria-labelledby="dropdownMenu">
                                            @if (getSetting('website_enquiry_bulk_upload', @$Setting))
                                                <a href="javascript:void(0);" class="dropdown-item text-primary fw-700"
                                                    data-toggle="modal" data-target="#BulkStoreAgentModal"><i
                                                        class="ik ik-upload"></i> @lang('admin/ui.bulk_upload')</a>
                                            @endif
                                            @if (getSetting('website_enquiry_bulk_status_update', @$Setting))
                                                <hr class="m-1">

                                                <a href="javascript:void(0)" class="dropdown-item bulk-action"
                                                    data-value="0" data-status="New" data-column="status"
                                                    data-message="You want to mark these Enquiry as New?"
                                                    data-action="columnUpdate"
                                                    data-callback="bulkColumnUpdateCallback">@lang('admin/ui.mark_as_new')
                                                </a>

                                                <a href="javascript:void(0)" class="dropdown-item bulk-action"
                                                    data-value="1" data-status="Contacted" data-column="status"
                                                    data-message="You want to mark these Enquiry as Contacted?"
                                                    data-action="columnUpdate"
                                                    data-callback="bulkColumnUpdateCallback">@lang('admin/ui.mark_as_contacted')
                                                </a>
                                                <a href="javascript:void(0)" class="dropdown-item bulk-action"
                                                    data-value="2" data-status="Closed" data-column="status"
                                                    data-message="You want to mark these Enquiry as Closed?"
                                                    data-action="columnUpdate"
                                                    data-callback="bulkColumnUpdateCallback">@lang('admin/ui.mark_as_closed')
                                                </a>
                                            @endif
                                            @if (getSetting('website_enquiry_bulk_delete', @$Setting))
                                                <hr class="m-1">
                                                <button type="submit" class="dropdown-item bulk-action text-danger fw-700"
                                                    data-value="" data-message="You want to delete these Enquiry?"
                                                    data-action="delete" data-callback="bulkDeleteCallback"><i
                                                        class="ik ik-trash mr-1"></i> @lang('admin/ui.bulk_delete')
                                                </button>
                                            @endif
                                        </ul>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="ajax-container" style="display: none;">
                            @include('panel.admin.website-enquiries.load')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (getSetting('website_enquiry_table_filter', @$Setting))
        @include('panel.admin.website-enquiries.include.filter')
    @endif

    @if (getSetting('website_enquiry_bulk_upload', @$Setting))
        @include('panel.admin.website-enquiries.include.bulk-upload')
    @endif

@endsection

@push('script')
    @include('panel.admin.include.bulk-script')

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
                    'value': "Website Enquiry Report"
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

        {{-- START RESET BUTTON INIT --}}
        <script>
            $('#reset').click(function() {
                fetchData("{{ route('panel.admin.website-enquiries.index') }}");
                window.history.pushState("", "", "{{ route('panel.admin.website-enquiries.index') }}");
                $('#TableForm').trigger("reset");
                $(document).find('.close.off-canvas').trigger('click');
                $('#status').select2('val', "");
                $('#status').trigger('change');
            });
        </script>
        {{-- END RESET BUTTON INIT --}}
@endpush
