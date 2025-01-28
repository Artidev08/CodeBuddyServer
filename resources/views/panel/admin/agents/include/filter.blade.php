<div class="side-slide bg-dark" style="right: -100%;">
    <div class="filter bg-dark">
        <div class="card-header d-flex justify-content-between filterDark">
            <h5 class="text-white mt-2">Filter</h5>
            <button type="button" class="close off-canvas text-white" data-type="close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="card-body">
            <form action="{{ route('panel.admin.agents.index') }}"method="GET" id="TableForm" class="d-flex">
                <div class="row">
                    <div class="col-12 form-group">
                        <label for="">Status</label>
                        <select name="status" id="status" class="form-control select2">
                            <option value="" readonly>Select Status </option>
                            @foreach (App\Models\Agent::STATUSES as $key => $status)
                                <option value="{{ $key }}"@if (request()->has('status') && request()->get('status') == $key) selected @endif>
                                    {{ $status['label'] ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 form-group">
                        <label for="">Model</label>
                        <select name="model_id" id="model_id" class="form-control select2">
                            <option value="" readonly>Select Status </option>
                            @foreach (getCategoriesByCode('ModelCategories') as $model)
                                <option value="{{ $model->id }}"
                                    {{ request('model_id') == $model->id ? 'Selected' : '' }}>
                                    {{ $model->name ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 form-group">
                        <label for="">Classifier</label>
                        <select name="classifier" id="classifier" class="form-control select2">
                            <option value="" readonly>Select Classifier </option>
                            @foreach (App\Models\Agent::CLASSIFIER as $key => $classifier)
                                <option value="{{ $key }}"@if (request()->has('classifier') && request()->get('classifier') == $key) selected @endif>
                                    {{ $classifier['label'] ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="department_id">Department </label>
                            <a data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.add_item_department')"><i
                                    class="ik ik-help-circle text-muted ml-1"></i></a>
                            <select name="department_id" id="department_id" data-flag="0"
                                class="form-control select2 department_id">
                                <option value="" readonly>Select Department </option>
                                @foreach (getCategoriesByCode('DepartmentCategories') as $department)
                                    <option value="{{ $department->id }}"
                                        {{ request('department_id') == $department->id ? 'Selected' : '' }}>
                                        {{ $department->name ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="designation_id">Designation </label>
                            <a data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.add_item_designation')"><i
                                    class="ik ik-help-circle text-muted ml-1"></i></a>
                            <select name="designation_id" id="designation_id" data-flag="0"
                                class="form-control select2 designation_id">
                                <option value="" readonly>Select Designation </option>

                            </select>
                        </div>
                    </div>
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
                        <a href="javascript:void(0);" id="reset" type="button"
                            class="btn btn-light ml-2 text-dark">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
