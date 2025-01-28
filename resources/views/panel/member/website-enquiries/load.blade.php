<div class="table-controller mb-2">
    <div class="d-flex justify-content-between">
        @if (getSetting('website_enquiry_table_record_limit',@$setting))
        <div class="mr-3">
            <label for=""> @lang('admin/ui.show')
                <select name="length" class="length-input" id="length">
                    @foreach (tableLimits() as $limit)
                        <option
                            value="{{ @$limit }}"{{ @$websiteEnquiries->perPage() == @$limit ? 'selected' : '' }}>
                            {{ @$limit ?? '' }}</option>
                    @endforeach
                </select>
                 @lang('admin/ui.entry')
            </label>
        </div>
        @endif
        @if (getSetting('website_enquiry_table_excel_export',@$setting))
        <div>
            <button type="button" id="export_button" class="btn btn-light btn-sm"> @lang('admin/ui.btn_excel') </button>
        </div>
        @endif
    </div>
    <div class="d-flex justify-content-between">
        <div>
            @if (getSetting('website_enquiry_table_search',@$setting))
            <x-input name="search" placeholder="{{ __('admin/ui.left_sidebar_search') }}" type="text" tooltip="" regex="role_name"
            validation="permission_name" value="{{ request()->get('search') }}" />
        @endif
        </div>
        @if (getSetting('website_enquiry_table_filter',@$setting))
        <button type="button" class="off-canvas btn btn-light rounded-0 text-muted btn-icon"><i class="ik ik-filter ik-lg"></i>
        </button>
        @endif
    </div>
</div>
<div class="table-responsive">
    @include('panel.admin.website-enquiries.table')
</div>
<div class="card-footer">
    <div class="row">
        <div class="col-lg-8">
            <div class="pagination mobile-justify-center">
                {{ $websiteEnquiries->appends(request()->except('page'))->links() }}
            </div>
        </div>
        <div class="col-lg-4 mobile-mt-20">
            @if ($websiteEnquiries->lastPage() > 1)
                <label class="d-flex justify-content-end mobile-justify-center" for="">
                    <div class="mr-2 pt-2 ">
                         @lang('admin/ui.jump_to'):
                    </div>
                    <div class="input-group w-50">
                        <input type="number" class="w-25 form-control" id="jumpTo" name="page"
                            value="{{ $websiteEnquiries->currentPage() ?? '' }}">
                        <div class="w-25 bg-gray py-2 pl-2 fw-700">/ {{ $websiteEnquiries->lastPage() }}</div>
                    </div>
                </label>
            @endif
        </div>
    </div>
</div>
