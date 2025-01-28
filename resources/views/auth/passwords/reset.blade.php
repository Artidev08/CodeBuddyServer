@extends('layouts.empty')

@section('meta_data')
    @php
        $meta_title = 'Reset Password';
    @endphp
@endsection



@section('content')
    <section class="bg-home-75vh">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="p-3 bg-white rounded shadow form-signin">
                        <a href="{{ route('index') }}">
                            <img src="{{ getBackendLogo(getSetting('app_logo')) }}"
                                class="avatar-small mb-4 d-block mx-auto" height="100px" width="100px" alt="">
                        </a>
                        <h5 class="mb-3 text-center">Password recovery</h5>
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-12 col-form-label text-md-start"> @lang('Email Address') </label>

                                <div class="col-md-12">
                                    <input readonly id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-12 col-form-label text-md-start"> @lang('Password') </label>

                                <div class="col-md-12">
                                    <input id="password" placeholder="Password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="new-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm"
                                    class="col-md-12 col-form-label text-md-start">@lang('Confirm Password') </label>

                                <div class="col-md-12">
                                    <input id="password-confirm" placeholder="Confirm Password" type="password"
                                        class="form-control" name="password_confirmation" required
                                        autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-12 ">
                                    <button type="submit" class="btn w-100 btn-primary">
                                        @lang('Change Password') 
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
