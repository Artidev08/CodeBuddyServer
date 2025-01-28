<div class="side-slide" style="right: -100%;">
    <div class="filter">
        <div class="card-header d-flex justify-content-between ">
            <h5 class="mt-3 mb-0"> @lang('admin/ui.filter') </h5>
            <button type="button" class="close off-canvas mt-2 mb-0" data-type="close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="card-body">
            <form action="{{ route('panel.admin.faqs.index') }}" method="GET" id="TableForm" class="d-flex">
                <div class="row">
                    <div class="col-12 form-group">
                        <x-label name="category" validation="category_select" tooltip="" />
                        <x-select name="category_id" value="{{ @$category->id }}" label="All Category"
                            optionName="name" valueName="id" class="select2" :arr="@$categories" validation="category_select"
                            id="category_id" />
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Apply  @lang('admin/ui.filter') </button>
                        <a href="javascript:void(0);" id="reset" type="button"
                            class="btn btn-light ml-2"> @lang('admin/ui.reset') </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
