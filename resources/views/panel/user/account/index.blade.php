@extends('layouts.user')

@section('meta_data')
    @php
        $meta_title = __('user/ui.profile') . ' | ' . getSetting('app_name');
        $meta_description = '' ?? getSetting('seo_meta_description');
        $meta_keywords = '' ?? getSetting('seo_meta_keywords');
        $meta_motto = '' ?? getSetting('site_motto');
        $meta_abstract = '' ?? getSetting('site_motto');
        $meta_author_name = '' ?? 'Defenzelite';
        $meta_author_email = '' ?? 'support@defenzelite.com';
        $meta_reply_to = '' ?? getSetting('app_email');
        $meta_img = ' ';
        $customer = 1;
        $userPanel = true;
    @endphp
@endsection

@section('content')
    @push('style')
        <link rel="stylesheet" href="{{ asset('site/assets/css/intlTelInput.css') }}">
        <style>
            /* Style Comes Here */
            .profile-pic {
                width: 200px;
                max-height: 200px;
                display: inline-block;
            }

            .circle {
                border-radius: 100% !important;
                overflow: hidden;
                width: 128px;
                height: 128px;
                border: 2px solid rgba(255, 255, 255, 0.2);
                top: 72px;
                background: #4b88d7;
            }

            img {
                max-width: 100%;
                height: auto;
            }

            .p-image {
                top: 167px;
                right: 30px;
                color: #666666;
                transition: all .3s cubic-bezier(.175, .885, .32, 1.275);
            }

            .p-image:hover {
                transition: all .3s cubic-bezier(.175, .885, .32, 1.275);
            }

            .upload-button {
                font-size: 1.2em;
            }

            .upload-button:hover {
                transition: all .3s cubic-bezier(.175, .885, .32, 1.275);
                color: #999;
            }

            .file--upload>label {
                color: hsl(204, 86%, 53%);
                border-color: hsl(204, 86%, 53%);
            }

            .files>label {
                font-size: 13px;
                font-weight: 700;
                cursor: pointer;
                outline: 0;
                user-select: none;
                border-style: solid;
                border-radius: 4px;
                border-width: 2px;
                background-color: hsl(0deg 0% 100%);
                padding-left: 20px;
                padding-right: 20px;
                padding-top: 7px;
                padding-bottom: 7px;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .files>input[type='file'] {
                display: none;
            }

            .image-uploade:hover {
                border-color: hsl(204, 85%, 48%);
                box-shadow: 0 5px 20px rgba(234, 234, 234, 0.8);
            }

            .iti--inline-dropdown .iti__dropdown-content {
                z-index: 9 !important;
            }
        </style>
    @endpush

    <div class="row">
        <div class="main-card">
            @include('panel.user.include.message')
            @php
                $sectionHeader = [
                    'headline' => __('user/ui.profile'),
                    'sub_headline' => __('user/ui.check_profile'),
                ];
            @endphp
            @include('panel.user.partials.section_header')

            <div class="row">
                <div class="col-lg-8 col-md-12 col-12">
                    <form method="post" action="{{ route('panel.user.setting.store', auth()->id()) }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <p class="fs-16 p-0 mb-0">@lang('user/ui.about')</p>
                            </div>
                            <div class="card-body px-lg-5 px-md-4 px-2">
                                <div class="row gx-4">
                                    <div class="d-flex mb-4">
                                        <div class="">
                                            <img class="avatar rounded-circle"
                                                src="{{ $user && $user->avatar ? $user->avatar : asset('panel/admin/default/default-avatar.png') }}"
                                                width="100px" style="object-fit: cover; width: 100px; height: 100px" />
                                        </div>
                                        <div class='files file--upload mt-8 ms-5'>
                                            {{-- for='input-files' --}}
                                            <label> <a href="javascript:void(0)" id="changeProfileModal"
                                                    class="d-inline-block">
                                                    <i class="uil uil-camera fs-13 fw-semibold"></i>@lang('user/ui.upload')
                                                </a></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-4">
                                            <input id="form_name" type="text" name="first_name" class="form-control"
                                                pattern="{{ regex('name')['pattern'] }}"
                                                title="{{ regex('name')['message'] }}" placeholder="Jane"
                                                value="{{ auth()->user()->first_name }}" required>
                                            <label for="form_name">@lang('user/ui.first_name')<span
                                                    class="text-danger">*</span></label>
                                            <div class="valid-feedback"> Looks good!</div>
                                            <div class="invalid-feedback"> Please enter your first name.</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-4">
                                            <input id="form_lastname" type="text" name="last_name" class="form-control"
                                                pattern="{{ regex('name')['pattern'] }}"
                                                title="{{ regex('name')['message'] }}" placeholder="Doe"
                                                value="{{ auth()->user()->last_name }}" required>
                                            <label for="form_lastname">@lang('user/ui.last_name')<span
                                                    class="text-danger">*</span></label>
                                            <div class="valid-feedback"> Looks good!</div>
                                            <div class="invalid-feedback"> Please enter your last name.</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-4">
                                            <input id="form_email" type="email" name="email" class="form-control"
                                                pattern="{{ regex('email')['pattern'] }}"
                                                title="{{ regex('email')['message'] }}" placeholder="jane.doe@example.com"
                                                disabled value="{{ auth()->user()->email }}" required>
                                            <label for="form_email">@lang('user/ui.email')<span
                                                    class="text-danger">*</span></label>
                                            <div class="valid-feedback"> Looks good!</div>
                                            <div class="invalid-feedback"> Please provide a valid email address.</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating mb-4">
                                            <div class="input-group">
                                                <input type="hidden" id="countryCodeInput" name="country_code"
                                                    value="">
                                                <input type="tel" style="width: 16rem !important;"class="form-control"
                                                    id="phone" name="phone" value="{{ $user->fullPhone() }}">

                                            </div>
                                            <label for="floatingPhone">
                                                @if (@validation('common_phone_number')['pattern']['mandatory'])
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-4">
                                            <input type="date" id="dob" name="dob" placeholder="dd-mm-yyyy"
                                                pattern="{{ regex('dob')['pattern'] }}"
                                                title="{{ regex('dob')['message'] }}" class="form-control"
                                                value="{{ auth()->user()->dob }}" max="{{ now()->format('Y-m-d') }}">
                                            <label for="form_email">@lang('user/ui.dob')<span
                                                    class="text-danger">*</span></label>
                                            <div class="valid-feedback"> Looks good!</div>
                                            <div class="invalid-feedback"> Please provide a valid email address.</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6" id="genderFormGroup">
                                        <label for="form_gender" class="fs-15">@lang('user/ui.gender')<span
                                                class="text-danger">*</span></label>
                                        <div class="d-flex">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gender"
                                                    id="gender1" value="Male"
                                                    @if ($user->gender == 'Male') checked @endif>
                                                <label class="form-check-label fs-13"
                                                    for="gender1">@lang('user/ui.male')</label>
                                            </div>
                                            <div class="form-check ms-5 mb-4">
                                                <input class="form-check-input" type="radio" name="gender"
                                                    id="gender2" value="Female"
                                                    @if ($user->gender == 'Female') checked @endif>
                                                <label class="form-check-label fs-13"
                                                    for="gender2">@lang('user/ui.female')</label>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="col-12">
                                        <div class="form-floating mb-4">
                                            <textarea name="bio" id="comments" class="form-control" placeholder="Your message" style="height: 100px">{{ auth()->user()->bio }}</textarea>
                                            <label for="form_message">@lang('user/ui.bio') </label>
                                            <div class="valid-feedback"> Looks good!</div>
                                            <div class="invalid-feedback"> Please enter your messsage.</div>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>
                        <div class="card mt-2">
                            <div class="card-header">
                                <p class="fs-16 p-0 mb-0 ">@lang('user/ui.system')</p>
                            </div>
                            <div class="card-body px-lg-5 px-md-4 px-2">
                                @php
                                    $languages = App\Models\User::LANGUAGE;
                                @endphp

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="language"> @lang('admin/ui.language') <span class="text-red">*</span> </label>
                                        <select name="language" class="form-control select2" id="language">
                                            @foreach ($languages as $key => $language)
                                                <option value="{{ $key }}"
                                                    @if (isset($user->preferences) && $key == @$user->preferences['language']) selected @endif>
                                                    {{ $language['label'] }}</option>
                                            @endforeach
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.row -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-sm float-end" id="saveUpdateButton"
                                style="right: 10px;
    bottom: 48px;"> @lang('user/ui.save_update') </button>
                        </div>
                    </form>
                </div>
                @if (getSetting('user_mfa_activation') == 1)
                    <div class="col-lg-4 col-md-12 col-12 mt-lg-0 mt-md-3 mt-3 float-end mr-0">
                        <div class="card">
                            <div class="card-header border-bottom">
                                <h6 class="text-center fw-100 m-0">@lang('user/ui.mfa')</h6>
                            </div>
                            <div class=" mt-0">
                                <div class="tab-pane fade @if (request()->has('active') && request()->get('active') == 'security') show active @endif"
                                    id="security" role="tabpanel" aria-labelledby="pills-security-tab">
                                    <form action="{{ route('mfa-store') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="secret_key" value="{{ $secret }}">
                                        @if (auth()->user()->google2fa_secret == null)
                                            <div class="card-body text-center">
                                                <div class="border-bottom">
                                                    {!! $QR_Image !!}
                                                </div>
                                                <div class="text-center text-muted  mx-auto mb-4" style="font-size: 12px">
                                                    @lang('user/ui.mfa_setup_instruction') <b><a
                                                            href="https://safety.google/authentication/">@lang('user/ui.google_authenticator')</a></b>
                                                    @lang('user/ui.app_for_continuing')
                                                </div>

                                                <button class="btn btn-outline-primary">@lang('user/ui.scanned_qr')</button>
                                            </div>
                                        @else
                                            <div class="card-body text-center">
                                                <h6 class="fw-700 mb-0">@lang('user/ui.two_factor_authentication')</h6>
                                                <p class="text-muted mb-4">@lang('user/ui.two_factor_enabled')</p>
                                                <a href="{{ route('mfa-enabled') }}"
                                                    class="btn btn-danger">@lang('user/ui.scan_again')</a>
                                            </div>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @include('panel.user.modal.update-profile-picture')
@endsection

@push('script')
    <script src="{{ asset('site/assets/js/country-code/intl-tel-input.js') }}"></script>

    {{-- START PROFILE MODAL INIT --}}
    <script>
        document.getElementById('avatar').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            $('#avatar_file').removeClass('d-none');
            document.getElementById('avatar_file').src = src
        }

        $('#changeProfileModal').on('click', function() {
            $('#profilePicture').modal('show');
        });
    </script>
    {{-- END PROFILE MODAL INIT --}}

    {{-- COUNTRYCODE SELECTOR INIT --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.querySelector("#phone");
            const countryCodeInput = document.querySelector("#countryCodeInput");

            const iti = window.intlTelInput(input, {
                initialCountry: "auto",
                separateDialCode: true,
                utilsScript: "{{ asset('site/assets/js/country-code/utils.js') }}",
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
@endpush
