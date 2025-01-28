@extends('layouts.empty')

@section('meta_data')
    @php
        $meta_title = @$metas->title ?? 'Admin Login';
        $meta_description = @$metas->description ?? '';
        $meta_keywords = @$metas->keyword ?? '';
        $meta_motto = @$app_settings['site_motto'] ?? '';
        $meta_abstract = @$app_settings['site_motto'] ?? '';
        $meta_author_name = @$app_settings['app_name'] ?? 'Defenzelite';
        $meta_author_email = @$app_settings['frontend_footer_email'] ?? 'dev@defenzelite.com';
        $meta_reply_to = @$app_settings['frontend_footer_email'] ?? 'dev@defenzelite.com';
        $meta_img = '';
    @endphp
@endsection

<style>
    .field-icon {
        float: right;
        margin-right: 7px;
        margin-top: -34px;
        position: relative;
        z-index: 2;
    }

    .alert {
        padding: 0px 15px !important;
    }

    .alert-danger {
        color: #842029 !important;
        background-color: #f8d7da !important;
        border-color: #f5c2c7 !important;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        text-align: center;
        font-weight: 600;
    }

    @media (max-width: 700px) {
        .custom-input_box {
            width: 25px !important;
            height: 30px;
            border: 0;
            border-bottom: 1px solid #817d7d;
        }
        .form-center{
            margin-top: 0rem !important;
        }
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
    .form-center{
        margin-top: 3rem !important;
    }
</style>
@section('content')
    <section class="bg-home-75vh">
        <div class="container">
            <div class="row form-center mt-14">
                <div class="col-lg-7 col-xl-6 col-xxl-5 mx-auto ">
                    <div class="card">
                        <div class="card-body text-center">
                            @if (getSetting('authentication_mode') == 1)
                                <div class="p-3 bg-white form-signin">
                                    <form method="POST" action="{{ route('login', $role) }}" class="login-form"
                                          autocomplete="off">
                                        @csrf
                                        <a href="{{ route('index') }}">
                                            <img src="{{ getBackendLogo(getSetting('app_logo')) }}"
                                                 class="d-block mx-auto"
                                                 height="100px" alt="">
                                        </a>
                                        <h5 class="mb-3 text-center">Sign in to Admin panel</h5>
                                        @if (session('error'))
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                {{ session('error') }}
                                                <button type="button" class="btn close text-white" data-dismiss="alert"
                                                        aria-label="Close">
                                                </button>
                                            </div>
                                        @endif
                                        @if ($errors->any())
                                            @foreach ($errors->all() as $error)
                                                <div class="alert alert-danger alert-dismissible fade show mb-3"
                                                     role="alert">
                                                    {{ $error }}
                                                    <button type="button" class="btn close text-white"
                                                            data-dismiss="alert"
                                                            aria-label="Close">
                                                    </button>
                                                </div>
                                            @endforeach
                                        @endif
                                        <div class="form-floating mb-2">
                                            <input type="email" name="email"

                                                   class="form-control  @error('email') is-invalid @enderror"
                                                   id="floatingInput" placeholder="name@example.com"
                                                   value="{{ old('email') }}" required autocomplete="off" autofocus>
                                            <label for="floatingInput">Email Address</label>
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input type="password" autocomplete="off"
                                                   class="form-control @error('password') is-invalid @enderror"
                                                   id="password-field" placeholder="Password" name="password" required>
                                            <label for="password-field">Password</label>
                                            <span toggle="#password-field"
                                                  class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        @if(getSetting('recaptcha')==1)
                                        <div class="form-floating mb-3">
                                            <input type="text" autocomplete="off"
                                                   class="form-control @error('recapcha') is-invalid @enderror"
                                                   id="password-field" placeholder="Enter Recapcha" name="recapcha" required>
                                            <label for="password-field">Recapcha</label>
                                            <span toggle="#password-field"
                                                  class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        @endif
                                        <div class="d-flex justify-content-between">
                                            <div class="mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="option1"
                                                           id="flexCheckDefault" name="item_checkbox">
                                                    <label class="form-check-label fw-normal"
                                                           for="flexCheckDefault">Remember
                                                        me</label>
                                                </div>
                                            </div>
                                            <div class="forgot-pass mb-0">
                                                <a href="{{ route('password.forget') }}"
                                                   class="text-danger fw-normal">Forgot password?</a>
                                            </div>
                                        </div>

                                        <button class="btn btn-primary rounded-pill btn-login w-100 mb-2" type="submit">
                                            Secure Sign-In
                                        </button>
                                        <p class="mb-0 text-muted mt-5 text-center">©
                                            <script>
                                                document.write(new Date().getFullYear())
                                            </script> {{ getSetting('app_name') }}
                                        </p>
                                    </form>
                                </div>
                            @else
                                <div class="py-3 bg-white rounded shadow form-signin">
                                    <form action="{{ route('login-validate') }}" method="POST" class="digit-group"
                                          data-group-name="digits" data-autosubmit="false" autocomplete="off"
                                          style="height: 300px;">
                                        @csrf
                                        <a href="{{ route('index') }}">
                                            <img src="{{ getBackendLogo(getSetting('app_logo')) }}"
                                                 class=" avatar-small mb-4 d-block mx-auto" height="100px" alt="">
                                        </a>
                                        <h5 class="mb-5 mt-3 text-center">Sign in to {{ getSetting('app_name') }}</h5>
                                        <div
                                            class="form-icon position-relative {{ $errors->has('phone') ? 'has-error' : '' }}">
                                            <div class="form-floating text-center mx-2 phone-input-box" style="">
                                                <input required name="phone[]" class="custom-input_box" type="number"
                                                       id="digit-1" data-next="digit-2" maxlength="1" max="9">
                                                <input required name="phone[]" class="custom-input_box" type="number"
                                                       id="digit-2" data-next="digit-3" data-previous="digit-1"
                                                       maxlength="1" max="9">
                                                <input required name="phone[]" class="custom-input_box mt-1"
                                                       type="number" id="digit-3" data-next="digit-4"
                                                       data-previous="digit-2" maxlength="1" max="9">
                                                <input required name="phone[]" class="custom-input_box" type="number"
                                                       id="digit-4" data-next="digit-5" data-previous="digit-3"
                                                       maxlength="1" max="9">

                                                <input required name="phone[]" class="custom-input_box mt-1"
                                                       type="number" id="digit-5" data-next="digit-6"
                                                       data-previous="digit-4" maxlength="1" max="9">
                                                <input required name="phone[]" class="custom-input_box" type="number"
                                                       id="digit-6" data-next="digit-7" data-previous="digit-5"
                                                       maxlength="1" max="9">
                                                <input required name="phone[]" class="custom-input_box mt-1"
                                                       type="number" id="digit-7" data-next="digit-8"
                                                       data-previous="digit-6" maxlength="1" max="9">
                                                <input required name="phone[]" class="custom-input_box" type="number"
                                                       id="digit-8" data-next="digit-9" data-previous="digit-7"
                                                       maxlength="1" max="9">

                                                <input required name="phone[]" class="custom-input_box mt-1"
                                                       type="number" id="digit-9" data-next="digit-10"
                                                       data-previous="digit-8" maxlength="1" max="9">
                                                <input required name="phone[]" class="custom-input_box" type="number"
                                                       id="digit-10" data-next="digit-11" data-previous="digit-9"
                                                       maxlength="1" max="9">
                                            </div>

                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ 'Please Enter 10 Digit Number' }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100 mt-5"
                                                style="width:auto!important;margin:auto;display:block;"><span
                                                class="text-white"> Verify By OTP</span></button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('script')
    <script>
        // {{--START PASSWORD ENCRYPTION INIT--}}
        $(document).ready(function () {
            $('.login-form').on('submit', async function (e) {
                hashed = btoa($('#password-field').val());
                $('#password-field').val(hashed);
            });
        });
        // {{--END PASSWORD ENCRYPTION INIT--}}

        // Auto Fill OTP Input Start
        $('.digit-group').find('input').each(function () {
            $(this).attr('maxlength', 1);
            $(this).on('keyup', function (e) {
                var parent = $($(this).parent());

                if (e.keyCode === 8 || e.keyCode === 37) {
                    var prev = parent.find('input#' + $(this).data('previous'));

                    if (prev.length) {
                        $(prev).select();
                    }
                } else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 96 && e.keyCode <= 105)) {
                    var next = parent.find('input#' + $(this).data('next'));

                    if (next.length) {
                        $(next).select();
                    } else {
                        if (parent.data('autosubmit')) {
                            parent.submit();
                        }
                    }
                }
            });
        });
        // Auto Fill OTP Input End

        // Input Paste Script Start
        $('.custom-input_box').on('click keyup paste', function () {
            var input_val = $(this).val();
            console.log(input_val);
            if (input_val.length > 1) {
                $(this).val(input_val.slice(0, 1));
            }
        });
        // Input Paste Script End

        // Password Show/Hide Script Start
        $(document).on('click', '.toggle-password', function () {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
        // Password Show/Hide Script End

    </script>
@endpush
