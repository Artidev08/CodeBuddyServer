@php
    if (!isset($width)) {
        $width = 15;
    }
    $image = asset('user/icons/empty.png');
@endphp

<div class="text-center mx-auto" style="margin-top: 18%">
    <img src="{{ asset($image) }}" alt="" style="width:{{ $width }}%">
    <i class="uil uil-file-slash fs-30"></i>
    <p class="text-muted">{{ $title }}</p>
</div>
