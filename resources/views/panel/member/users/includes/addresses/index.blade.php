<div class="table-controller mb-2">
    <div class="d-flex justify-content-between">
        <div class="mr-3">
            <label for="">  @lang('admin/ui.show')
                <select name="length" class="length-input" id="length">
                    @foreach (tableLimits() as $limit)
                        <option value="{{ @$limit }}"{{ @$addresses->perPage() == @$limit ? 'selected' : '' }}>
                            {{ @$limit }}</option>
                    @endforeach
                </select>
                  @lang('admin/ui.entry')
            </label>
        </div>
        <div>
            <button type="button" data-table="#address_table" data-file="Address" id="address_export_button" class="btn btn-light btn-sm">@lang('admin/ui.btn_excel')</button>
        </div>
    </div>
    <div class="d-flex justify-content-between">
        <div>

                <x-input name="search" placeholder="{{ __('admin/ui.left_sidebar_search') }}" type="text" tooltip="" regex=""
                validation="" value="{{ request()->get('search') }}" />
        </div>

    </div>
</div>
<div class="table-responsive table">
    <table id="address_table" class="table p-0">
        <thead>
            <tr>
                <th width="8%">  @lang('admin/ui.sNo') </th>
                <th class="no-export" width="10%">  @lang('admin/ui.actions') </th>
                <th class="no-export" width="10%">  @lang('admin/ui.#') </th>
                <th width="20%">  @lang('admin/ui.type') </th>
                <th width="52%">  @lang('admin/ui.location') </th>
            </tr>
        </thead>
        <tbody class="no-data bg-dark-theme">
            @if (@$addresses->count() > 0)
                @foreach (@$addresses as $address)
                    @php
                        @$address_decoded = @$address->details;
                    @endphp
                    <tr>
                        <td>{{ @$loop->iteration ?? '--' }}</td>

                        <td class="no-export">
                            <div class="dropdown d-flex">
                                <button class="dropdown-toggle btn btn-secondary" type="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    @lang('admin/ui.action')
                                </button>
                                <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">

                                    <li class="dropdown-item p-0"><a href="javascript:void(0)"
                                            class="btn btn-sm edit-btn editAddress fw-400" title=""
                                            data-id="{{ @$address }}" data-original-title="Edit"> <i class="ik ik-edit mr-2"></i> @lang('admin/ui.edit')</a>
                                    </li>

                                    <li class="dropdown-item p-0"><a
                                            href="{{ route('panel.admin.addresses.destroy', @$address->id) }}"
                                            class="btn btn-sm delete-item text-danger fw-700" title=""
                                            data-original-title="delete">  <i class="ik ik-trash mr-2"></i> @lang('admin/ui.delete')</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                        <td>
                            {{ @$address->getPrefix() }}
                        </td>
                        <td>
                            {{ @$address_decoded['type'] == 0 ? 'Home' : 'Office' }}
                        </td>
                        <td>
                            {{ @$address_decoded['name'] ?? '--' }} <br>
                            {{ @$address_decoded['address_1'] ?? '--' }} <br>
                            {{ @$address_decoded['address_2'] ?? '--' }} <br>
                            {{ @$address->city_name . '(' . @$address_decoded['pincode_id'] . ')' }} <br>
                            {{ @$address->state_name . ', ' . @$address->country_name . ',' }}<br>
                            {{ @$address_decoded['country_code'] ?? '--' }} {{ @$address_decoded['phone'] ?? '--' }}
                        </td>

                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="8">@include('panel.admin.include.components.no-data-img')</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<div class="card-footer">
    <div class="row">
        <div class="col-lg-6 mt-2">
            <div class="pagination mobile-justify-center">
                {{ @$addresses->appends(request()->except('page'))->links() }}
            </div>
        </div>
        <div class="col-lg-6 pt-0 mb-4 mobile-mt-20">
            @if (@$addresses->lastPage() > 1)
                <label class="d-flex justify-content-end mobile-justify-center" for="">
                    <div class="mr-2 pt-2 ">
                        @lang('admin/ui.jumpTo') :
                    </div>
                    <div class="input-group w-50">
                        <input type="number" class="w-25 form-control" id="jumpTo" name="page"
                            value="{{ $addresses->currentPage() ?? '' }}">
                        <div class="w-25 bg-gray py-2 pl-2 fw-700">/ {{ $addresses->lastPage() }}</div>
                    </div>
                </label>
            @endif
        </div>
    </div>
