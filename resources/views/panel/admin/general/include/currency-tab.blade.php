<form class="forms-sample ajaxForm" action="{{ route('panel.admin.setting.store') }}" method="post"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="group_name" value="{{ 'general_setting_currency' }}">
    <div class="form-group row">
        <label for="exampleInputUsername2" class="col-sm-3 col-form-label">@lang('admin/ui.select_currency') <span
                class="text-red">*</span><a data-toggle="tooltip" href="javascript:void(0);"
                title="@lang('admin/tooltip.general_currency_select')"><i class="ik ik-help-circle text-muted ml-1"></i></a>
        </label>
        <div class="col-sm-9">
            <select required name="app_currency" class="form-control" id="currency">
                @foreach (config('currencies') as $currency)
                    <option value="{{ @$currency['symbol'] }}"
                        {{ @$currency['symbol'] == getSetting('app_currency',@$setting) ? 'selected' : '' }}>
                        {{ @$currency['symbol'] . ' - ' . @$currency['name'] }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group d-flex m-0">
        <label for="thousand_separator" class="col-sm-3 pl-0 col-form-label">@lang('admin/ui.decimals') <span
                class="text-red">*</span><a data-toggle="tooltip" href="javascript:void(0);"
                title="@lang('admin/tooltip.general_currency_no_of_decimal')"><i class="ik ik-help-circle text-muted ml-1"></i></a>
        </label>
        <select required name="no_of_decimal" class="form-control" id="">
            <option value="0" {{ getSetting('no_of_decimal',@$setting) == 0 ? ' selected' : '' }}>1234
            </option>
            <option value="1" {{ getSetting('no_of_decimal',@$setting) == 1 ? ' selected' : '' }}>123.4
            </option>
            <option value="2" {{ getSetting('no_of_decimal',@$setting) == 2 ? ' selected' : '' }}>12.34
            </option>
            <option value="3" {{ getSetting('no_of_decimal',@$setting) == 3 ? ' selected' : '' }}>1.234
            </option>
        </select>
    </div>
    <div class="form-group d-flex m-0">
        <label for="decimal_separator"
            class="col-sm-3 pl-0 col-form-label">@lang('admin/ui.decimal_separator') <a data-toggle="tooltip"
                href="javascript:void(0);" title="@lang('admin/tooltip.general_currency_decimal_separator')"><i
                    class="ik ik-help-circle text-muted ml-1"></i></a>
        </label>
        <div class="radio-toolbar-cus col-sm-9 m-0 pl-1 col-form-label">
            <input type="radio" id="radiodot" name="decimal_separator" value="1"
                @if (getSetting('decimal_separator',@$setting) == 1) checked @endif>
            <label class="fw-700" for="radiodot">DOT (10,000.00)</label>

            <input type="radio" id="radiocomma" name="decimal_separator" value="2"
                @if (getSetting('decimal_separator',@$setting) == 2) checked @endif>
            <label class="fw-700" for="radiocomma">COMMA (10.000,00)</label>
        </div>
    </div>
    <div class="form-group d-flex m-0">
        <label for="currency_positon" class="col-sm-3 pl-0 col-form-label">@lang('admin/ui.currency_position') <a
                data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.general_currency_position')"><i
                    class="ik ik-help-circle text-muted ml-1"></i></a>
        </label>
        <div class="radio-toolbar-cus col-sm-9 m-0 pl-1 col-form-label">
            <input type="radio" id="radioposition1" name="currency_position" value="1"
                @if (getSetting('currency_position',@$setting) == 1) checked @endif>
            <label class="fw-700" for="radioposition1">$1,100.00</label>
            <input type="radio" id="radioposition4" name="currency_position" value="2"
                @if (getSetting('currency_position',@$setting) == 2) checked @endif>
            <label class="fw-700" for="radioposition4">1,100$</label>
        </div>
    </div>
    <div class="card-footer text-right">
        <button type="submit" class="btn btn-primary mr-2 ajax-btn">@lang('admin/ui.save_update') </button>
    </div>
</form>
