@extends('layouts.main')
@section('title', __('admin/ui.left_sidebar_basic_details'))
@section('content')
    @push('head')
        <link rel="stylesheet" href="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.css') }}">
    @endpush
    @php
        @$breadcrumb_arr = [
            ['name' => __('admin/ui.left_sidebar_basic_details'), 'url' => 'javascript:void(0);', 'class' => 'active'],
        ];
    @endphp
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('admin/ui.left_sidebar_basic_details') ?? '--' }}</h5>
                            <span style="font-size: 0.75rem;"> @lang('admin/ui.website_page_heading') </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div>
                        @include('panel.admin.include.breadcrumb')
                    </div>
                </div>
                @include('panel.admin.modal.sitemodal', [
                    'title' => 'How to use',
                    'content' =>
                        'You need to create a unique code and call the unique code with paragraph content helper.',
                ])
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="card">
                    <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                        @if (getSetting('control_details_activation', @$setting))
                            <li class="nav-item"><a class="nav-link active" id="pills-profile-tab" data-toggle="pill"
                                    href="#last-month" role="tab" aria-controls="pills-profile" aria-selected="false">
                                    @lang('admin/ui.control_detail') </a>
                            </li>
                        @endif
                        @if (getSetting('custom_style_activation', @$setting))
                            <li class="nav-item"><a data-active="security"
                                    class="nav-link active-swicher @if (request()->has('active') && request()->get('active') == 'security') active @endif"
                                    id="pills-security-tab" data-toggle="pill" href="#security" role="tab"
                                    aria-controls="pills-security" aria-selected="true"> @lang('admin/ui.custom_style') </a>
                            </li>
                        @endif
                        @if (getSetting('custom_script_activation', @$setting))
                            <li class="nav-item"><a data-active="customscript"
                                    class="nav-link active-swicher @if (request()->has('active') && request()->get('active') == 'customscript') active @endif"
                                    id="pills-customscript-tab" data-toggle="pill" href="#customscript" role="tab"
                                    aria-controls="pills-customscript" aria-selected="true"> @lang('admin/ui.custom_script') </a>
                            </li>
                        @endif
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        @if (getSetting('control_details_activation', @$setting))
                            <div class="tab-pane fade show active" id="last-month" role="tabpanel"
                                aria-labelledby="pills-profile-tab">
                                <div class="card-body">
                                    <div class="row gutters-10">
                                        <div class="col-lg-6">
                                            <div class="card shadow-none bg-light">
                                                <div class="card-header dark-theme-bg primary-theme-bg">
                                                    <h6 class="mb-0"> @lang('admin/ui.about_website') </h6>
                                                </div>
                                                <div class="card-body dark-theme-body-bg primary-theme-body-bg">
                                                    <form action="{{ route('panel.admin.setting.store') }}" method="POST"
                                                        enctype="multipart/form-data">
                                                        @csrf

                                                        <x-input name="group_name" placeholder="Enter Name" type="hidden"
                                                            tooltip="" regex="" validation=""
                                                            value="{{ 'website_footer_about' }}" />

                                                        <div class="form-group">
                                                            <x-label name="about_content" validation="about_content"
                                                                tooltip="general_setting_about_content" />
                                                            <x-textarea regex="short_description" validation="about_content"
                                                                value="{{ trim(getSetting('frontend_footer_description', @$setting)) }}"
                                                                name="frontend_footer_description"
                                                                id="frontend_footer_description"
                                                                placeholder="Enter  Content  " />
                                                        </div>
                                                        <div class="form-group">
                                                            <x-label name="map_location"
                                                                validation="common_meta_description"
                                                                tooltip="general_setting_map_location" />
                                                            <x-textarea regex="short_description"
                                                                validation="common_meta_description"
                                                                value="{{ getSetting('frontend_map_code', @$setting) }}"
                                                                name="frontend_map_code" id="frontend_map_code"
                                                                placeholder="Enter Location  " />

                                                        </div>
                                                        <div class="form-group">
                                                            <x-label name="copy_right" validation="common_meta_description"
                                                                tooltip="general_setting_copyright" />
                                                            <x-textarea regex="short_description"
                                                                validation="common_meta_description"
                                                                value="{{ getSetting('frontend_copyright_text', @$setting) }}"
                                                                name="frontend_copyright_text" id="frontend_copyright_text"
                                                                placeholder="Enter Copyright  " />

                                                        </div>

                                                        <div class="text-right ajax-btn">
                                                             <button type="submit"
                                            class="btn btn-primary floating-btn ajax-btn">@lang('admin/ui.save_update')</button>
                                                            
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-lg-6">
                                            <div class="card shadow-none bg-light dark-theme-bg">
                                                <div class="card-header primary-theme-bg">
                                                    <h6 class="mb-0"> @lang('admin/ui.bussiness_address') </h6>
                                                </div>
                                                <div class="card-body dark-theme-body-bg primary-theme-body-bg">
                                                    <form action="{{ route('panel.admin.setting.store') }}" method="POST"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        {{-- <input type="hidden" name="group_name"
                                                        value="{{ 'website_footer_contact' }}"> --}}
                                                        <x-input name="group_name" placeholder="Enter Name"
                                                            type="hidden" tooltip="" regex="" validation=""
                                                            value="{{ 'website_footer_contact' }}" />

                                                        <div class="form-group">
                                                            <x-label name="primary_address" validation="common_address"
                                                                tooltip="general_setting_primary_address" />
                                                            <x-textarea regex="short_description"
                                                                validation="common_address"
                                                                value="{{ getSetting('frontend_footer_address', @$setting) }}"
                                                                name="frontend_footer_address"
                                                                id="frontend_footer_address"
                                                                placeholder="Enter Primary Address " />


                                                        </div>
                                                        <div class="form-group">
                                                            <x-label name="secondary_address" validation="common_address"
                                                                tooltip="general_setting_secondary_address" />
                                                            <x-textarea regex="short_description"
                                                                validation="common_address"
                                                                value="{{ getSetting('frontend_footer_address_secondary', @$setting) }}"
                                                                name="frontend_footer_address_secondary"
                                                                id="frontend_footer_address_secondary"
                                                                placeholder="Enter  Secondary Address " />

                                                        </div>
                                                        <div class="form-group">
                                                            <x-label name="primary_number"
                                                                validation="common_phone_number"
                                                                tooltip="general_setting_primary_number" />
                                                            <x-textarea regex="phone_number"
                                                                validation="common_phone_number"
                                                                value="{{ getSetting('frontend_footer_phone', @$setting) }}"
                                                                name="frontend_footer_phone" id="frontend_footer_phone"
                                                                placeholder="Enter Primary Number " />
                                                            {{-- <div class="input-group">
                                                                    <input type="hidden" id="countryCodeInput" name="country_code" value="">
                                                                    <input type="tel" style="width: 19.5rem !important;"class="form-control"
                                                                        id="phone" name="phone" value="{{ $getSetting->fullPhone() }}">
                                                                </div> --}}
                                                        </div>
                                                        <div class="form-group">
                                                            <x-label name="secondary_number"
                                                                validation="common_phone_number"
                                                                tooltip="general_setting_secondary_number" />
                                                            <x-textarea regex="phone_number"
                                                                validation="common_phone_number"
                                                                value="{{ getSetting('frontend_footer_phone', @$setting) }}"
                                                                name="frontend_footer_phone" id="frontend_footer_phone"
                                                                placeholder="Enter Primary Number " />

                                                        </div>
                                                        <div class="form-group">
                                                            <x-label name="primary_email" validation="common_email"
                                                                tooltip="general_setting_primary_email" />
                                                            <x-input name="frontend_footer_email"
                                                                placeholder="Enter Email" type="text"
                                                                tooltip="general_setting_primary_email" regex="email"
                                                                validation="common_email"
                                                                value="{{ getSetting('frontend_footer_email', @$setting) }}" />

                                                        </div>
                                                        <div class="text-right ajax-btn">
                                                             <button type="submit"
                                            class="btn btn-primary floating-btn ajax-btn">@lang('admin/ui.save_update')</button>
                                                          
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <form action="{{ route('panel.admin.setting.store') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf

                                                <x-input name="group_name" placeholder="Enter Name" type="hidden"
                                                    tooltip="" regex="" validation=""
                                                    value="{{ 'website_footer_bottom' }}" />

                                                <div class="card-body p-0">

                                                    <div class="card shadow-none bg-light">
                                                        <div class="card-header dark-theme-bg primary-theme-bg">
                                                        </div>
                                                        <div class="card-body dark-theme-body-bg primary-theme-body-bg">
                                                            <div class="form-group">
                                                                <div class="input-group form-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i
                                                                                class="ik ik-facebook"></i></span>
                                                                    </div>
                                                                    <input type="url" class="form-control"
                                                                        placeholder="http://" name="facebook_link"
                                                                        value="{{ getSetting('facebook_link', @$setting) }}">
                                                                </div>
                                                                <div class="input-group form-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text">
                                                                            <i
                                                                                class="fa-brands fa-x-twitter text-color-white"></i></span>
                                                                    </div>
                                                                    <input type="url" class="form-control"
                                                                        placeholder="http://" name="twitter_link"
                                                                        value="{{ getSetting('twitter_link', @$setting) }}">
                                                                </div>
                                                                <div class="input-group form-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i
                                                                                class="ik ik-instagram"></i></span>
                                                                    </div>
                                                                    <input type="url" class="form-control"
                                                                        placeholder="http://" name="instagram_link"
                                                                        value="{{ getSetting('instagram_link', @$setting) }}">
                                                                </div>
                                                                <div class="input-group form-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i
                                                                                class="ik ik-youtube"></i></span>
                                                                    </div>
                                                                    <input type="url" class="form-control"
                                                                        placeholder="http://" name="youtube_link"
                                                                        value="{{ getSetting('youtube_link', @$setting) }}">
                                                                </div>
                                                                <div class="input-group form-group">
                                                                    <div class="input-group-prepend">
                                                                        <span class="input-group-text"><i
                                                                                class="ik ik-linkedin"></i></span>
                                                                    </div>
                                                                    <input type="url" class="form-control"
                                                                        placeholder="http://" name="linkedin_link"
                                                                        value="{{ getSetting('linkedin_link', @$setting) }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right ajax-btn">
                                                         <button type="submit"
                                            class="btn btn-primary floating-btn ajax-btn">@lang('admin/ui.save_update')</button>
                                                      
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="tab-pane fade @if (request()->has('active') && request()->get('active') == 'security') show active @endif" id="security"
                            role="tabpanel" aria-labelledby="pills-security-tab">
                            <div class="card-body">
                                <form action="{{ route('panel.admin.setting.store') }}" method="POST"
                                    enctype="multipart/form-data" class="ajaxForm">
                                    @csrf
                                    <x-input name="active" placeholder="Enter Name" type="hidden" tooltip=""
                                        regex="" validation="" value="{{ 'security' }}" />
                                    <x-input name="group_name" placeholder="Enter Name" type="hidden" tooltip=""
                                        regex="" validation="" value="{{ 'appearance_custom_style' }}" />

                                    {{-- <input type="hidden" name="active" value="{{ 'security' }}"> --}}
                                    {{-- <input type="hidden" name="group_name" value="{{ 'appearance_custom_style' }}"> --}}
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label"> @lang('admin/ui.header_custom') </label>
                                        <div class="col-md-8">
                                            <textarea name="custom_header_style" rows="4" class="form-control" placeholder="<style>&#10;...&#10;</style>">{{ getSetting('custom_header_style', @$setting) }}</textarea>
                                            <small class="text-color-white"> @lang('admin/ui.write_style') </small>
                                        </div>
                                    </div>

                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary ajax-btn"> @lang('admin/ui.update')
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade @if (request()->has('active') && request()->get('active') == 'customscript') show active @endif" id="customscript"
                            role="tabpanel" aria-labelledby="pills-customscript-tab">
                            <div class="card-body">
                                <form action="{{ route('panel.admin.setting.store') }}" method="POST"
                                    enctype="multipart/form-data" class="ajaxForm">
                                    @csrf
                                    <x-input name="active" placeholder="Enter Name" type="hidden" tooltip=""
                                        regex="" validation="" value="{{ 'customscript' }}" />
                                    {{-- <input type="hidden" name="active" value="{{ 'customscript' }}"> --}}
                                    {{-- <input type="hidden" name="group_name" value="{{ 'appearance_custom_script' }}"> --}}
                                    <x-input name="group_name" placeholder="Enter Name" type="hidden" tooltip=""
                                        regex="" validation="" value="{{ 'appearance_custom_script' }}" />

                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label"> @lang('admin/ui.header_custom') </label>
                                        <div class="col-md-8">
                                            <textarea name="custom_header_script" rows="4" class="form-control" placeholder="<script>
                                                & #10;...&# 10;
                                            </script>">{{ getSetting('custom_header_script', @$setting) }}</textarea>
                                            <small class="text-color-white"> @lang('admin/ui.write_script') </small>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label"> @lang('admin/ui.footer_custom') </label>
                                        <div class="col-md-8">
                                            <textarea name="custom_footer_script" rows="4" class="form-control" placeholder="<script>
                                                & #10;...&# 10;
                                            </script>">{{ getSetting('custom_footer_script', @$setting) }}</textarea>
                                            <small class="text-color-white"> @lang('admin/ui.script_tag') </small>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary ajax-btn"> @lang('admin/ui.update')
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script src="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>
        {{-- COUNTRYCODE SELECTOR INIT --}}
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const input = document.querySelector("#phone");
                const countryCodeInput = document.querySelector("#countryCodeInput");

                const iti = window.intlTelInput(input, {
                    initialCountry: "auto",
                    separateDialCode: true,
                    utilsScript: "{{ asset('panel/admin/plugins/country-code/utils.js') }}",
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

@endsection
