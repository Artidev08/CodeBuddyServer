@extends('layouts.empty')
@section('content')
    <style>
        .otp {
            width: 30px;
            margin-right: 5px
        }

        .otp2 {
            width: 30px;
            margin-right: 5px
        }

        .form-floating {
            display: flex;
            justify-content: center;
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
            text-align: center !important;
            font-weight: 600;
        }

        @media (max-width: 700px) {
            .custom-input_box {
                width: 25px !important;
                height: 30px;
                border: 0;
                border-bottom: 1px solid #817d7d;
            }
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>

    <section class="bg-home d-flex align-items-center position-relative"
        style="background: url('images/shape01.png') center;">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="form-signin p-4 bg-white rounded shadow">
                        <form action="{{ route('otp-validate') }}" class="digit-group" data-group-name="digits"
                            data-autosubmit="false" autocomplete="off" method="post">
                            @csrf
                            <a href="{{ url('/') }}">
                                <img src="{{ getBackendLogo(getSetting('app_logo')) }}"
                                    class="avatar-small mb-4 d-block mx-auto" height="100px" alt="">
                            </a>
                            <h5 class="mb-3 text-center">Verify OTP</h5>
                            <p class="text-muted text-center">
                                Please enter your 4-digit OTP which has been sent to your mobile number
                                <span class="text-primary"> {{ $phone }}
                                    <a class="text-muted" title="Change Mobile Number?" href="{{ url('admin/login') }}">
                                        <small class="text-primary"
                                            style="text-decoration:underline;color: #000 !important;
                                            font-weight: 600;">change</small>
                                    </a>
                                </span>
                            </p>

                            <div class="text-center mb-3">
                                {{ session()->get('otp') }}
                            </div>
                            <div class="form-floating mb-3 text-center">
                                <input required class="otp custom-input_box" maxlength="1" type="number" id="digit-1"
                                    name="otp[]" data-next="digit-2" />
                                <input required class="otp custom-input_box" maxlength="1" type="number" id="digit-2"
                                    name="otp[]" data-next="digit-3" data-previous="digit-1" />
                                <input required class="otp custom-input_box" maxlength="1" type="number" id="digit-3"
                                    name="otp[]" data-next="digit-4" data-previous="digit-2" />

                                <input required class="otp custom-input_box" maxlength="1" type="number" id="digit-4"
                                    name="otp[]" data-next="digit-5" data-previous="digit-3" />
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mt-2"
                                style="width:auto!important;margin:auto;display:block;"><span class="text-white"> Verify
                                    OTP</span>
                            </button>

                            <a href="javascript:void(0)" id="sendOTP"
                                style="text-align: center;display: block;padding-top: 5px;color: #6666cc;">Resend OTP</a>

                            <div id="Timer" class="text-muted" style="text-align: center;"></div>

                            <p class="mb-0 text-muted mt-3 text-center" style="position:relative">
                                {{ getSetting('frontend_copyright_text') }}
                            </p>
                        </form>
                    </div>
                    <form id="resendOTP" action="{{ route('login-validate') }}" method="POST">
                        @csrf
                        <input type="hidden" value="1" name="resent">
                        <input required name="phone" pattern="^[0-9]*$" min="0" id="phone" type="hidden"
                            value="{{ request()->phone }}">
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
     // KeyUp click Paste Script Start
        $('.digit-group').find('input').each(function() {
            $(this).attr('maxlength', 1);
            $(this).on('keyup', function(e) {
                var parent = $($(this).parent());

                if (e.keyCode === 8 || e.keyCode === 37) {
                    var prev = parent.find('input#' + $(this).data('previous'));

                    if (prev.length) {
                        $(prev).select();
                    }
                } else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (
                        e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
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

            $('#sendOTP').on('click', function() {
                $('#resendOTP').submit();
            });
        });
        // KeyUp click Paste Script End
        
        $('#sendOTP').hide();

        var timeLeft = 30;
        var elem = document.getElementById('Timer');

        var timerId = setInterval(countdown, 1000);

        function countdown() {
            if (timeLeft == 0) {
                clearTimeout(timerId);
                $('#sendOTP').show();
                $('#Timer').hide();
            } else {
                elem.innerHTML = timeLeft + ' seconds';
                timeLeft--;
            }
        }

        $('.custom-input_box').on('click keyup paste', function() {
            var input_val = $(this).val();
            console.log(input_val);
            if (input_val.length > 1) {
                $(this).val(input_val.slice(0, 1));
            }
        });
    </script>
@endpush
