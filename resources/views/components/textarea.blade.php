<textarea class="form-control {{ $class }}" pattern="{{ @$regex['pattern']}}" title="{{ @$regex['message']}}" @isset($validation['pattern']['minlength']) minlength="{{$validation['pattern']['minlength']}}" @endisset
@isset($validation['pattern']['maxlength']) maxlength="{{$validation['pattern']['maxlength']}}" @endisset  rows="{{ $rows }}" cols="{{ $cols }}" name="{{ $name }}" id="{{ $id }}" placeholder="{{ $placeholder }}"
@if(isset($validation['pattern']['mandatory']) && $validation['pattern']['mandatory'] == 'required') {{ $validation['pattern']['mandatory'] }}@endif
>{{ $value }}</textarea>
