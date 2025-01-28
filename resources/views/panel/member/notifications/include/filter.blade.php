<div class="side-slide" style="right: -100%;">
    <div class="filter">
        <div class="card-header d-flex justify-content-between">
            <h5 class="text-white mt-2">Filter</h5>
            <button type="button" class="close off-canvas text-white" data-type="close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="card-body">
            <form action="{{ route('panel.admin.notifications.index') }}"method="GET" id="TableForm" class="d-flex">
                <div class="row">
                    <div class="form-group col-12">
                        <label for="">From</label>
                        <input type="date" name="from" class="form-control" value="{{ request()->get('from') }}">
                    </div>
                    <div class="form-group col-12">
                        <label for="">To</label>
                        <input type="date" name="to" class="form-control" value="{{ request()->get('to') }}">
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
