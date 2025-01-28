@extends('layouts.main')
@section('title', $label)
@section('content')
    @php
        /**
         * Agent
         *
         * @category Hq.ai
         *
         * @ref zCURD
         * @author  Defenzelite <hq@defenzelite.com>
         * @license https://www.defenzelite.com Defenzelite Private Limited
         * @version <Hq.ai: 1.1.0>
         * @link    https://www.defenzelite.com
         */
        $breadcrumb_arr = [['name' => 'Add Register', 'url' => route('panel.member.agent-content-registers.index'), 'class' => '']];
    @endphp
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.css') }}">

        <style>
            .error {
                color: red;
            }

            .card {
                margin-bottom: 15px
            }

            textarea.form-control {
                font-size: 20px;
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
                            <h5>{{$label}}</h5>
                            <span>Create a record for {{$label}}</span>
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
                <div class="card mb-0">
                    <div class="card-header d-flex justify-content-between">
                        <h3>Select Criteria for {{$label}}</h3>
                    </div>
                    <form action="{{ route('panel.member.agent-content-registers.store') }}" method="POST" class="ajaxForm">
                        @csrf
                            <input type="hidden" name="agent_id" value="{{@$agent->id}}" id="">
                            

                        <div class="card-body">
                            <div id="ajax-container">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="table" class="table">
                                            <thead>
                                                <tr>
                                                    @if (!isset($print_mode))
                                                        <th class="no-export">
                                                            <input type="checkbox" class="mr-2 " id="selectall" value="" checked>
                                                            @lang('admin/ui.actions')
                                                        </th>
                                                    @endif
                                                    <th class="col_3"> Criteria
                                                    </th> 
                                                    <th class="col_6"> Score</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($combinations) > 0)
                                                    @foreach ($combinations as $combo)
                                                        <tr id="{{ $agent->id }}">
                                                            @if (!isset($print_mode))
                                                                <td class="no-export">
                                                                    <div class="dropdown d-flex">
                                                                        <input type="checkbox" name="selected_combinations[]" value="{{ json_encode($combo) }}" checked>
                                                                    </div>
                                                                </td>
                                                            @endif
                                                            <td class="col_1">
                                                                @foreach ($combo as $key => $value)
                                                                    @php
                                                                        $modelClass = '\\App\\Models\\' . ucfirst($key);
                                                                        $category = $modelClass::where('id', $value)->first('name');
                                                                    @endphp
                                                                    <span class="badge badge-secondary m-1">{{ @$category->name }}</span>
                                                                @endforeach
                                                            </td>
                                                        
                                                            <td class="col_4">{{ count($criteria_payload_keys)}}/{{ count(getCriteriaVariables()) }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td class="text-center" colspan="8">No Data Found...</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="col-md-12 ml-auto">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary floating-btn ajax-btn">
                                    Create Runner </button>
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
    
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
                    url: '{{ route("panel.member.agent-content-registers.update-status") }}', 
                    method: 'POST',
                    data: {
                        status: status,  
                        id: id,
                        value: selectedValue,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        pushNotification(response.message,response.title,response.status);
                    },
                    error: function(xhr, status, error) {
                        pushNotification(error,error,status);
                    }
                });
            }
            $('.ajaxForm').on('submit', function(e) {
                $(".ajax-btn").removeClass("btn-primary"); // Add the "btn-secondary" class (typo fixed)
                $(".ajax-btn").addClass("disabled"); // Add the "disabled" class
                $(".ajax-btn").addClass("btn-secondary"); // Add the "btn-secondary" class (typo fixed)
                $(".ajax-btn").css("cursor", "not-allowed"); // Disable pointer events (removed the semicolon)
                $(".ajax-btn").attr("type", "button"); // Remove the "submit" type
                $(".ajax-btn").html('<i class="fa fa-spinner fa-spin"></i>');
            })
        </script>
        {{-- END RESET BUTTON INIT --}}
    @endpush
@endsection
