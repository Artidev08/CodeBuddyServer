@extends('layouts.main')
@section('title', @$label . ' Add')
@section('content')

    @push('head')
        <link rel="stylesheet" href="{{ asset('admin/plugins/select2/dist/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.css') }}">
    @endpush

    @push('style')
        <style>
            .input-group {
                position: relative;
                display: flex;
                align-items: center;
            }

            .input-group input {
                width: 100%;
                padding-right: 40px;
                /* Adjust based on icon size */
            }

            .input-group .input-group-text {
                position: absolute;
                right: 10px;
                /* Adjust based on design */
                cursor: pointer;
                background: none;
                border: none;
                margin-top: -25px;
                margin-right: -10px;
            }

            @media (min-width: 992px) {
                .container-fluid-height {
                    height: 83vh;
                }
            }

            .iti--inline-dropdown .iti__dropdown-content {
                z-index: 9 !important;
            }
        </style>
    @endpush

    <div class="container-fluid container-fluid-height ">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5> @lang('admin/ui.add') {{ @$label ?? '' }}</h5>
                            <span> @lang('admin/ui.admin_edit_subheading') </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 d-sm-flex d-lg-block">
                    <nav class="breadcrumb-container " aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('panel.admin.dashboard.index') }}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('panel.admin.users.index') }}">{{ @$label ?? '' }}</a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="#"> @lang('admin/ui.add')</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <form class="ajaxForm" method="POST" action="{{ route('panel.admin.users.store') }}" autocomplete="off">
            @csrf

            <x-input name="request_with" placeholder="" type="hidden" tooltip="" regex="" validation=""
                value="create" />
            <x-input name="role" placeholder="" type="hidden" tooltip="" regex="" validation=""
                value="{{ request()->get('role') }}" />
            <div class="row">

                <div class="col-md-7 mx-auto">
                    @include('panel.admin.include.message')
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3> @lang('admin/ui.personal_info') </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <x-label name="first_name" validation="common_name" tooltip="add_user_first_name" />
                                        <x-input name="first_name"
                                            placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.first_name') }}"
                                            type="text" tooltip="add_user_first_name" regex="name"
                                            validation="common_name" value="{{ old('first_name') }}" />
                                        <x-message name="first_name" :message="@$message" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <x-label name="last_name" validation="common_name" tooltip="add_user_last_name" />
                                        <x-input name="last_name"
                                            placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.last_name') }}"
                                            type="text" tooltip="add_user_last_name" regex="name"
                                            validation="common_name" value="{{ old('last_name') }}" />
                                        <x-message name="last_name" :message="@$message" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <x-label name="email" validation="common_email" tooltip="add_user_email" />
                                        <x-input name="email"
                                            placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.email') }}"
                                            type="email" tooltip="add_user_email" regex="email" validation="common_email"
                                            value="{{ old('email') }}" />
                                        <x-message name="email" :message="@$message" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <x-label name="contact_number" validation="common_phone_number"
                                            tooltip="add_user_phone" />

                                        {{-- <x-input name="phone"
                                            placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.phone_number') }}"
                                            id="phone" type="number" tooltip="add_user_phone" regex="phone_number"
                                            validation="common_phone_number" value="{{ old('phone') }}" style="width: 18rem !important;"/>
                                        <x-message name="phone" :message="@$message" /> --}}

                                        <div class="input-group">
                                            <input type="hidden" id="countryCodeInput" name="country_code" value="">
                                            <input type="tel" class="form-control" id="phone" name="phone"
                                                value="{{ old('phone') }}" style="color:white !important background-color: #3d405d;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    @php
                                        $gender_arr = [__('admin/ui.male'), __('admin/ui.female')];
                                        $selectedOption = old('gender', 'Male');
                                    @endphp
                                    <x-label name="gender" validation="gender" tooltip="add_user_gender" />
                                    <div class="form-group d-flex justify-between">
                                        @foreach ($gender_arr as $gender)
                                            <div class="form-check mr-4">
                                                <input class="form-check-input mt-0" type="radio" name="gender"
                                                    id="gender_{{ $gender }}" value="{{ $gender }}"
                                                    {{ $selectedOption == $gender ? 'checked' : '' }} required>
                                                <label class="form-check-label mt-0" for="gender_{{ $gender }}">
                                                    {{ $gender }}
                                                </label>
                                            </div>
                                        @endforeach
                                        <x-message name="gender" :message="@$message" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <x-label name="assign_role" validation="assign_role" tooltip="add_role_name" />
                                        <x-select name="role" value="{{ old('role') }}" label="Role"
                                            optionName="name" class="select2" :arr="@$roles" validation="assign_role"
                                            id="roleId" valueName="id" />
                                    </div>
                                </div>

                                @php
                                    $now = now()->format('Y-m-d');
                                @endphp
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <x-label name="dob" validation="admin_dob" tooltip="add_user_dob" />
                                        <x-date regex="dob" :max="$now" validation="admin_dob" type="date"
                                            value="{{ old('dob') }}" name="dob" id="dob"
                                            placeholder="Select your date" />
                                        <x-message name="dob" :message="@$message" />
                                    </div>
                                </div>
                                <div class="col-md-6  d-flex align-items-center">
                                    <div class="form-group d-none">
                                        <x-label name="status" tooltip="add_user_status" />
                                        <x-input regex="" tooltip="add_user_status" validation="" type="checkbox"
                                            value="1" name="status" id="status" placeholder="Select your date"
                                            checked />
                                        <x-message name="status" :message="@$message" />

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 mx-auto">
                    @include('panel.admin.include.message')
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="mb-0"> @lang('admin/ui.ekyc') </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 ">
                                    @php
                                        $checkbox_arr = ['send_mail'];
                                    @endphp
                                    <div class="form-group">
                                        <x-checkbox name="send_mail" value="1" type="checkbox"
                                            tooltip="add_user_send_mail" :arr="@$checkbox_arr" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @php
                                        $checkbox_arr = ['verify_mail'];
                                    @endphp
                                    <div class="form-group">
                                        <x-checkbox name="verify_mail" value="1" type="checkbox"
                                            tooltip="add_user_verify_mail" :arr="@$checkbox_arr" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 mx-auto" style="margin-top: -284px; margin-right: 0px !important;">
                    @include('panel.admin.include.message')
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="mb-0"> @lang('admin/ui.roles_security') </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class=" d-flex justify-content-between">
                                            <div>
                                                <x-label name="set_password" validation="admin_password"
                                                    tooltip="add_user_password" />
                                            </div>
                                            <button type="button"
                                                class="btn btn-link p-0 m-0 generate_pass">@lang('admin/ui.generate_password')
                                            </button>
                                        </div>

                                        <div class="input-group mb-3">
                                            <x-input name="password"
                                                placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.password') }}"
                                                type="password" tooltip="add_user_password" regex="password"
                                                validation="admin_password" value="{{ old('password') }}" />
                                            
                                            <div class="input-group-append">
                                                <span class="input-group-text"
                                                    style="cursor: pointer; position: absolute; right: 0px;"
                                                    onclick="togglePasswordVisibility()">
                                                    <i class="ik ik-eye text-color-black" id="togglePassword"></i>
                                                </span>
                                            </div>
                                        </div>


                                        <x-message name="password" :message="@$message" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <x-input name="" id="role_name" placeholder="" type="hidden" tooltip="" regex=""
                validation="" value="{{ request()->get('role') ?? '' }}" />
            <button class="btn btn-primary floating-btn ajax-btn" type="submit">
                Create {{ request()->get('role') ?? '' }}
            </button>
        </form>
    </div>

