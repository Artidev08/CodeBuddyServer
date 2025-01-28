<form class="forms-sample ajaxForm" action="{{ route('panel.admin.setting.store') }}" method="post"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="group_name" value="{{ 'general_setting_verification' }}">
    <div class="form-group row">
        <label for="exampleInputUsername2" class="col-sm-9 col-form-label">
             @lang('admin/ui.email_notification') <a data-toggle="tooltip" href="javascript:void(0);"
                title="@lang('admin/tooltip.email_notification')"><i class="ik ik-help-circle text-muted ml-1"></i></a>
            <br>
        </label>
        <div class="col-sm-3">
            <input class="js-switch switch-input" @if (getSetting('email_notify',@$setting) == '1') checked @endif name="email_notify"
                type="checkbox" id="email_notify" value="1">
        </div>
    </div>
    <div class="form-group row">
        <label for="exampleInputUsername2" class="col-sm-9 col-form-label"> @lang('admin/ui.sms_notification') <a
                data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.sms_notification')"><i
                    class="ik ik-help-circle text-muted ml-1"></i></a>
            <br>
        </label>
        <div class="col-sm-3">
            <input class="js-switch switch-input" @if (getSetting('sms_notify',@$setting) == '1') checked @endif name="sms_notify"
                type="checkbox" id="sms_notify" value="1">
        </div>
    </div>
    <div class="form-group row">
        <label for="exampleInputUsername2" class="col-sm-9 col-form-label"> @lang('admin/ui.site_notification') <a
                data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.on_site_notifications')"><i
                    class="ik ik-help-circle text-muted ml-1"></i></a>
            <br>
        </label>
        <div class="col-sm-3">
            <input class="js-switch switch-input" @if (getSetting('notification',@$setting) == '1') checked @endif name="notification"
                type="checkbox" id="notification" value="1">
        </div>
    </div>

    <hr>

    <div class="form-group row">
        <label for="exampleInputUsername2" class="col-sm-9 col-form-label"> @lang('admin/ui.email_verification') <a
                data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.email_verification')"><i
                    class="ik ik-help-circle text-muted ml-1"></i></a>
            <br>
        </label>
        <div class="col-sm-3">
            <input class="js-switch switch-input" @if (getSetting('email_verify',@$setting) == '1') checked @endif name="email_verify"
                type="checkbox" id="email_verify" value="1">
        </div>
    </div>
    <div class="form-group row">
        <label for="exampleInputUsername2" class="col-sm-9 col-form-label"> @lang('admin/ui.sms_verification') <a
                data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.sms_verification')"><i
                    class="ik ik-help-circle text-muted ml-1"></i></a>
            <br>
        </label>
        <div class="col-sm-3">
            <input class="js-switch switch-input" @if (getSetting('sms_verify',@$setting) == '1') checked @endif name="sms_verify"
                type="checkbox" id="sms_verify" value="1">
        </div>
    </div>

    <div class="card-footer text-right">
        <button type="submit" class="btn btn-primary mr-2"> @lang('admin/ui.save_update') </button>
    </div>
</form>
