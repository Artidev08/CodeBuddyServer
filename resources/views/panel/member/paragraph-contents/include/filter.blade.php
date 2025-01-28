<div class="side-slide" style="right: -100%;">
    <div class="filter">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mt-3 mb-0"> @lang('admin/ui.filter') </h5>
            <button type="button" class="close off-canvas mt-2 mb-0" data-type="close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="card-body">
            <form action="{{ route('panel.admin.paragraph-contents.index') }}" class="d-flex" method="GET"
                id="TableForm">
                <div class="row">
                    <div class="form-group col-12">
                        <x-label name="group" validation="" tooltip="" />
                        <x-select name="group" validation="" id="group" class="select2" value="{{ old('group') }}" label="Group" optionName="label" valueName="label" :arr="\App\Models\ParagraphContent::GROUPS"/>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Apply  @lang('admin/ui.filter') </button>
                        <a href="javascript:void(0);" data-url="{{ route('panel.admin.paragraph-contents.index') }}"
                            id="reset" type="button" class="btn btn-light ml-2">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
