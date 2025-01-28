<title> {{ @$meta_title ?? getSetting('seo_meta_title') }} | {{ getSetting('app_name') }} </title>

<!-- Metas -->
<meta charset="utf-8">
<meta name="description" content="{{ @$meta_description ?? getSetting('seo_meta_description') }}">
<meta name="keywords" content="{{ @$meta_keywords }}">
<meta name='subject' content='{{ @$meta_motto }}'>
<meta name='copyright' content='{{ env('APP_NAME') }}'>
<meta name='language' content='IN'>
<meta name='robots' content='index,follow'>
<meta name='abstract' content='@isset($meta_abstract){{ $meta_abstract }}@endisset'>
<meta name='topic' content='Business'>
<meta name='summary' content='{{ @$meta_motto }}'>
<meta name='Classification' content='Business'>
{{-- <meta name='author' content='@isset($meta_author_name){{ $meta_author_email }}@endisset'> --}}
<meta name='designer' content='Defenzelite'>
<meta name='reply-to' content='@isset($meta_author_name){{ $meta_author_name }}@endisset'>
<meta name='owner' content='@isset($meta_reply_to){{ $meta_reply_to }}@endisset'>
<meta name='url' content='{{ url()->current() }}'>

<meta name="og:title" content="{{ @$meta_title }}" />
<meta name="og:type" content="{{ @$meta_motto }}" />
<meta name="og:url" content="{{ url()->current() }}" />
<meta name="og:image" content="@isset($meta_img){{ $meta_img }}@endisset" />
<meta name="og:site_name" content="{{ env('APP_NAME') }}" />
<meta name="og:description" content="{{ @$meta_description ?? getSetting('seo_meta_description') }}" />

<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<link rel="icon" href="{{ getBackendLogo(getSetting('app_favicon')) }}" />

<!-- CSS -->
<link rel="stylesheet" href="{{ asset('site/assets/css/plugins.css') }}">
<link rel="stylesheet" href="{{ asset('site/assets/css/intlTelInput.css') }}">
<link rel="stylesheet" href="{{ asset('site/assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('site/assets/css/colors/navy.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- font family --}}
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">

{{-- Dynamic CSS Before Head --}}
@if (getSetting('custom_header_style') != 0)
    <link rel="stylesheet" href="{!! getSetting('custom_header_style') !!}" />
@endif

<style>
    .alert {
        position: relative;
        padding: 0.75rem 1.7rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 0.3125rem;
        font-weight: 500;
    }

    .alert-dismissible {
        padding-right: 4rem;
    }

    .alert-dismissible .close {
        position: absolute;
        top: 0;
        right: 0;
        padding: 0.75rem 1.25rem;
        color: inherit;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
</style>