</div>


<!-- push external js -->
@push('script')
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    {{-- START HTML TO EXCEL INIT --}}
    <script>
        function html_address_table_to_excel(type) {
            var table_core = $("#address_table").clone();
            var clonedTable = $("#address_table").clone();
            clonedTable.find('[class*="no-export"]').remove();
            clonedTable.find('[class*="d-none"]').remove();
            clonedTable = tableHeadIconFixer(clonedTable);
            $("#address_table").html(clonedTable.html());

            var report_format = [{
                    'label': "Date Range",
                    'value': "{{ request()->get('from') ?? 'N/A' }} - {{ request()->get('to') ?? 'N/A' }}"
                },
                {
                    'label': "Report Name",
                    'value': "User Addresses"
                },
                {
                    'label': "Company",
                    'value': "{{ env('APP_NAME') }}"
                }
            ];

            var report_name = report_format[1]['value'] + " | " + Date.now();
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

            $("#address_table").html(table_core.html());
        }

        $(document).on('click', '#address_export_button', function() {
            html_address_table_to_excel('xlsx');
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


    {{-- START ADDRESS INIT --}}
    <script>
        function getStates(countryId = 101) {
            $.ajax({
                url: "{{ route('world.get-states') }}",
                method: 'GET',
                data: {
                    country_id: countryId
                },
                success: function(res) {
                    $('#state').html(res).css('width', '100%');
                }
            })
        }

        function getCities(stateId = 101) {
            $.ajax({
                url: "{{ route('world.get-cities') }}",
                method: 'GET',
                data: {
                    state_id: stateId
                },
                success: function(res) {
                    $('#city').html(res).css('width', '100%');
                }
            })
        }

        function getEditStates(countryId = 101) {
            $.ajax({
                url: "{{ route('world.get-states') }}",
                method: 'GET',
                data: {
                    country_id: countryId
                },
                success: function(res) {
                    $('#stateEdit').html(res).css('width', '100%');
                }
            })
        }

        function getEditCities(stateId = 101) {
            $.ajax({
                url: "{{ route('world.get-cities') }}",
                method: 'GET',
                data: {
                    state_id: stateId
                },
                success: function(res) {
                    $('#cityEdit').html(res).css('width', '100%');
                }
            })
        }

        // getStates();
        $(document).ready(function() {

            $('#country').on('change', function() {
                getStates($(this).val());
            });

            $('#state').on('change', function() {
                getCities($(this).val());
            });
            $('#countryEdit').on('change', function() {
                getEditStates($(this).val());
            });

            $('#stateEdit').on('change', function() {
                getEditCities($(this).val());
            });
        });


        function getStateAsync(countryId) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '{{ route('world.get-states') }}',
                    method: 'GET',
                    data: {
                        country_id: countryId
                    },
                    success: function(data) {
                        $('#state').html(data);
                        $('.state').html(data);
                        resolve(data)
                    },
                    error: function(error) {
                        reject(error)
                    },
                })
            })
        }

        function getCityAsync(stateId) {
            if (stateId != "") {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: '{{ route('world.get-cities') }}',
                        method: 'GET',
                        data: {
                            state_id: stateId
                        },
                        success: function(data) {
                            $('#city').html(data);
                            $('.city').html(data);
                            resolve(data)
                        },
                        error: function(error) {
                            reject(error)
                        },
                    })
                })
            }
        }
    </script>
@endpush