@endsection

@push('script')
    {{-- START SELECT 2 BUTTON INIT --}}
    <script>
        $('select.select2').select2();
    </script>
    {{-- END SELECT 2 BUTTON INIT --}}

    {{-- COUNTRYCODE SELECTOR INIT --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.querySelector("#phone");
            const countryCodeInput = document.querySelector("#countryCodeInput");

            const iti = window.intlTelInput(input, {
                initialCountry: "auto",
                separateDialCode: true,
                geoIpLookup: callback => {
                    fetch("https://ipapi.co/json")
                        .then(res => res.json())
                        .then(data => callback(data.country_code))
                        .catch(() => callback("us"));
                },
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

    {{-- START AJAX FORM INIT --}}

    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var route = form.attr('action');
            var method = form.attr('method');
            var data = new FormData(this);
            var role = $('#role_name').val();
            var redirectUrl = "{{ url('admin/users') }}" + '?role=' + role;
            var response = postData(method, route, 'json', data, null, null, '1', true, redirectUrl, form);
        })
    </script>
    {{-- END AJAX FORM INIT --}}

    {{-- START JS HELPERS INIT --}}
    <script>
        $(document).ready(function() {
            $('#togglePassword').click(function() {

                var input = $('#password');
                var icon = $(this);

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('ik-eye').addClass('ik-eye-off');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('ik-eye-off').addClass('ik-eye');
                }
            });

            $(document).ready(function() {

                $('#state, #country, #city').css('width', '100%').select2();

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

                getStates(101);

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

                $('#country').on('change', function(e) {
                    getStates($(this).val());
                })

                $('#state').on('change', function(e) {
                    getCities($(this).val());
                })

            });

            var pass = "";
            $('.generate_pass').on('click', function() {
                var length = 8; // Minimum length required by the pattern
                var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*";
                var hasDigit = false;
                var hasLowercase = false;
                var hasUppercase = false;
                var hasSpecialChar = false;

                while (pass.length < length || !(hasDigit && hasLowercase && hasUppercase &&
                        hasSpecialChar)) {
                    pass = "";
                    hasDigit = false;
                    hasLowercase = false;
                    hasUppercase = false;
                    hasSpecialChar = false;

                    for (var x = 0; x < length; x++) {
                        var i = Math.floor(Math.random() * chars.length);
                        pass += chars.charAt(i);
                    }

                    for (var i = 0; i < pass.length; i++) {
                        if (/[0-9]/.test(pass[i])) hasDigit = true;
                        else if (/[a-z]/.test(pass[i])) hasLowercase = true;
                        else if (/[A-Z]/.test(pass[i])) hasUppercase = true;
                        else if (/[!@#$%^&*]/.test(pass[i])) hasSpecialChar = true;
                    }
                }


                $('#password').val(pass);
            });
            $('#password').val(pass);
        });
    </script>
    {{-- END JS HELPERS INIT --}}
@endpush
