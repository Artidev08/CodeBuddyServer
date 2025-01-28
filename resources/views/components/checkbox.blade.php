@foreach ($arr as $arr_item)
    <input id="{{$arr_item}}" class="py-2 {{ @$class }}" type="{{$type}}" name="{{$name}}" value="{{ is_array($value) ? in_array($arr_item,$value) : 1 }}" @if(is_array($value) ? in_array($arr_item,$value) : $value == 1) checked @endif>
    <x-label name="{{ $arr_item }}" validation="{{ @$validation }}"  tooltip="{{ $tooltip !== null ? $tooltip : '' }}" />
@endforeach

