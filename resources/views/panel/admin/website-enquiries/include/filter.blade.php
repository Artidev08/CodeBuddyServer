<div class="side-slide" style="right: -100%;">
    <div class="filter">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mt-3 mb-0">Filter</h5>
            <button type="button" class="close off-canvas mt-2 mb-0" data-type="close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="card-body">
            <form class="d-flex" action="{{ route('panel.admin.website-enquiries.index') }}" method="get"
                id="TableForm">
                <div class="row">
                    <div class="form-group col-12">
                        <x-label name="from_date" validation="" tooltip=""/>
                        <x-date regex="date" max="{{ now()->format('Y-m-d') }}" validation="date" type="date"
                            value="{{ request()->get('from') }}" class="form-control" name="from" id="from" />
                    </div>
                    <div class="form-group col-12">
                        <x-label name="to_date" validation="" tooltip=""/>
                        <x-date regex="date" max="{{ now()->format('Y-m-d') }}" validation="date" type="date"
                            value="{{ request()->get('to') }}" class="form-control" name="to" id="to" />
                    </div>
                    <div class="col-12 form-group">
                        <x-label name="status" validation="" tooltip="" />
                        <x-select name="status" validation="" id="status" class="form-control select2" value="" label="Status" option_name="label" :arr="@$statuses"/>
                    </div>
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Apply Filter</button>
                        <a href="javascript:void(0);" id="reset" type="button" class="btn btn-light ml-2">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
