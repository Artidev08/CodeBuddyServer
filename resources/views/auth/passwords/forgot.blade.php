@extends('site.include.assets')
@if (isset($settings['recaptcha']) && $settings['recaptcha'] == 1)
    {!! ReCaptcha::htmlScriptTagJsApi() !!}
@endif


@section('meta_data')
    @php
		$meta_title = 'Forgot Password | '.getSetting('app_name');		
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

@section('content')
    <section class="bg-home-75vh">
        <div class="container">
            <div class="row mt-15">
                <div class="col-lg-7 col-xl-6 col-xxl-5 mx-auto">
                    <div class="card">
                        <div class="card-body p-8 text-center">
                            <div class="form-signin">
                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger alert-dismissible fade show my-1" role="alert">
                                            {{ $error }}
                                            <button type="button" class="btn close" data-dismiss="alert" aria-label="Close">
                                                <span class="">&times;</span>
                                            </button>
                                        </div>
                                    @endforeach
                                @endif
                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf
                                    <a href="{{ url('/') }}">
                                        <img src="{{ getBackendLogo(getSetting('app_logo')) }}" height="100px"
                                            class="avatar-small mb-4 d-block mx-auto" alt="">
                                    </a>
                                    <h3 class="mb-3 text-center mb-7">Reset your password</h3>
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control  @error('email') is-invalid @enderror"
                                            id="floatingInput" placeholder="name@example.com" name="email"
                                            value="{{ old('email') }}" required>
                                        <label for="floatingInput">Email Address</label>
                                    </div>
                                    @error('email')
                                        <span class="invalid-feedback" style="display: block;" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <button class="btn btn-primary rounded-pill btn-login w-100 mb-2" type="submit">Send
                                        Instructions
                                    </button>
                                    <div class="col-12 text-center mt-3">
                                        <p class="mb-0">Already have an account?<a href="{{ route('login', 'user') }}"
                                                class="hover"> Sign In</a></p>
                                        <p class="mb-0 mt-3"><small class="text-dark me-2"></small></p>
                                    </div>
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

            </div>
        </div>
    </section>
@endsection
