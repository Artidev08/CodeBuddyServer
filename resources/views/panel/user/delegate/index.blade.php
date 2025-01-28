@extends('layouts.user')

@section('meta_data')

@php
$meta_title = __('user/ui.delegate_access') . ' | ' . getSetting('app_name');
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

<style>
    .copy-btn {
        font-size: 12px;
        border: none;
        font-weight: 700;

    }
</style>

@section('content')
    <div class="row">
        <div class="main-card">
            @include('panel.user.include.message')
            @php
                $sectionHeader = [
                    'headline' => __('user/ui.delegate_access_code'),
                    'sub_headline' => __('user/ui.delegate_sub_headline'),
                ];
            @endphp
            @include('panel.user.partials.section_header')
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mt-2 mb-2 ">
                            <div class="w-30 w-sm-100 mx-auto d-flex justify-content-center" style="background: #eee">
                                <div>
                                    <h1 class="rounded fw-800" style="letter-spacing: 5px; margin-top: 7px;">
                                        {{ $delegateAccessCode }}
                                    </h1>
                                    <div id="access_code_copy" class="text-monospace d-none">
                                        {{ $delegateAccessCode }}
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center mt-2">
                                    <button id="access_code_copy-button" class="copy-btn text-dark mb-2"
                                        title="Copy Delegate Access Code" onclick="copyCodeText('access_code_copy');"
                                        title="Copy Text">
                                        @lang('user/ui.copy_dac') <i class="ik ik-copy"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                        {{-- <div class="d-flex justify-content-center mt-2">
                            <button id="access_code_copy-button" class="btn btn-light text-dark"
                                title="Copy Delegate Access Code" onclick="copyCodeText('access_code_copy');"
                                title="Copy Text">
                               @lang('user/ui.copy_dac') <i class="ik ik-copy"></i>
                            </button>
                        </div> --}}

                        <a href="{{ route('panel.user.delegate-access.change-code') }}" class="btn btn-primary mb-3"
                            id="resetDACButton" style="left:155px">
                            <i class="ik ik-rotate-cw ml-1"></i>  @lang('user/ui.rest_dac')
                        </a>
                        <br>
                        <span class="text-muted">
                            <strong> <i class="ik ik-alert-triangle"></i>  @lang('user/ui.note') :</strong> @lang('user/ui.dac_note')


                            </span>
                        </div>
                    </div>
                </div>
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
        </script>
        {{-- END DELEGATE ACCESS INIT --}}
    @endpush
@endsection
