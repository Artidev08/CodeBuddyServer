<form class="forms-sample ajaxForm" action="{{ route('panel.admin.setting.store') }}"method="post"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="group_name" value="{{ 'general_setting' }}">
    <div class="form-group row">
        <label for="seal_signature" class="col-sm-3 col-form-label">@lang('admin/ui.seal_signature') <a
                data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.general_app_logo')"><i
                    class="ik ik-help-circle text-muted ml-1"></i></a>
        </label>
        <div class="col-sm-9">
            <input type="file" name="seal_signature" class="file-upload-default">
            <div class="input-group col-xs-12">
                <input type="text" class="form-control file-upload-info" disabled
                    placeholder="Upload Seal & Signature">
                <span class="input-group-append">
                    <button class="file-upload-browse btn btn-success"
                        type="button">@lang('admin/ui.upload') </button>
                </span>
            </div>
        </div>
        <div class="col-sm-3"> </div>
        <div class="col-sm-9">
            <div class="card m-0 p-2">
                <div class="mx-auto">
                    <img src="{{ asset(getSetting('seal_signature',@$setting)) }}" alt="Invoice term" width="120px">
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <label for="InvoiceTerm" class="col-sm-3 col-form-label">@lang('admin/ui.invoice_term') <span
                class="text-danger">*</span><a data-toggle="tooltip" href="javascript:void(0);"
                title="@lang('admin/tooltip.general_app_url')"><i class="ik ik-help-circle text-muted ml-1"></i></a>
        </label>
        <div class="col-sm-9">
            <input type="text" name="invoice_term" class="form-control" required
                value="{{ getSetting('invoice_term',@$setting) }}" placeholder="invoice Term">
        </div>
    </div>

    <div class="form-group row">
        <label for="InvoicePrefix" class="col-sm-3 col-form-label">@lang('admin/ui.invoice_prefix') <span
                class="text-danger">*</span><a data-toggle="tooltip" href="javascript:void(0);"
                title="@lang('admin/tooltip.general_app_url')"><i class="ik ik-help-circle text-muted ml-1"></i></a>
        </label>
        <div class="col-sm-9">
            <input type="text" name="invoice_prefix" class="form-control" required
                value="{{ getSetting('invoice_prefix',@$setting) }}" placeholder="Invoice Prefix">
        </div>
    </div>
    
    <div class="card-footer text-right">
        <button type="submit" class="btn btn-primary mr-2 ajax-btn">@lang('admin/ui.save_update') </button>
    </div>
</form>
