<style>
    .select2-container {
        width: 100% !important;
    }
</style>
<div class="side-slide" style="right: -100%;">
    <div class="filter">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mt-3 mb-0"> @lang('admin/ui.filter') </h5>
            <button type="button" class="close off-canvas mt-2 mb-0" data-type="close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="card-body">
            <form action="{{ route('panel.admin.users.index', ['role' => request()->get('role')]) }}" method="GET"
                class="d-flex" id="TableForm">
                <input type="hidden" name="role" value="{{ request()->get('role') }}">
                <div class="row">
                    <div class="col-12 form-group mr-2 align-items-center">
                        <x-label name="is_verified" validation="" tooltip="" />
                        <x-select name="is_verified" value="{{ request()->get('is_verified') }}" label="Status"
                            class="select2" validation="" optionName="label" id="is_verified" :arr="$statuses" />
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Apply @lang('admin/ui.filter') </button>
                        <a href="javascript:void(0);" id="reset" type="button" class="btn btn-light ml-2">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
