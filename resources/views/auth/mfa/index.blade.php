@extends('layouts.empty')

@section('meta_data')
    @php
        $meta_title = 'Login | ' . getSetting('app_name');
        $meta_description = '' ?? getSetting('seo_meta_description');
        $meta_keywords = '' ?? getSetting('seo_meta_keywords');
        $meta_motto = '' ?? getSetting('site_motto');
        $meta_abstract = '' ?? getSetting('site_motto');
        $meta_author_name = '' ?? 'Defenzelite';
        $meta_author_email = '' ?? 'support@defenzelite.com';
        $meta_reply_to = '' ?? getSetting('app_email');
        $meta_img = ' ';
    @endphp
@endsection
<style>
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

    @media(max-width: 700px) {
        .custom-input_box {
            width: 25px !important;
            height: 30px;
            border: 0;
            border-bottom: 1px solid #817d7d;
        }
    }

    .forgot-pass {
        font-weight: 500;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
@section('content')
    <section class="bg-home d-flex align-items-center position-relative p-lg-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-4 col-sm-12 mx-auto">
                    <div class="p-3 bg-white rounded shadow form-signin">
                        <form method="POST" action="{{ route('2fa') }}">
                            @csrf
                            <a href="{{ route('index') }}">
                                <img src="{{ getBackendLogo(getSetting('app_logo')) }}  "
                                    class=" avatar-small mb-4 d-block mx-auto" height="100px"  alt="">
                            </a>
                            {{-- <h5 class="mb-3 text-center">Please sign in</h5> --}}
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
                                    <div class="alert alert-danger alert-dismissible fade show mb-3 p-2" role="alert">
                                        {{ $error }}
                                    </div>
                                @endforeach
                            @endif
                            <div class="form-floating">
                                {{-- <label class="text-muted" for="">Enter OTP</label> --}}
                                <input required type="number" placeholder="Enter OTP" name="one_time_password"
                                    class="form-control  @error('one_time_password') is-invalid @enderror"
                                    id="floatingInput" autofocus>
                                @error('one_time_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <button class="btn btn-success w-100 mt-3" type="submit">Authenticate Your Account</button>

                            <div class="col-12 text-center mt-3">
                                <p class="mb-0 mt-3"><span class="text-dark me-2">Facing Trouble?</span>
                                    <a href="{{ route('mfa-reset-form') }}" class="text-link">
                                        <strong>Reset 2FA</strong>
                                    </a>
                                </p>
                            </div><!--end col-->

                            <p class="mb-0 text-muted mt-3 text-center">©
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
    <script>
        // Auto Fill OTP Input Start
        $('.digit-group').find('input').each(function() {
            $(this).attr('maxlength', 1);
            $(this).on('keyup', function(e) {
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

        $('.custom-input_box').on('click keyup paste', function() {
            var input_val = $(this).val();
            console.log(input_val);
            if (input_val.length > 1) {
                $(this).val(input_val.slice(0, 1));
            }
        });
    </script>
@endpush
