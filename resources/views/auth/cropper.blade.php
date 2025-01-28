@extends('layouts.app')
@push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css">
@endpush
<div class="demo"></div>
<img class="my-image" src="demo/demo-1.jpg" />
@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js"></script>
    <script>
        $('.demo').croppie({
            url: 'demo/demo-1.jpg',
        });
    </script>
    <!-- or even simpler -->
    <script>
        $('.my-image').croppie();
    </script>
@endpush
