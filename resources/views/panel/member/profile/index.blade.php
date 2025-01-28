@extends('layouts.main')
@section('title', __('admin/ui.profile'))
@section('content')
    @push('head')
        <link rel="stylesheet" href="{{ asset('panel/admin/plugins/select2/dist/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('panel/admin/plugins/datedropper/datedropper.min.css') }}">

    @endpush

    @push('style')
        <style>
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
            .iti--inline-dropdown .iti__dropdown-content {
                z-index: 9 !important;
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-file-text bg-blue"></i>
                        <div class="d-inline">
                            <h5> @lang('admin/ui.profile') </h5>
                            <span> @lang('admin/ui.update_profile') </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('panel.admin.dashboard.index') }}"><i class="ik ik-home"></i></a>
                            </li>

                            <li class="breadcrumb-item" aria-current="page"> @lang('admin/ui.Profile') </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            @include('panel.admin.include.message')
            <div class="col-lg-4 col-md-5">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <div style="width: 150px; height: 150px; position: relative" class="mx-auto">
                                <img src="{{ $user && $user->avatar ? $user->avatar : asset('panel/admin/default/default-avatar.png') }}"
                                    class="rounded-circle" width="150"
                                    style="object-fit: cover; width: 150px; height: 150px" />
                                <button class="btn btn-dark rounded-circle position-absolute"
                                    style="width: 30px; height: 30px; padding: 8px; line-height: 1; top: 0; right: 0"
                                    data-toggle="modal" data-target="#updateProfileImageModal"><i
                                        class="ik ik-camera"></i></button>
                            </div>
                            <h5 class="mb-0 mt-3">
                                {{ Str::limit($user->full_name, 20) }}
                                @if ($user->is_verified == 1)
                                    <strong class="mr-1"><i class="ik ik-check-circle"></i></strong>
                                @endif
                            </h5>
                            {{-- <span class="text-muted" title="Role Name">{{ $user->role_name }}</span>
                            @if (getSetting('wallet_activation') == 1)
                                <div class=" mt-2">
                                    <a class="btn btn-outline-light text-dark border"
                                        href="@if (auth()->user()->isAbleTo('control_wallet')) {{ route('panel.admin.wallet-logs.index', $user->id) }} @endif">
                                        <i class="fa fa-wallet pr-1"></i>@lang('admin/ui.wallet_balance')
                                        {{ $user->wallet ?? '0.0' }}
                                    </a>
                                </div>
                            @endif --}}
                        </div>
                    </div>
                    <hr class="mb-0">
                    <div class="card-body">
                        <small class="text-muted d-block"> @lang('admin/ui.email_address') </small>
                        <div class="d-flex justify-content-between">
                            <h6 style="overflow-wrap: anywhere;"><span><i class="ik ik-mail mr-1"></i><a class="text-color-white"
                                        href="mailto:{{ $user->email ?? '' }}"
                                        id="copyemail">{{ $user->email ?? '' }}</a></span></h6>
                            <span class="text-copy" title="Copy" data-clipboard-target="#copyemail">
                                <i class="ik ik-copy"></i>
                            </span>
                        </div>
                        <small class="text-muted d-block pt-10"> @lang('admin/ui.PhoneNumber') </small>
                        <div class="d-flex justify-content-between">
                            <h6><span><a class="text-color-white" href="tel:{{ $user->country_code ?? '' }} {{ $user->phone ?? '' }}" id="copyphone"><i
                                            class="ik ik-phone mr-1"></i>+{{ $user->country_code ?? '' }} {{ $user->phone ?? '' }}</a></span>
                            </h6>
                            <span class="text-copy" title="Copy" data-clipboard-target="#copyphone" tile>
                                <i class="ik ik-copy"></i>
                            </span>
                        </div>
                        <small class="text-muted d-block pt-10"> @lang('admin/ui.MemberSince') </small>
                        <h6>{{ $user->formatted_created_at ?? '' }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-7">
                <div class="card">
                    <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a data-active="setting"
                                class="nav-link active-swicher @if ((request()->has('active') && request()->get('active') == 'setting') || !request()->has('active')) active @endif"
                                data-type="setting" id="pills-setting-tab" data-toggle="pill" href="#previous-month"
                                role="tab" aria-controls="pills-setting" aria-selected="false"> @lang('admin/ui.Setting') </a>
                        </li>
                        <li class="nav-item">
                            <a data-active="account"
                                class="nav-link active-swicher @if (request()->has('active') && request()->get('active') == 'account') active @endif"
                                data-type="account" id="pills-timeline-tab" data-toggle="pill" href="#current-month"
                                role="tab" aria-controls="pills-timeline" aria-selected="true"> @lang('admin/ui.change_password') </a>
                        </li>
                        @if (auth()->user()->isAbleTo('control_mfa_user'))
                            @if (getSetting('mfa_activation') == 1)
                                <li class="nav-item">
                                    <a data-active="security"
                                        class="nav-link active-swicher @if (request()->has('active') && request()->get('active') == 'security') active @endif"
                                        data-type="security" id="pills-timeline-tab" data-toggle="pill" href="#security"
                                        role="tab" aria-controls="pills-timeline" aria-selected="true">
                                        @lang('admin/ui.mfa') </a>
                                </li>
                            @endif
                        @endif

                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade @if ((request()->has('active') && request()->get('active') == 'setting') || !request()->has('active')) show active @endif"
                            id="previous-month" role="tabpanel" aria-labelledby="pills-setting-tab">
                            <div class="card-body">
                                <form action="{{ route('panel.admin.profile.update', $user->id) }}" method="POST"
                                    class="form-horizontal">
                                    @csrf
                                    <x-input name="request_with" placeholder="" type="hidden" tooltip=""
                                    regex="" validation="" value="profile" />
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <x-label name="first_name" validation="common_name" tooltip="" />
                                                <x-input name="first_name" id="first_name"
                                                    placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.first_name') }}"
                                                    type="text" tooltip="add_user_first_name" regex="name"
                                                    validation="common_name" value="{{ @$user->first_name }}" />
                                                <x-message name="first_name" :message="@$message" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">


                                                <x-label name="last_name" validation="common_name" tooltip="" />
                                                <x-input name="last_name" id="last_name"
                                                    placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.last_name') }}"
                                                    type="text" tooltip="" regex="name"
                                                    validation="common_name" value="{{ $user->last_name }}" />
                                                <x-message name="last_name" :message="@$message" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">

                                                <x-label name="email" validation="common_email" tooltip="" />
                                                <x-input name="email" id="email"
                                                    placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.email') }}"
                                                    type="email" tooltip="" regex="email"
                                                    validation="common_email" value="{{ $user->email }}" />
                                                <x-message name="email" :message="@$message" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <x-label name="contact_number" validation="common_phone_number"
                                                    tooltip="" />
                                                {{-- <x-input name="phone"
                                                    placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.phone_number') }}"
                                                    id="phone" type="number" tooltip="" regex="phone_number"
                                                    validation="common_phone_number" value="{{ $user->phone }}" />
                                                <x-message name="phone" :message="@$message" /> --}}
                                                <div class="input-group">
                                                    <input type="hidden" id="countryCodeInput" name="country_code" value="">
                                                        <input type="tel"  class="form-control"
                                                        id="phone" name="phone" value="{{ $user->fullPhone() }}" >
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="gender"> @lang('admin/ui.gender') <span class="text-red">*</span>
                                                </label>
                                                <div class="form-radio">
                                                    <div class="radio radio-inline">
                                                        <label>
                                                            <input type="radio" name="gender" value="Male"
                                                                @checked(old('gender', $user->gender == 'Male'))>
                                                            <i class="helper"></i> @lang('admin/ui.Male')
                                                        </label>
                                                    </div>
                                                    <div class="radio radio-inline">
                                                        <label>
                                                            <input type="radio" name="gender" value="Female"
                                                                @checked(old('gender', $user->gender == 'Female'))>
                                                            <i class="helper"></i> @lang('admin/ui.Female')
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                        @php
                                            $now = now()->format('Y-m-d');
                                        @endphp
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <x-label name="dob" validation="admin_dob" tooltip="" />
                                                <x-date regex="dob" :max="$now" validation="admin_dob"
                                                    type="date" value="{{ $user->dob }}" name="dob"
                                                    id="dob" placeholder="Select your date" />
                                                <x-message name="dob" :message="@$message" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <x-label name="time_zone" validation="lead_status" tooltip="" />
                                                <x-select name="timezone" value="{{ $user->timezone }}" label="Status"
                                                    optionName="" class="select2" :arr="@$timezones" validation=""
                                                    id="timezone" />

                                            </div>
                                        </div>
                                        {{-- Select Language --}}
                                        @php
                                            $languages = App\Models\User::LANGUAGE;
                                        @endphp

                                        <div class="col-md-4">
                                            <div class="form-group">

                                                <x-label name="language" validation="blog_type" tooltip="" />
                                                <x-select name="language"   value="{{ $user->preferences['language'] ?? 'en' }}"
                                                    label="Type" optionName="label" valueName="" class="select2"
                                                    validation="blog_type" id="language" :arr="@$languages" />
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">

                                                <x-label name="bio" validation="" tooltip="" />
                                                <x-textarea regex="" validation="" value="{{ $user->bio }}"
                                                    name="bio" id=""
                                                    placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.bio') }}"
                                                    rows="2" />
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">@lang('admin/ui.update')</button>
                                </form>
                            </div>
                        </div>

                        <div class="tab-pane fade @if (request()->has('active') && request()->get('active') == 'account') show active @endif" id="current-month"
                            role="tabpanel" aria-labelledby="pills-timeline-tab">
                            <div class="card-body">
                                <form class="row"
                                    action="{{ route('panel.admin.profile.update.password', $user->id) }}"
                                    method="POST">
                                    @csrf
                                    <x-input name="request_with" placeholder="" type="hidden" tooltip=""
                                        regex="" validation="" value="password" />
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="form-group col-md-6">

                                                <x-label name="new_password" validation="" tooltip="" />
                                                <x-input name="password" id="password"
                                                    placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.password') }}"
                                                    type="password" tooltip="" regex="" validation=""
                                                    value="" />
                                                <x-message name="Password" :message="@$message" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">

                                        <x-label name="confirm_password" validation="" tooltip="" />
                                        <x-input name="confirm_password" id="confirm_password"
                                            placeholder="{{ __('admin/ui.confirm_password') }}" type="password"
                                            tooltip="" regex="" validation="" value="" />
                                        <x-message name="Password" :message="@$message" />
                                    </div>


                                    <div class="col-md-12">
                                        <button class="btn btn-primary" type="submit">@lang('admin/ui.update')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @if (getSetting('mfa_activation') == 1)
                            <div class="tab-pane fade @if (request()->has('active') && request()->get('active') == 'security') show active @endif"
                                id="security" role="tabpanel" aria-labelledby="pills-security-tab">
                                <form action="{{ route('mfa-store') }}" method="post">
                                    @csrf
                                    <x-input name="secret_key" placeholder="" type="hidden" tooltip=""
                                    regex="" validation="" value="{{ $secret }}" />
                                    @if (auth()->user()->google2fa_secret == null)
                                        <div class="card-body text-center">
                                            <h6 class="fw-700 mb-0">Setup MFA</h6>
                                            <div>
                                                {!! $QR_Image !!}
                                                <hr>

                                            </div>
                                            <div class="text-center text-muted w-75 mx-auto mb-4">
                                                Set up your two factor authentication by scanning the barcode below.
                                                <br>
                                                Use <b><a href="https://safety.google/authentication/">Google
                                                        Authenticator</a></b> app for continuing.
                                            </div>

                                            <button class="btn btn-primary">I've Scanned QR</button>
                                        </div>
                                    @else
                                        <div class="card-body text-center">
                                            <h6 class="fw-700 mb-0">Two-Factor Authentication</h6>
                                            <p class="text-muted mb-4">Two-factor authentication is currently
                                                enabled.</p>
                                            <a href="{{ route('mfa-enabled') }}" class="btn btn-danger">Scan again</a>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('panel/admin/profile/include/profile-modal')

@endsection

@push('script')
    <script src="{{ asset('panel/admin/plugins/datedropper/datedropper.min.js') }}"></script>

    <script src="{{ asset('panel/admin/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/clipboard.js/1.5.12/clipboard.min.js"></script>
    <script src="{{ asset('panel/admin/plugins/datedropper/croppie.min.js') }}"></script>

     {{-- COUNTRYCODE SELECTOR INIT --}}
     <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.querySelector("#phone");
            const countryCodeInput = document.querySelector("#countryCodeInput");

            const iti = window.intlTelInput(input, {
                initialCountry: "auto",
                separateDialCode: true,
                utilsScript: "{{ asset('panel/admin/plugins/country-code/utils.js') }}",
            });
            window.iti = iti;

            const updateCountryCode = () => {
                const selectedCountryData = iti.getSelectedCountryData();
                countryCodeInput.value = selectedCountryData.dialCode;
            };

            input.addEventListener("countrychange", updateCountryCode);
            input.addEventListener("keyup", updateCountryCode);
            input.addEventListener("change", updateCountryCode);

            setTimeout(() => {
                const event = new Event('countrychange');
                input.dispatchEvent(event);
            }, 300);
        });
    </script>
    {{-- END COUNTRYCODE SELECTOR INIT --}}

    {{-- START JS HELEPR INIT --}}
    <script>
        $('.active-swicher').on('click', function() {
            var active = $(this).attr('data-active');
            updateURL('active', active);
        });

        function getStates(countryId = 101) {
            $.ajax({
                url: '{{ route('world.get-states') }}',
                method: 'GET',
                data: {
                    country_id: countryId
                },
                success: function(res) {
                    $('#state').html(res).css('width', '100%').select2();
                }
            })
        }

        function getCities(stateId = 101) {
            $.ajax({
                url: '{{ route('world.get-cities') }}',
                method: 'GET',
                data: {
                    state_id: stateId
                },
                success: function(res) {
                    $('#city').html(res).css('width', '100%').select2();
                }
            })
        }

        // Country, City, State Code
        $('#state, #country, #city').css('width', '100%').select2();

        getStates(101);
        $('#country').on('change', function(e) {
            getStates($(this).val());
        })

        $('#state').on('change', function(e) {
            getCities($(this).val());
        })

        function getStateAsync(countryId) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '{{ route('world.get-states') }}',
                    method: 'GET',
                    data: {
                        country_id: countryId
                    },
                    success: function(data) {
                        $('#state').html(data);
                        $('.state').html(data);
                        resolve(data)
                    },
                    error: function(error) {
                        reject(error)
                    },
                })
            })
        }

        function getCityAsync(stateId) {
            if (stateId != "") {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: '{{ route('world.get-cities') }}',
                        method: 'GET',
                        data: {
                            state_id: stateId
                        },
                        success: function(data) {
                            $('#city').html(data);
                            $('.city').html(data);
                            resolve(data)
                        },
                        error: function(error) {
                            reject(error)
                        },
                    })
                })
            }
        }

        $(document).ready(function() {
            var country = "{{ $user->country_id }}";
            var state = "{{ $user->state_id }}";
            var city = "{{ $user->city_id }}";
            if (state) {
                getStateAsync(country).then(function(data) {
                    $('#state').val(state).change();
                    $('#state').trigger('change');
                });
            }
            if (city) {
                $('#state').on('change', function() {
                    if (state == $(this).val()) {
                        getCityAsync(state).then(function(data) {
                            $('#city').val(city).change();
                            $('#city').trigger('change');
                        });
                    }
                });
            }
        });
    </script>
    {{-- END HELPER JS INIT --}}

    {{-- START IMAGE PREVIEW JS INIT --}}
    <script>
        const avatar = document.getElementById('avatar');
        const imagePreview = document.getElementById('imagePreview');
        const croppedImageDataInput = document.getElementById('croppedImageData');
        const croppieContainer = document.querySelector('.demo');

        let croppieInstance = null;

        // When the input field for selecting an image changes
        avatar.onchange = evt => {
            const [file] = avatar.files;
            if (file) {
                // Show the selected image in the preview
                imagePreview.src = URL.createObjectURL(file);
                // Initialize Croppie on the `.demo` element
                croppieInstance = new Croppie(croppieContainer, {
                    enableExif: true,
                    viewport: {
                        width: 200,
                        height: 200,
                        type: 'circle'
                    },
                    boundary: {
                        width: 300,
                        height: 300
                    }
                });

                // Bind the selected image to Croppie
                croppieInstance.bind({
                    url: URL.createObjectURL(file),
                });
            }
        };

        // Capture cropped image data when the form is submitted
        document.querySelector('#updateProfileImageModal').onsubmit = () => {
            if (croppieInstance) {
                croppieInstance.result('base64').then(function(result) {
                    // Set the cropped image data to the hidden input
                    croppedImageDataInput.value = result;
                });
            }
        };
    </script>
    {{-- END IMAGE PREVIEW JS INIT --}}
@endpush
