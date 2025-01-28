<div class="side-slide" style="right: -100%;">
    <div class="filter">
        <div class="card-header d-flex justify-content-between ">
            <h5 class="mt-3 mb-0">Filter</h5>
            <button type="button" class="close off-canvas mt-2 mb-0" data-type="close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="card-body">
            <form action="{{ route('panel.member.contents.index') }}" method="GET" id="TableForm" class="d-flex">
                <div class="row">
                    <div class="form-group col-12 mb-1">
                        <x-label name="content_category_id" validation="" tooltip="" />
                        <x-select name="content_category" value="{{ request()->get('content_category') }}" label="Category" valueName="id"  optionName="name" class="select2 course-filter"  validation="" id="content_category" :arr="@$contentCategories"/>
                    </div>
                    <div class="form-group col-12 mb-1">
                        <x-label name="occasion_id" validation="" tooltip="" />
                        <x-select name="occasion" value="{{ request()->get('occasion') }}" label="Occasion" optionName="name" valueName="id"  class="select2 course-filter"  validation="" id="occasion" :arr="@$occasions"/>
                    </div>
                    <div class="form-group col-12 mb-1">
                        <x-label name="event_id" validation="" tooltip="" /> 
                        <x-select name="event" value="{{ request()->get('event') }}" label="Event" optionName="name" class="select2 course-filter"  validation="" valueName="id" id="event" :arr="@$events"/>
                    </div>
                    <div class="form-group col-12 mb-1">
                        <x-label name="from" validation="" tooltip="add_from" class="" />
                        <x-input type="date" validation="" value="{{ old('from') }}" name="from" id="from"
                            placeholder="Enter From" class="form-control select2" tooltip="add_from" />
                    </div>
                    <div class="form-group col-12 mb-1">
                        <x-label name="to" validation="" tooltip="add_to" class="" />
                        <x-input type="date" validation="" value="{{ old('to') }}" name="to" id="to"
                            placeholder="Enter To" class="form-control select2" tooltip="add_to" />
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
