<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="description" content="{{ getSetting('seo_meta_description') }}">
<meta name="keywords" content="{{ getSetting('seo_meta_keywords') }}">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" href="{{ getBackendLogo(getSetting('app_favicon')) }}" />

<!-- font awesome library -->
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/font-family/nunito.css') }}" rel="stylesheet">

<script src="{{ asset($root_directory . 'plugins/js/app.js') }}"></script>

<!-- themekit admin template asstes -->
<link rel="stylesheet" href="{{ asset($root_directory . 'css/all.css?v=' . rand(0, 99999)) }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'dist/css/theme.css') }}">
{{-- Font Awesome --}}

<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/fontawesome-6.5.1/fontawesome.css') }}"
    rel="stylesheet">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/icon-kit/dist/css/iconkit.min.css') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/ionicons/dist/css/ionicons.min.css') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/nprogress/nprogress.css') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'css/croppie.min.css') }}">

   {{-- COUNTRYCODE SELECTOR INIT --}}
<link rel="stylesheet" href="{{ asset($root_directory . 'assets/css/intlTelInput.css') }}">
{{-- End COUNTRYCODE SELECTOR INIT --}}

<!-- Stack array for including inline css or head elements -->
<link rel="stylesheet" type="text/css"
    href="{{ asset($root_directory . 'plugins/date-picker/daterangepicker.css') }}" />
@stack('head')

<link rel="stylesheet" type="text/css"
    href="{{ asset($root_directory . 'plugins/jquery-confirm-3.3.2/jquery-confirm.min.css') }}" />

@if (auth()->user()->preferences != null&& isset(auth()->user()->preferences['theme_id']) && auth()->user()->preferences['theme_id'] != null)
    @if (auth()->user()->preferences['theme_id'] == 1)
        <link rel="stylesheet" href="{{ asset($root_directory . 'css/style.css') }}">
    @elseif(auth()->user()->preferences['theme_id'] == 2)
        <link rel="stylesheet" href="{{ asset($root_directory . 'css/dark-theme.css') }}">
    @elseif(auth()->user()->preferences['theme_id'] == 3)
        <link rel="stylesheet" href="{{ asset($root_directory . 'css/primary-theme.css') }}">
    @endif
@else
    <link rel="stylesheet" href="{{ asset($root_directory . 'css/style.css') }}">
@endif

<link rel="stylesheet" href="{{ asset($root_directory . 'css/style.css') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/select2/dist/css/select2.min.css') }}">

<link rel="stylesheet"
    href="{{ asset($root_directory . 'plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/jquery-toast-plugin/dist/jquery.toast.min.css') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'css/shimmer.css') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'css/intlTelInput.css') }}">
