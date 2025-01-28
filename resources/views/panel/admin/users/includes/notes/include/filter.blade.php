<div class="side-slide" style="right: -100%;">
    <div class="filter">
        <div class="card-header d-flex justify-content-between">
            <h5 class="text-white mt-2"> @lang('admin/ui.filter') </h5>
            <button type="button" class="close off-canvas text-white" data-type="close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="card-body">
            <form action="{{ route('panel.admin.users.include.notes.index') }}"method="GET" id="TableForm"
                class="d-flex">
                <div class="row">
                    <div class="form-group col-12">
                        <x-label name="from_date" tooltip="" validation="" />
                        <x-date regex="date" max="{{ now()->format('Y-m-d') }}" validation="date" type="date"
                            value="{{ request()->get('from') }}" class="form-control" name="from" id="from" />
                    </div>
                    <div class="form-group col-12">
                        <x-label name="to_date" tooltip="" validation="" />
                        <x-date regex="date" max="{{ now()->format('Y-m-d') }}" validation="date" type="date"
                            value="{{ request()->get('to') }}" class="form-control" name="to" id="to" />
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Apply @lang('admin/ui.filter') </button>
                        <a href="javascript:void(0);" id="reset" type="button" class="btn btn-light ml-2">
                            @lang('admin/ui.reset') </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
