@extends('layouts.user')

@section('meta_data')
    @php
    $meta_title = 'Security | ' . getSetting('app_name');
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
<style>
    ::placeholder {
        color: #999;
        /* Light grey color */
    }

    .password-toggle-span {
        position: absolute;
        top: 29% !important;
        transform: translateY(-50%);
        right: 1.75rem !important;
        cursor: pointer;
        font-size: 0.9rem;
        color: #959ca9;
    }

    .password-confirm-toggle {
        position: absolute;
        top: 64% !important;
        transform: translateY(-50%);
        right: 1.75rem !important;
        cursor: pointer;
        font-size: 0.9rem;
        color: #959ca9;
    }
</style>

@endpush

<div class="row">
    <div class="main-card">
        @include('panel.user.include.message')
        @php
        $sectionHeader = [
        'headline' => 'Change Password',
        'sub_headline' => 'change anytime',
        ];
        @endphp
        @include('panel.user.partials.section_header')
        <div class="col-lg-6">
            <form action="{{ route('panel.user.setting.update-password') }}" method="post">
                @csrf
                <div class="card">
                    <div class="crad-body p-4">
                        <div class="col-sm-12">
                            <label for="NewPassword">New Password</label>
                            <div class="password-field mb-3">
                                <input type="password" class="form-control p-2" id="new_Password"
                                    placeholder="Enter your new password" name="password" required>
                                <span class="password-toggle-span"><i class="uil uil-eye"></i></span>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <label for="ConfirmPassword">Confirm Password</label>
                            <div class="confirm-password-field mb-3">
                                <input type="password" class="form-control p-2" placeholder="Confirm your new password"
                                    id="confirm_Password" name="confirm_password" required>
                                <span class="password-confirm-toggle"><i class="uil uil-eye"></i></span>
                            </div>
                        </div>
                        <div class="col-12" id="savePasswordButtonContainer">
                            <button class="btn btn-primary btn-sm float-end">Save password</button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
{{-- START DELEGATE ACCESS INIT --}}
<script>
    function copyCodeText(element) {
        var $copyText = document.getElementById(element).innerText;
        var button = document.getElementById(element + '-button');
        var svgIcon =
            'Copy DAC <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24.000000 24.000000"preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,24.000000) scale(0.100000,-0.100000)"fill="#000000" stroke="none"><path d="M17 223 c-11 -11 -8 -151 4 -158 16 -10 24 4 11 17 -16 16 -15 111 0 126 15 15 110 16 126 0 13 -13 27 -5 17 11 -7 12 -147 15 -158 4z"/><path d="M64 167 c-3 -8 -4 -45 -2 -83 l3 -69 80 0 80 0 0 80 0 80 -78 3 c-59 2 -79 0 -83 -11z m146 -72 l0 -65 -65 0 -65 0 0 65 0 65 65 0 65 0 0 -65z"/></g></svg>';

        navigator.clipboard.writeText($copyText).then(function() {
            button.innerHTML = 'Copied';
            button.disabled = true;

            setTimeout(function() {
                button.disabled = false;
                button.style.cssText = "";
                button.innerHTML = svgIcon;
            }, 200);
        }, function() {
            button.style.cssText = "background-color: var(--red);";
            button.innerText = 'Error';
        });
    }
    $(document).ready(function() {
        $('.confirm-password-field').on('click',function(){
            var passInput = $(this).parent().find('input[type="password"]');
            var passConfirmToggle = $(this).parent().find('.password-confirm-toggle > i');
            passConfirmToggle.click(function() {
                togglePasswordVisibility(passInput, passConfirmToggle);
            });
        })

        $('.password-field').on('click',function(){
            var passInput = $(this).parent().find('input[type="password"]');
            console.log(passInput);
            var passToggle = $(this).parent().find('.password-toggle-span > i');
            console.log(passToggle);
            passToggle.click(function() {
                togglePasswordVisibility(passInput, passToggle);
            });
        })
        
        function togglePasswordVisibility(inputField, toggleIcon) {
            if (inputField.attr('type') === "password") {
                inputField.attr('type','text');
                toggleIcon.removeClass('uil-eye').addClass('uil-eye-slash');
            } else {
                inputField.attr('type','password');
                toggleIcon.removeClass('uil-eye-slash').addClass('uil-eye');
            }
        }
    });

</script>
{{-- END DELEGATE ACCESS INIT --}}
@endpush
@endsection