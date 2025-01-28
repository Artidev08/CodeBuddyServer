@extends('layouts.main')
@section('title', @$label . ' Add')
@section('content')
    @php
        @$breadcrumb_arr = [
            ['name' => __('admin/ui.enquiry'), 'url' => route('panel.admin.website-enquiries.index'), 'class' => ''],
            ['name' => __('admin/ui.add'), 'url' => route('panel.admin.website-enquiries.create'), 'class' => 'active'],
        ];

    @endphp
    @push('head')
        <style>
            @media (min-width: 992px) {
                .container-fluid-height {
                    height: 83vh;
                }
            }

            .iti--inline-dropdown .iti__dropdown-content {
                z-index: 9 !important;
            }
        </style>
    @endpush

    <div class="container-fluid container-fluid-height">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5> @lang('admin/ui.create_new') {{ @$label ?? '' }}</h5>
                            <span> @lang('admin/ui.add_a_new_record_for') {{ @$label ?? '' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb')
                </div>
            </div>
        </div>
        <form action="{{ route('panel.admin.website-enquiries.store') }}" method="post" class="ajaxForm">
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="card ">
                        <div class="card-header">
                            <h3> @lang('admin/ui.new_enquiry') </h3>
                        </div>
                        <div class="card-body">

                            <x-input name="status" placeholder="Enter Name" type="hidden" tooltip="" regex=""
                                validation="" value="0" />
                            <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip=""
                                regex="" validation="" value="create" />
                            <x-input name="type" placeholder="Enter Name" type="hidden" tooltip="" regex=""
                                validation="" value="website-enquiry" />

                            <div class="row">
                                <div class="col-md-12 mx-auto">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group {{ @$errors->has('subject') ? 'has-error' : '' }}">
                                                <x-label name="subject" validation="enquiry_subject"
                                                    tooltip="add_enquiry_subject" />
                                                <x-input name="subject" placeholder="Enter Subject" type="text"
                                                    tooltip="add_enquiry_subject" regex="subject"
                                                    validation="enquiry_subject" value="{{ old('subject') }}" />

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group {{ @$errors->has('description') ? 'has-error' : '' }}">
                                                <x-label name="description" validation="enquiry_description"
                                                    tooltip="add_enquiry_description" />
                                                <x-textarea regex="short_description" validation="enquiry_description"
                                                    value="{{ old('description') }}" name="description" id="description"
                                                    placeholder="Enter Description" />

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3>@lang('admin/ui.enquiry_person')</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <div class="form-group {{ @$errors->has('name') ? 'has-error' : '' }}">
                                        <x-label name="full_name" validation="enquiry_name" tooltip="add_enquiry_name" />
                                        <x-input name="name" placeholder="Enter Name" type="text"
                                            tooltip="add_enquiry_name" regex="name" validation="common_name"
                                            value="{{ old('name') }}" />


                                    </div>
                                </div>

                                <div class="col-sm-12 col-md-12">
                                    <div class="form-group {{ @$errors->has('Phone') ? 'has-error' : '' }}">
                                        <x-label name="phone" validation="" tooltip="add_enquiry_phone" />
                                        {{-- <x-input name="phone" placeholder="Enter Name" type="number"

                                        tooltip="add_enquiry_phone" regex="" validation=""

                                        value="{{ old('phone') }}" /> --}}
                                        <div class="input-group">
                                            <input type="hidden" id="countryCodeInput" name="country_code" value="">
                                            <input type="tel" class="form-control" id="phone" name="phone"
                                                value="{{ old('phone') }}">
                                        </div>


                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-12">
                                    <div class="form-group {{ @$errors->has('email') ? 'has-error' : '' }}">
                                        <x-label name="email" validation="" tooltip="add_enquiry_email" />
                                        <x-input name="email"
                                            placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.email') }}"
                                            type="email" tooltip="add_enquiry_email" regex="email" validation=""
                                            value="{{ old('email') }}" />

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary floating-btn ajax-btn"> @lang('admin/ui.create') </button>

                </div>
            </div>
        </form>
    </div>

@endsection

@push('script')
    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            if (editor != undefined) {
                const description = editor.getData();
                data.append('value', description);
            }
            var redirectUrl = "{{ url('/admin/website-enquiries') }}";
            var response = postData(method, route, 'json', data, null, null, '1', true, redirectUrl);
        })
    </script>
    {{-- END AJAX FORM INIT --}}

    {{-- START JS HELPERS INIT --}}
    <script>
        let editor;
        $(window).on('load', function() {
            $('#remarkType').on('change', function() {
                var type = $(this).val();
                if (type == 2) {
                    $('#txt_area').addClass('ck-editor');
                    ClassicEditor
                        .create(document.querySelector('.ck-editor'))
                        .then(newEditor => {
                            editor = newEditor;
                        })
                        .catch(error => {
                            console.error(error);
                        });

                } else {
                    $('#content-holder').html('');
                    $('#content-holder').html(
                        ' <textarea  class="form-control"rows="10" name="description" id="txt_area" placeholder="Enter Description"></textarea>'
                    );
                }
            });
        });
    </script>
    {{-- END JS HELPERS INIT --}}
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
