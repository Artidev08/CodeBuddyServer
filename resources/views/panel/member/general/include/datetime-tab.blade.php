<form class="forms-sample ajaxForm" action="{{ route('panel.admin.setting.store') }}" method="post"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="group_name" value="{{ 'general_setting_date_time' }}">
    <div class="form-group d-flex">
        <label for="" class="col-sm-3"> @lang('admin/ui.date_format') <span class="text-red">*</span><a
                data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.general_date_format')"><i
                    class="ik ik-help-circle text-muted ml-1"></i></a>
        </label>
        <div class="row">
            @foreach (\App\Models\Setting::DATE_FORMATS as $dt_formats)
             
                <div class="col-sm-12">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="date_format" id="{{@$loop->iteration}}"
                            {{ @$dt_formats['format'] == getSetting('date_format',@$setting) ? 'checked' : '' }}
                            value="{{ @$dt_formats['format'] }}">
                        <label class="form-check-label" for="{{@$loop->iteration}}">{{ $dt_formats['label'] }}
                            ({{ $dt_formats['format'] }})
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card-footer text-right">
        <button type="submit" class="btn btn-primary mr-2 ajax-btn"> @lang('admin/ui.save_update') </button>
    </div>
</form>
