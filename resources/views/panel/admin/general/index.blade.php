@extends('layouts.main')
@section('title', @$label)
@section('content')
@php
    $breadcrumb_arr = [
        [
            'name' => @$label,
            'url' => 'javascript:void(0);',
            'class' => '',
            'url' => 'javascript:void(0);',
            'class' => 'active',
        ],
    ];
@endphp

@push('head')
    <link rel="stylesheet" href="{{ asset('panel/admin/plugins/mohithg-switchery/dist/switchery.min.css') }}">
    <link rel="stylesheet" href="{{ asset('panel/admin/plugins/datedropper/datedropper.min.css') }}">

    <style>
        .radio-toolbar-cus {
            margin: 10px;
        }

        .radio-toolbar-cus input[type="radio"] {
            opacity: 0;
            position: fixed;
            width: 0;
        }

        .radio-toolbar-cus label {
            display: inline-block;
            background-color: #ddd;
            margin-top: 0;
            padding: 6px 12px;
            font-family: sans-serif, Arial;
            font-size: 14px;
            border: 2px solid rgb(255, 255, 255);
            border-radius: 4px;
        }

        .radio-toolbar-cus label:hover {
            background-color: rgb(194, 192, 192);
        }

        .radio-toolbar-cus input[type="radio"]:focus+label {
            border: 2px #444;
            background: #444;
        }

        .radio-toolbar-cus input[type="radio"]:checked+label {
            background-color: rgb(64, 153, 255);
            color: #ffffff;
            border: #444;
        }

        .croppie-container .cr-boundary {
            width: 300px;
            height: 300px;
            margin: auto;
            overflow: hidden;
            position: relative;
        }

        .center {
            position: absolute;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
@endpush

<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-grid bg-blue"></i>
                    <div class="d-inline">
                        <h5>  @lang(@$label) </h5>
                        <span>  @lang('admin/ui.website_page_heading') </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @include('panel.admin.include.breadcrumb')
                @include('panel.admin.modal.sitemodal', [
                    'title' => 'How to use',
                    'content' =>
                        'You need to create a unique code and call the unique code with paragraph content helper.',
                ])
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9">
            <div class="card card-484">
                <div role="tabpanel">
                    <div class="card-header" style="border:none;">
                        <ul class="nav nav-tabs" role="tablist">
                            @if (getSetting('general_activation', @$setting))
                                @if ($permissions->contains('access_general_setting'))
                                    <li class="nav-item">

                                        <a href="#general" data-active="general"
                                            class="nav-link active-swicher @if ((request()->has('active') && request()->get('active') == 'general') || !request()->has('active')) active @endif"
                                            aria-controls="general" role="tab"
                                            data-toggle="tab">  @lang('admin/ui.general') </a>
                                    </li>
                                @endif
                            @endif
                            @if (getSetting('currency_activation', @$setting))
                                @if ($permissions->contains('access_currency_setting'))
                                    <li class="nav-item">
                                        <a href="#currency" data-active="currency"
                                            class="nav-link active-swicher @if (request()->has('active') && request()->get('active') == 'currency') active @endif"
                                            aria-controls="currency" role="tab"
                                            data-toggle="tab">  @lang('admin/ui.currency') </a>
                                    </li>
                                @endif
                            @endif
                            @if (getSetting('date_mode_activation', @$setting))
                                @if ($permissions->contains('access_date_time_setting'))
                                    <li class="nav-item">
                                        <a href="#datetime" data-active="datetime"
                                            class="nav-link active-swicher @if (request()->has('active') && request()->get('active') == 'datetime') active @endif"
                                            aria-controls="datetime" role="tab"
                                            data-toggle="tab">  @lang('admin/ui.authentication_mode') </a>
                                    </li>
                                @endif
                            @endif
                            @if (getSetting('notification_verification_activation', @$setting))
                                @if ($permissions->contains('access_notification_setting'))
                                    <li class="nav-item">
                                        <a href="#verification" data-active="verification"
                                            class="nav-link active-swicher @if (request()->has('active') && request()->get('active') == 'verification') active @endif"
                                            aria-controls="verification" role="tab"
                                            data-toggle="tab">  @lang('admin/ui.notification') </a>
                                    </li>
                                @endif
                            @endif

                            @if (getSetting('invoice_activation', @$setting))
                            <li class="nav-item">
                                <a href="#signatureUpdate" data-active="signatureUpdate"
                                    class="nav-link active-swicher @if (request()->has('active') && request()->get('active') == 'signatureUpdate') active @endif"
                                    aria-controls="signatureUpdate" role="tab"
                                    data-toggle="tab">  @lang('admin/ui.invoice_update') </a>
                            </li>
                            @endif
                            @if (getSetting('troubleshoot_activation', @$setting))
                            @if ($permissions->contains('access_troubleshoot_setting'))
                                <li class="nav-item">
                                    <a href="#troubleshoot" data-active="troubleshoot"
                                        class="nav-link active-swicher @if (request()->has('active') && request()->get('active') == 'troubleshoot') active @endif"
                                        aria-controls="troubleshoot" role="tab"
                                        data-toggle="tab">  @lang('admin/ui.trouble_shoot') </a>
                                </li>
                            @endif
                            @endif
                            <li class="nav-item">
                                <a href="#masterPassCode" data-active="masterPasscode"
                                    class="nav-link active-swicher @if (request()->has('active') && request()->get('active') == 'masterPassCode') active @endif"
                                    aria-controls="masterPassCode" role="tab"
                                    data-toggle="tab">{{ __('admin/ui.master_passcode') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body pt-0">
                        <div class="tab-content">
                            @if (getSetting('general_activation', @$setting))
                                @if ($permissions->contains('access_general_setting'))
                                    <div role="tabpanel"
                                        class="tab-pane fade @if ((request()->has('active') && request()->get('active') == 'general') || !request()->has('active')) show active @endif pt-3"
                                        id="general" aria-labelledby="general-tab">
                                        @include('panel.admin.general.include.general-tab')
                                    </div>
                                @endif
                            @endif

                            @if (getSetting('currency_activation', @$setting))
                                @if ($permissions->contains('access_currency_setting'))
                                    <div role="tabpanel"
                                        class="tab-pane fade pt-3 @if (request()->has('active') && request()->get('active') == 'currency') show active @endif"
                                        id="currency" aria-labelledby="currency-tab">
                                        @include('panel.admin.general.include.currency-tab')
                                    </div>
                                @endif
                            @endif
                            @if (getSetting('date_mode_activation', @$setting))
                                @if ($permissions->contains('access_date_time_setting'))
                                    <div role="tabpanel"
                                        class="tab-pane fade pt-3 @if (request()->has('active') && request()->get('active') == 'datetime') show active @endif"
                                        id="datetime" aria-labelledby="datetime-tab">
                                        @include('panel.admin.general.include.datetime-tab')
                                    </div>
                                @endif
                            @endif
                            @if (getSetting('notification_verification_activation', @$setting))
                                @if ($permissions->contains('access_notification_setting'))
                                    <div role="tabpanel"
                                        class="tab-pane fade pt-3 @if (request()->has('active') && request()->get('active') == 'verification') show active @endif"
                                        id="verification" aria-labelledby="verification-tab">
                                        @include('panel.admin.general.include.varification-tab')
                                    </div>
                                @endif
                            @endif
                            @if (getSetting('invoice_activation', @$setting))
                            <div role="tabpanel"
                                class="tab-pane fade pt-3 @if (request()->has('active') && request()->get('active') == 'signature-update') show active @endif"
                                id="signatureUpdate" aria-labelledby="signature-update">
                                @include('panel.admin.general.include.signature-update')
                            </div>
                                @endif
                            @if (getSetting('troubleshoot_activation', @$setting))
                            @if ($permissions->contains('access_troubleshoot_setting'))
                                <div role="tabpanel"
                                    class="tab-pane fade pt-3 @if (request()->has('active') && request()->get('active') == 'troubleshoot') show active @endif"
                                    id="troubleshoot" aria-labelledby="troubleshoot-tab">
                                    @include('panel.admin.general.include.troubleshoot-tab')
                                </div>
                            @endif
                            @endif
                            <div role="tabpanel"
                                    class="tab-pane fade pt-3 @if (request()->has('active') && request()->get('active') == 'masterPassCode') show active @endif"
                                    id="masterPassCode" aria-labelledby="masterPassCode-tab">
                                    @include('panel.admin.general.include.master-pass-code-tab')
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h3>Bulk Setting Assistant</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-primary pb-1 px-1 m-0">
                        <h6 class="fw-600 text-color-black"style="font-size:10px;">
                            Download and Upload an Excel sheet to change multiple settings at once.
                        </h6>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label for="exampleInputUsername2">
                            <span class="mr-0">
                                <a href="javascript:void(0);" class="text-primary fw-700" data-toggle="modal"
                                    data-target="#BulkStoreAgentModal"><i class="ik ik-upload"></i> Bulk Upload</a>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('panel.admin.general.include.bulk-upload')
@include('panel.admin.general.include.logo-upload')

@endsection

@push('script')
    <script src="{{ asset('panel/admin/plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>
    <script src="{{ asset('panel/admin/plugins/datedropper/datedropper.min.js') }}"></script>
    <script src="{{ asset('panel/admin/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/clipboard.js/1.5.12/clipboard.min.js"></script>
    <script src="{{ asset('panel/admin/plugins/datedropper/croppie.min.js') }}"></script>
    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            var response = postData(method, route, 'json', data, null, null);
            if(typeof(response) != "undefined" && response !== null && response.status == "success"){
                window.location.href = redirectUrl;
            }
        })
    </script>
    {{-- END AJAX FORM INIT --}}

    {{-- START JS HELPERS INIT --}}
    <script>
        $('.active-swicher').on('click', function() {
            var active = $(this).attr('data-active');
            updateURL('active', active);
        });
    </script>
    {{-- END JS HELPERS INIT --}}

    {{--START IMAGE UPLOAD CROPPER --}}
    <script>
        const avatar = document.getElementById('app_logo');
        const imagePreview = document.getElementById('logoImagePreview');
        const croppedImageDataInput = document.getElementById('croppedLogoData');
        const croppieLogoContainer = document.querySelector('.demoLogo');

        let croppieLogoInstance = null;

        avatar.onchange = evt => {
            const [file] = avatar.files;
            if (file) {
                imagePreview.src = URL.createObjectURL(file);
                croppieLogoInstance = new Croppie(croppieLogoContainer, {
                    enableExif: true,
                    viewport: {
                        width: 200,
                        height: 200,
                        type: 'square'
                    },
                    boundary: {
                        width: 300,
                        height: 300
                    }
                });

                croppieLogoInstance.bind({
                    url: URL.createObjectURL(file),
                });
            }
        };
        document.querySelector('#updateLogoImageModal').onsubmit = () => {
            if (croppieLogoInstance) {
                croppieLogoInstance.result('base64').then(function(result) {
                    croppedImageDataInput.value = result;
                });
            }
        };
    </script>
    {{--END LOGO UPLOAD CROPPER --}}

    {{--START FAVICON UPLOAD CROPPER --}}
    <script>
        const favicon = document.getElementById('app_favicon');
        const faviconImagePreview = document.getElementById('faviconImagePreview');
        const croppedFaviconDataInput = document.getElementById('croppedFaviconData');
        const croppieFaviconContainer = document.querySelector('.demoFavicon');

        let croppieFaviconInstance = null;

        favicon.onchange = evt => {
            const [file] = favicon.files;
            if (file) {
                faviconImagePreview.src = URL.createObjectURL(file);
                croppieFaviconInstance = new Croppie(croppieFaviconContainer, {
                    enableExif: true,
                    viewport: {
                        width: 200,
                        height: 200,
                        type: 'square'
                    },
                    boundary: {
                        width: 300,
                        height: 300
                    }
                });

                croppieFaviconInstance.bind({
                    url: URL.createObjectURL(file),
                });
            }
        };
        document.querySelector('#updateLogoImageModal').onsubmit = () => {
            if (croppieFaviconInstance) {
                croppieFaviconInstance.result('base64').then(function(result) {
                    croppedFaviconDataInput.value = result;
                });
            }
        };
    </script>
    {{-- END FAVICON UPLOAD CROPPER --}}

@endpush
