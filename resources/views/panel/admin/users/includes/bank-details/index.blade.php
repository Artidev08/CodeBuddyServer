<div class="table-controller mb-2">
    <div class="d-flex justify-content-between">
        <div class="mr-3">
            <label for=""> @lang('admin/ui.show')
                <select name="length" class="length-input" id="length">
                    @foreach (tableLimits() as $limit)
                        <option value="{{ @$limit }}"{{ @$payoutDetails->perPage() == @$limit ? 'selected' : '' }}>
                            {{ @$limit }}</option>
                    @endforeach
                </select>
                @lang('admin/ui.entry')
            </label>
        </div>
        <div>
            <button type="button"data-table="#bank_table" data-file="Bank" id="bank_export_button"
                class="btn btn-light btn-sm">@lang('admin/ui.btn_excel')</button>
        </div>
    </div>
    <div class="d-flex justify-content-between">
        <div>
            <x-input name="search" placeholder="{{ __('admin/ui.left_sidebar_search') }}" type="text" tooltip=""
                regex="" validation="" value="{{ request()->get('search') }}" />

        </div>
    </div>
</div>
<div class="table-responsive">
    <table id="bank_table" class="table">
        <thead>
            <tr>
                <th width="6%"> @lang('admin/ui.sNo')</th>
                <th class="no-export" width="6%">@lang('admin/ui.actions')</th>
                {{-- <th width="6%" class="no-export"> @lang('admin/ui.#')<div class="table-div"> <i class="icon-head"
                            title="Created At"></i>
                        <div class="table-div"><i class="ik ik-arrow-up  asc" data-val="id"></i><i
                                class="ik ik ik-arrow-down desc" data-val="id"></i></div>
                </th> --}}
                <th width="23%"> @lang('admin/ui.bank')</th>
                <th width="25%" title="Account Holder Name">@lang('admin/ui.ahn')</th>
                <th width="12%">@lang('admin/ui.accountNo')</th>
                <th width="10%">@lang('admin/ui.ifscCode')</th>
                <th width="6%">@lang('admin/ui.branch')</th>
                <th width="6%"> @lang('admin/ui.type')</th>
            </tr>
        </thead>

        <tbody class="no-data">
            @if (@$payoutDetails->count() > 0)
                @foreach (@$payoutDetails as $payoutDetail)
                    @php
                        @$paload_decodes = @$payoutDetail->payload;
                    @endphp
                    <tr>

                        <td title="{{ $payoutDetail->getPrefix() }}">{{ @$loop->iteration }}</td>
                        <td class="no-export">
                            <div class="dropdown d-flex">
                                <button class="dropdown-toggle btn btn-secondary" type="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    @lang('admin/ui.action')
                                </button>
                                <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                    <li class="dropdown-item p-0"><a href="javascript:void(0)" data-toggle="modal"
                                            data-target="#editBankDetailsModal"
                                            class="btn btn-sm edit-btn editBankDetailsModal fw-400" title=""
                                            data-payload="{{ json_encode(@$payoutDetail->payload) }}"
                                            data-row="{{ @$payoutDetail }}" data-original-title="Edit"> <i
                                                class="ik ik-edit mr-2"></i> @lang('admin/ui.edit')</a>
                                    </li>

                                    <a href="{{ route('panel.admin.payout-details.destroy', @$payoutDetail->id) }}"
                                        title="Delete Bank" class="dropdown-item  delete-item">
                                        <li class=" p-0 text-danger fw-700"><i class="ik ik-trash mr-2"></i>@lang('admin/ui.delete')</li>
                                    </a>

                                </ul>
                            </div>
                        </td>
                        {{-- @dd($payoutDetail) --}}

                        <td class="col_3">
                            {{ \App\Models\PayoutDetail::BANK_NAMES[@$paload_decodes['bank_name']]['label'] ?? '--' }}
                        </td>

                        <td>{{ Str::limit(@$paload_decodes['account_holder_name'], 12) }}</td>
                        <td>{{ @$paload_decodes['account_no'] }}</td>
                        <td>{{ @$paload_decodes['ifsc_code'] }}</td>
                        <td>{{ @$paload_decodes['branch'] }}</td>
                        <td>{{ @$paload_decodes['type'] }}</td>
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
                {{ @$payoutDetails->appends(request()->except('page'))->links() }}
            </div>
        </div>
        <div class="col-lg-6 pt-0 mb-4 mobile-mt-20">
            @if (@$payoutDetails->lastPage() > 1)
                <label class="d-flex justify-content-end mobile-justify-center" for="">
                    <div class="mr-2 pt-2 ">
                        @lang('admin/ui.jumpTo') :
                    </div>
                    <div class="input-group w-50">
                        <input type="number" class="w-25 form-control" id="jumpTo" name="page"
                            value="{{ $payoutDetails->currentPage() ?? '' }}">
                        <div class="w-25 bg-gray py-2 pl-2 fw-700">/ {{ $payoutDetails->lastPage() }}</div>
                    </div>
                </label>
            @endif
        </div>
    </div>
</div>


@include('panel.admin.users.includes.bank-details.edit')
@include('panel.admin.users.includes.bank-details.create')
<!-- push external js -->
@push('script')
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

    {{-- STATRT ADD EDIT PAYOUTDETAILS INIT --}}
    <script>
        $(document).on('click', '#addPayoutDetailBtn', function() {
            $('#addBankDetailsModal').modal('show');
        });
        $('.editBankDetailsModal').each(function() {
            $(this).click(function() {
                let record = $(this).data('row');
                let payload = $(this).data('payload');
                // alert(record.paylod.type);
                if (record.payload.type == "Saving")
                    $('#editsaving').prop('checked', true);
                else
                    $('#editcurrent').prop('checked', true);
                $('#payoutdetailId').val(record.id);
                $('#editaccount_holder_name').val(payload.account_holder_name);
                $('#editaccount_no').val(payload.account_no);
                $('#editifsc_code').val(payload.ifsc_code);
                $('#editbranch').val(payload.branch);
                $('#editbank option[value="' + payload.bank_name + '"]').prop('selected', true);
                $('#editPayoutDetailReq').modal('show');
            })
        });
    </script>

    <script>
        $('#close-edit-modal').on('click', function() {
            $('.modal-backdrop').remove();
            setTimeout(() => {
                $('body').removeAttr("style");
            }, 1000);
        });
    </script>

    {{-- END ADD EDIT PAYOUTDETAILS INIT --}}

    {{-- START HTML TO EXCEL INIT --}}

    <script>
        function html_bank_table_to_excel(type) {
            var table_core = $("#bank_table").clone();
            var clonedTable = $("#bank_table").clone();
            clonedTable.find('[class*="no-export"]').remove();
            clonedTable.find('[class*="d-none"]').remove();
            $("#bank_table").html(clonedTable.html());

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
                    'value': "User Bank Details"
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

            $("#bank_table").html(table_core.html());
        }

        $(document).on('click', '#bank_export_button', function() {
            html_bank_table_to_excel('xlsx');
        });
    </script>
    {{-- END HTML TO EXCEL INIT --}}
@endpush
