@extends('layouts.main')
@section('title', $title)
@section('content')
    @php
        /**
         * Item
         *
         * @category Hq.ai
         *
         * @ref zCURD
         * @author  Defenzelite <hq@defenzelite.com>
         * @license https://www.defenzelite.com Defenzelite Private Limited
         * @version <Hq.ai: 1.1.0>
         * @link    https://www.defenzelite.com
         */
        //    return  $category =App\Models\Category::where('category_id',request()->get('category'))->first();
        $breadcrumb_arr = [['name' => Str::limit($title, 30), 'url' => 'javascript:void(0);', 'class' => 'active']];
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
        {{-- <div class="page-header">
            <div class="row align-content-base-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ $title }}</h5>
                            <span>List of {{ $title }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb')
                </div>
            </div>
        </div> --}}
        <div class="row">
            <!-- start message area-->
            <!-- end message area-->
            <div class="col-md-12">
                <div class="card bg-black">
                    <div class="card-header d-flex justify-content-between pb-0">
                        <h3>{{ $title }}</h3>
                        <div class="d-flex justify-content-right">
                            <input type="text" name="search" class="form-control mr-2 search-box" placeholder="Search"
                            id="search" value="{{ request()->get('search') }}">
                            @if (auth()->user()->isAbleTo('add_item'))
                                <a href="{{ route($route . '.create') }}?agent_id={{ @$agentRecord->id }}"
                                    class="btn btn-sm btn-secondary mr-2" title="Add"><i class="fa fa-plus"
                                        aria-hidden="true"></i> Add </a>
                            @endif
                            <form action="{{ route($route . '.bulk-action') }}" method="POST" id="bulkAction" class="">
                                @csrf
                                <input type="hidden" name="ids" id="bulk_ids">
                                <div>
                                    <button class="dropdown-toggle p-0 custom-dopdown bulk-btn btn btn-light "
                                        type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false"><i class="ik ik-more-vertical pl-1"></i></button>
                                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                        <button type="submit" class="dropdown-item bulk-action text-danger fw-700"
                                            data-value="" data-message="You want to delete these content-base?"
                                            data-action="delete" data-callback="bulkDeleteCallback"> <i
                                                class="ik ik-trash mr-2"> </i> Bulk Delete
                                        </button>
                                    </ul>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="ajax-container">
                        @include($view . '.load')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include($view . '.include.filter')
    <!-- push external js -->
    @push('script')
        @include('panel.admin.include.bulk-script')
        {{-- START HTML TO EXCEL BUTTON INIT --}}
        <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

        <script>
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
                fetchData("{{ route($route . '.index') }}");
                window.history.pushState("", "", "{{ route($route . '.index') }}");
                $('#TableForm').trigger("reset");
                $(document).find('.close.off-canvas').trigger('click');
            });
        </script>
        {{-- END RESET BUTTON INIT --}}
    @endpush
@endsection
