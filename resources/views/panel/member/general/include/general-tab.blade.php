<style>
    .form-group .col-sm-9 {
        padding-left: 10px; /* Adjust as needed */
    }
</style>

<form class="forms-sample ajaxForm updateLogoImageModal" action="{{ route('panel.admin.setting.store') }}"method="post"
    enctype="multipart/form-data">
    @csrf

    <x-input name="group_name" placeholder="" type="hidden" tooltip="" regex="" validation=""
        value="{{ 'general_setting' }}" />
    <div class="form-group row">
        <label for="exampleInputUsername2" class="col-sm-3 col-form-label"> @lang('admin/ui.app_name') <span
                class="text-red">*</span><a data-toggle="tooltip" href="javascript:void(0);"
                title="@lang('admin/tooltip.general_app_name')"><i class="ik ik-help-circle text-muted ml-1"></i></a>
        </label>
        <div class="col-sm-9">
            <input type="text" pattern="[a-zA-Z]+.*"
                title="Please enter first letter alphabet and at least one alphabet character is required."
                name="app_name" class="form-control" placeholder="App Name" required
                value="{{ getSetting('app_name', @$setting) }}" />
        </div>
    </div>
    <div class="form-group row">
        <label for="exampleInputEmail2" class="col-sm-3 col-form-label"> @lang('admin/ui.app_url') <span
                class="text-danger">*</span><a data-toggle="tooltip" href="javascript:void(0);"
                title=" @lang('admin/tooltip.general_app_url') }}"><i class="ik ik-help-circle text-muted ml-1"></i></a>
        </label>
        <div class="col-sm-9">
            <input type="url" name="app_url" class="form-control" required
                value="{{ getSetting('app_url', @$setting) }}" placeholder="App Url">
        </div>
    </div>
    <div class="form-group row">
        <label for="logo" class="col-sm-3 col-form-label"> @lang('admin/ui.app_logo') <a data-toggle="tooltip"
                href="javascript:void(0);" title="@lang('admin/tooltip.general_app_logo')"><i
                    class="ik ik-help-circle text-muted ml-1"></i></a>
        </label>

        <div class="col-sm-9">
            <input type="file" name="app_logo" id="app_logo" accept="image/jpg,image/png,image/jpeg"
                class="file-upload-default cropAppLogo">
            <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Logo">
                <span class="input-group-append">
                    <button class="file-upload-browse btn btn-success" type="button"> @lang('admin/ui.upload') </button>
                </span>
            </div>
            <div>
                <img id="logoImagePreview" class="d-none" src="#" alt="your image" />
                <div class="demoLogo"></div>
                <x-input name="croppedLogoData" id="croppedLogoData" placeholder="" type="hidden" tooltip=""
                    regex="" validation="" value="" />
            </div>

        </div>
        <div class="col-sm-3"> </div>
        <div class="col-sm-9">
            <div class="card m-0 p-2">
                <div class="mx-auto">
                    <img src="{{ asset(getSetting('app_logo', @$setting)) }}" alt="App Logo" width="120px">
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="logo" class="col-sm-3 col-form-label"> @lang('admin/ui.app_favicon') <a data-toggle="tooltip"
                href="javascript:void(0);" title="@lang('admin/tooltip.general_app_favicon')"><i
                    class="ik ik-help-circle text-muted ml-1"></i></a>
        </label>
        <div class="col-sm-9">
            <input type="file" name="app_favicon" id="app_favicon"class="file-upload-default">
            <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled placeholder="Upload Favicon">
                <span class="input-group-append">
                    <button class="file-upload-browse btn btn-success" type="button"> @lang('admin/ui.upload') </button>
                </span>
            </div>
            <div>
                <img id="faviconImagePreview" class="d-none" src="#" alt="your image" />
                <div class="demoFavicon"></div>

                <x-input name="croppedLogoData" id="croppedFaviconData" placeholder="" type="hidden"
                    tooltip="" regex="" validation="" value="" />

            </div>
        </div>
        <div class="col-sm-3"></div>
        <div class="col-sm-9">
            <div class="card m-0 p-2">
                <div class="mx-auto">
                    <img src="{{ asset(getSetting('app_favicon', @$setting)) }}" alt="Favicon" width="40px">
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <label for="exampleInputUsername2" class="col-sm-3 col-form-label"> @lang('admin/ui.gpt_api_key') <span
                class="text-red">*</span><a data-toggle="tooltip" href="javascript:void(0);"
                title="@lang('admin/tooltip.general_gpt_api_key')"><i class="ik ik-help-circle text-muted ml-1"></i></a>
        </label>
        <div class="col-sm-9">
            <input type="text" pattern="[a-zA-Z]+.*"
                title="Please enter first letter alphabet and at least one alphabet character is required."
                name="gpt_api_key" class="form-control" placeholder="App Name" required
                value="{{ getSetting('gpt_api_key', @$setting) }}" />
        </div>
    </div>
    <div class="card-footer text-right">
        <button type="submit" class="btn btn-primary mr-2 ajax-btn">@lang('admin/ui.save_update') </button>
    </div>
</form>
