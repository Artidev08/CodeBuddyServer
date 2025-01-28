<div class="form-radio">
    @foreach ($arr as $key => $arr_val)
        <div class="radio radio-inline">
            <label>
                <input type="{{$type}}" name="{{$name}}" value="{{$valueName != null ? $arr_val : $key}}" @if($value == ($valueName != null ? $arr_val : $key)) checked @endif 
                class="{{$class}}">
                <i class="helper"></i>@lang('admin/ui.' . strtolower($arr_val)) 
            </label>
        </div>
    @endforeach
</div>
