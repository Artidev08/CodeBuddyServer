<div id="ajax-container">
<div class="table-controller mb-2">
    <div class="d-flex justify-content-between">
        <div class="mr-3">
            <label for=""> @lang('admin/ui.show')
                <select name="length" class="length-input" id="length">
                    @foreach (tableLimits() as $limit)
                        <option value="{{ @$limit }}"{{ @$contacts->perPage() == @$limit ? 'selected' : '' }}>
                            {{ @$limit }}</option>
                    @endforeach
                </select>
                 @lang('admin/ui.entry')
            </label>
        </div>
        <div>
            <button type="button" id="contact_export_button" class="btn btn-light btn-sm"
                data-table="#contact_table" data-file="Contact"> @lang('admin/ui.btn_excel') </button>
        </div>
    </div>
    <div class="d-flex justify-content-between">
        <div>
            <x-input name="search" placeholder="{{ __('admin/ui.left_sidebar_search') }}" type="text" tooltip="" regex=""
                validation="" value="{{ request()->get('search') }}" />
        </div>

    </div>
</div>
<div class="table-responsive">
    <table id="contact_table" class="table p-0">
        <thead>
            <tr>
                <th width="10%" class="no-export"> @lang('admin/ui.sNo') </th>
                <th width="10%" class="no-export"> @lang('admin/ui.actions') </th>
                <th width="10%" class="no-export">@lang('admin/ui.#') <div class="table-div"> <i class="icon-head" title="Created At"></i><div class="table-div"><i class="ik ik-arrow-up  asc" data-val="id"></i><i
                    class="ik ik ik-arrow-down desc" data-val="id"></i></div></th>
                <th width="25%"> @lang('admin/ui.name') </th>
            </tr>
        </thead>
        <tbody class="no-data">
            @if (@$contacts->count() > 0)
                @foreach (@$contacts as $contact)
                    <tr>
                        <td class="no-export">{{ @$loop->iteration ?? '--' }}</td>

                        <td class="no-export">
                            <div class="dropdown d-flex">
                                <button class="dropdown-toggle btn btn-secondary" type="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    @lang('admin/ui.actions')
                                </button>
                                <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                    <li class="dropdown-item p-0"><a href="javascript:void(0);" title="" data-contact="{{ @$contact->phone }}" class="btn btn-sm edit-btn edit-contact fw-400" > <i class="ik ik-edit"></i> @lang('admin/ui.edit')</a></li>

                                    <li class="dropdown-item p-0"><a href="{{ route('panel.admin.contacts.destroy', $contact->id) }}"  title="Delete Notes" class="btn btn-sm delete-item text-danger fw-700"> <i class="ik ik-trash"></i> @lang('admin/ui.delete')</a></li>
                                </ul>
                            </div>
                        </td>

                        <td class="no-export">{{ @$contact->getPrefix() ?? '--' }}</td>
                        <td>
                            @if (@$contact->gender == 'male')
                                <i class="fa fa-male text-primary"></i>
                            @else
                                <i class="fa fa-female text-danger"></i>
                            @endif
                            {{ @$contact->first_name . ' ' . @$contact->last_name ?? '--' }}
                        </td>
                        <td>{{ @$contact->email ?? '--' }}</td>
                        <td>{{ @$contact->country_code ?? '--' }} {{ @$contact->phone ?? '--' }}</td>
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
                {{ @$contacts->appends(request()->except('page'))->links() }}
            </div>
        </div>
        <div class="col-lg-6 pt-0 mb-4 mobile-mt-20">
            @if (@$contacts->lastPage() > 1)
                <label class="d-flex justify-content-end mobile-justify-center" for="">
                    <div class="mr-2 pt-2 ">
                         @lang('admin/ui.jumpTo') :
                    </div>
                    <div class="input-group w-50">
                        <input type="number" class="w-25 form-control" id="jumpTo" name="page"
                            value="{{ $contacts->currentPage() ?? '' }}">
                        <div class="w-25 bg-gray py-2 pl-2 fw-700">/ {{ $contacts->lastPage() }}</div>
                    </div>
                </label>
            @endif
        </div>
    </div>
</div>
</div>
<!-- push external js -->
@push('script')
    {{-- START HTML TO EXCEL INIT --}}
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

    <script>
        function html_contact_table_to_excel(type) {
            var table_core = $("#contact_table").clone();
            var clonedTable = $("#contact_table").clone();
            clonedTable.find('[class*="no-export"]').remove();
            clonedTable.find('[class*="d-none"]').remove();
            $("#contact_table").html(clonedTable.html());

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
                    'value': "User Contacts"
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
            $("#contact_table").html(table_core.html());
        }

        $(document).on('click', '#contact_export_button', function() {
            html_contact_table_to_excel('xlsx');
        });
    </script>
    {{-- END HTML TO EXCEL INIT --}}
    @include('panel.admin.include.bulk-script')
@endpush
