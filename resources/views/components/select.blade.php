<select {{ $isMultiple == 1 ? 'multiple' : '' }} id={{ $id }} class="form-control {{ @$class }}"
    name="{{ @$name }}" @if (isset($validation['pattern']['mandatory']) && $validation['pattern']['mandatory'] == 'required') required @endif>
    <option  value="" readonly>Select {{ $label }}</option>
    @foreach ($arr as $key => $arr_val)
        <option value="{{ $valueName != null ? $arr_val[$valueName] : $key }}"
            @isset($payload) {{ $payload }}="{{ isset($payloadValue) ? (isset($arr_val[$payloadValue]) ? $arr_val[$payloadValue] : $payloadValue) : '' }}" @endisset
            @if ($value == ($valueName != null ? $arr_val[$valueName] : $key)) selected @endif>

            {{ empty(@$optionName) ? @$arr_val : @$arr_val[@$optionName] }}
            @if ($multiarr != null)
                |
                @foreach ($multiarr as $option)
                    {{ $arr_val[$option] }}
                    @if (!$loop->last)
                        |
                    @endif
                @endforeach
            @endif
        </option>
    @endforeach
</select>
