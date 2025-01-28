@extends('layouts.empty')
@section('content')
    <section class="bg-home d-flex align-items-center">
        <div class="bg-overlay bg-overlay-white">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="form-signin p-4 bg-white rounded shadow">
                        <form action="{{ route('signup-validate') }}" method="post">
                            @csrf
                            <input type="hidden" name="phone" value="{{ $phone }}">
                            <a href="{{ url('/') }}">
                                <img src="{{ getBackendLogo(getSetting('app_logo')) }}"
                                    class="avatar avatar-small mb-4 d-block mx-auto" alt=""
                                    style="height:100%;width:20%;">
                            </a>
                            <ul class="steper mb-4">
                                <hr class="step-hr">
                                <li><a href="#"><i class="mdi mdi-check"></i>
                                        <p class="mb-0">OTP Verified</p>
                                    </a></li>
                                <li><a href="#"><i class="mdi mdi-check" style="background:#6666CC !important"></i>
                                        <p class="mb-0">Register</p>
                                    </a></li>
                                <li style="padding-right:0"><a href="#"><i class="mdi mdi-check"
                                            style="color:#fff!important;"></i>
                                        <p class="mb-0" style="color:#444!important;">Finish</p>
                                    </a></li>

                            </ul>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="mb-2">
                                        <input type="text" pattern="[a-zA-Z]+.*"
                                            title="Please enter first letter alphabet and at least one alphabet character is required."required
                                            name="first_name" class="form-control" id="first_name" placeholder="First Name"
                                            value="{{ old('first_name') }}">
                                    </div>
                                    <div class="mb-2">
                                        <input type="text" pattern="[a-zA-Z]+.*"
                                            title="Please enter first letter alphabet and at least one alphabet character is required."
                                            required name="last_name" class="form-control" id="last_name"
                                            placeholder="Last Name" value="{{ old('last_name') }}">
                                    </div>
                                    <div class="mb-2">
                                        <input type="email" pattern="[a-zA-Z]+.*"
                                            title="Please enter first letter alphabet and at least one alphabet character is required."
                                            required name="email" class="form-control" id="Email"
                                            placeholder="Enter Email" value="{{ old('email') }}">
                                    </div>
                                    <div class="mb-2">
                                        <input type="password" pattern="[a-zA-Z]+.*"
                                            title="Please enter first letter alphabet and at least one alphabet character is required."
                                            required name="password" class="form-control" id="Password"
                                            placeholder="Enter Password" value="{{ old('password') }}">
                                    </div>
                                    <div class="form-check mb-3">
                                        <input required class="form-check-input" type="checkbox" id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault">I accept <a
                                                href="{{ url('page/terms') }}" class="text-primary">Terms And
                                                Conditions</a></label>
                                    </div>
                                    <button class="btn btn-primary w-100" type="submit">Create Account</button>
                                </div>
                            </div>
                            <p class="mb-0 text-muted mt-3 text-center" style="position:relative">
                                {{ getSetting('frontend_copyright_text') }}
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div> <!--end container-->
    </section>
@endsection
