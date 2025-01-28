@extends('layouts.empty')

@section('meta_data')
    @php
        $meta_title = 'User Register';
    @endphp
@endsection
<style>
    #password-strength-meter {
        display: none;
        width: 200px;
        height: 6px;
        background-color: lightgray;
        margin-bottom: 10px;
        margin-top: 10px;
    }

    #password-strength-meter div {
        height: 100%;
        transition: width 0.3s ease;
    }

    .weak {
        background-color: red;
    }

    .medium {
        background-color: orange;
    }

    .strong {
        background-color: green;
    }

    .iti--inline-dropdown .iti__dropdown-content {
        z-index: 9 !important;
    }
</style>

@section('content')
    <section class="bg-home-75vh">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-xl-6 col-xxl-5 mx-auto">
                    <div class="card form-signin p-4 mt-2">
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger alert-dismissible fade show my-1" role="alert">
                                    {{ $error }}
                                    <button type="button" class="btn close" data-dismiss="alert" aria-label="Close">
                                    </button>
                                </div>
                            @endforeach
                        @endif
                        <form action="{{ route('register', $role) }}" method="post" class="mt-3 register-form">
                            @csrf
                            <a href="{{ url('/') }}">
                                <img src="{{ getBackendLogo(getSetting('app_logo')) }}" class="avatar-small d-block mx-auto"
                                    alt="" height="100px">
                            </a>
                            <h5 class="mb-3 text-center">Register User and Join {{ getSetting('app_name') }}</h5>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating mb-2">
                                        <input type="text" pattern="{{ regex('name')['pattern'] }}"
                                            title="{{ regex('name')['message'] }}"
                                            minlength="{{ @validation('common_name')['pattern']['minlength'] }}"
                                            maxlength="{{ @validation('common_name')['pattern']['maxlength'] }}"
                                            {{ @validation('common_name')['pattern']['mandatory'] }} class="form-control"
                                            id="floatingInput" placeholder="Enter First Name" name="first_name"
                                            value="{{ old('first_name') }}">

                                        <label for="floatingInput">First Name @if (@validation('common_name')['pattern']['mandatory'])
                                                <span class="text-danger">*</span>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-2">
                                        <input type="text" pattern="{{ regex('name')['pattern'] }}"
                                            title="{{ regex('name')['message'] }}"
                                            minlength="{{ @validation('common_name')['pattern']['minlegth'] }}"
                                            maxlength="{{ @validation('common_name')['pattern']['maxlength'] }}"
                                            {{ @validation('common_name')['pattern']['mandatory'] }} class="form-control"
                                            id="floatingInput" placeholder="Enter last Name" name="last_name"
                                            value="{{ old('last_name') }}" maxlength="50">
                                        <label for="floatingInput">Last Name @if (@validation('common_name')['pattern']['mandatory'])
                                                <span class="text-danger">*</span>
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-2">
                                <input type="email" class="form-control" pattern="{{ regex('email')['pattern'] }}"
                                    title="{{ regex('email')['message'] }}"
                                    minlength="{{ @validation('common_email')['pattern']['minlength'] }}"
                                    maxlength="{{ @validation('common_email')['pattern']['maxlength'] }}"
                                    minlength="{{ @validation('common_email')['pattern']['minlength'] }}"
                                    {{ @validation('common_emali')['pattern']['mandatory'] }} id="floatingEmail"
                                    placeholder="name@example.com" name="email" value="{{ old('email') }}" required>
                                <label for="floatingEmail">Email Address <span class="text-danger">*</span>
                                    @if (@validation('common_emali')['pattern']['mandatory'])
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-floating mb-2">
                                        <div class="input-group">
                                            <input type="hidden" id="countryCodeInput" name="country_code" value="">
                                            <input style="width: 24rem !important;" type="tel" class="form-control"
                                                id="phone" name="phone" value="{{ old('phone') }}">
                                        </div>
                                        <label for="floatingPhone">
                                            @if (@validation('common_phone_number')['pattern']['mandatory'])
                                            @endif
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating form-group mb-2">
                                        <input id="floatingPassword" type="password"
                                            pattern="{{ regex('password')['pattern'] }}"
                                            title="{{ regex('password')['message'] }}" class="form-control"
                                            placeholder="Password" name="password" required
                                            oninput="checkPasswordStrength()" onclick="showPasswordStrengthMeter()">
                                        <label for="floatingPassword">Password <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-12 d-flex text-align-center">
                                    <div class="col-4" id="password-strength-text"><span>Password strength</span> : <span
                                            id="strength-text"></span></div>
                                    <div class="col-8" id="password-strength-meter">
                                        <div></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating form-group mb-2">
                                        <input id="floatingPassword" type="password"
                                            pattern="{{ regex('password')['pattern'] }}"
                                            title="{{ regex('password')['message'] }}"class="form-control"
                                            placeholder="Confirm Password" name="password_confirmation" required>
                                        <label for="floatingPassword">Confirm Password <span
                                                class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-check mx-3">
                                <input class="form-check-input" required type="checkbox" value=""
                                    id="flexCheckDefault">
                                <label class="form-check-label fw-normal text-muted fs-6 ln-1" for="flexCheckDefault">
                                    <small>
                                        By clicking Sign Up, you agree to our Terms, Privacy Policy and Cookies Policy. You
                                        may receive SMS notifications from us and can opt out at any time.
                                    </small>
                                </label>
                            </div>

                            <button class="btn btn-primary w-100" type="submit">Complete Registration</button>

                            <div class="col-12 text-center mt-3">
                                <a href="{{ url('user/login') }}" class="text-dark">Already have an account?</a>
                            </div><!--end col-->

                            <p class="mb-0 text-muted mt-5 text-center">©
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> {{ getSetting('app_name') }}
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script src="{{ asset('site/assets/js/minified/require.js') }}"></script>
    <script src="{{ asset('site/assets/js/bcryptjs/bcrypt.min.js') }}"></script>

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
   {{--END COUNTRYCODE SELECTOR INIT --}}

    <script>
        function showPasswordStrengthMeter() {
            document.getElementById("password-strength-meter").style.display = "block";
        }

        function checkPasswordStrength() {
            var password = document.getElementById("floatingPassword").value;
            var meter = document.getElementById("password-strength-meter").getElementsByTagName("div")[0];
            var strengthText = document.getElementById("strength-text");
            var strength = 0;
            if (password.length >= 8) {
                strength += 1;
            }

            if (/[A-Z]/.test(password)) {
                strength += 1;
            }

            if (/[a-z]/.test(password)) {
                strength += 1;
            }

            if (/\d/.test(password)) {
                strength += 1;
            }

            if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)) {
                strength += 1;
            }

            if (strength < 3) {
                meter.className = "weak";
            } else if (strength < 5) {
                meter.className = "medium";
            } else {
                meter.className = "strong";
            }

            meter.style.width = (strength * 20) + "%";
        }
    </script>
@endpush
