@extends('layouts.main')
@section('title', @$label.' Add')
@section('content')
@php
    $breadcrumb_arr = [['name' => $label, 'url' => 'javascript:void(0);', 'class' => ''], ['name' => 'Add' . ' ' . @$label, 'url' => 'javascript:void(0);', 'class' => 'active']];
@endphp


<div class="container-fluid container-fluid-height">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-grid bg-blue"></i>
                    <div class="d-inline">
                        <h5> @lang('admin/ui.create_new')  {{ @$label ?? '--' }}</h5>
                        <span> @lang('admin/ui.add_new') {{ @$label ?? '--' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @include('panel.admin.include.breadcrumb')
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 mx-auto">
            @include('panel.admin.include.message')
            <div class="card ">
                <div class="card-header">
                    <h3> @lang('admin/ui.add') {{ @$label ?? '--' }}</h3>
                </div>
                <div class="card-body p-3">
                    <form action="{{ route('panel.admin.locations.country.store') }}" class="ajaxForm" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip=""
                        regex="" validation="" value="country-create" />

                        <div class="row">
                            <div class="col-md-12 mx-auto">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group {{ @$errors->has('Name') ? 'has-error' : '' }}">
                                            <x-label name="country_name" validation="country_name" tooltip="add_country_name" />
                                    <x-input name="name" placeholder="Enter Name" type="text"
                                        tooltip="add_country_name" regex="name" validation="country_name"
                                        value="{{ old('name') }}"  />

                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group {{ @$errors->has('Name') ? 'has-error' : '' }}">
                                            <x-label name="capital" validation="country_name" tooltip="add_country_capital" />
                                            <x-input name="capital" placeholder="Enter Capital" type="text"
                                                tooltip="add_country_capital" regex="name" validation="country_name"
                                                value="{{ old('capital') }}"  />
                                            
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <div class="form-group {{ @$errors->has('Code') ? 'has-error' : '' }}">
                                            <x-label name="country_code" validation="common_code_2" tooltip="add_country_code" />
                                            <x-input name="iso3" placeholder="Enter Code" type="text"
                                                tooltip="add_country_code" regex="promo_code" validation="common_code_2"
                                                value="{{ old('iso3') }}"  />

                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group {{ @$errors->has('Currency') ? 'has-error' : '' }}">
                                            <x-label name="country_currency" validation="country_currency" tooltip="add_country_code" />
                                            <x-input name="currency" placeholder="Enter Currency" type="text"
                                                tooltip="add_country_code" regex="currency_name" validation="country_currency"
                                                value="{{ old('currency') }}"  />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group {{ @$errors->has('region') ? 'has-error' : '' }}">
                                            <x-label name="region" validation="country_currency" tooltip="add_country_region" />
                                            <x-input name="region" placeholder="Enter Region" type="text"
                                                tooltip="add_country_region" regex="name" validation="country_currency"
                                                value="{{ old('region') }}"  />

                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group {{ @$errors->has('Emoji') ? 'has-error' : '' }}">
                                            <x-label name="emoji_code" validation="common_code_2" tooltip="add_country_emoji" />
                                            <x-input name="emoji" placeholder="Enter Emoji Code" type="text"
                                                tooltip="add_country_emoji" regex="promo_code" validation="common_code_2"
                                                value="{{ old('emoji') }}"  />
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group {{ @$errors->has('phonecode') ? 'has-error' : '' }}">
                                            <x-label name="phone_code" validation="common_code_2" tooltip="add_country_phonecode" />
                                            <x-input name="phonecode" placeholder="Ente Phone Code" type="text"
                                                tooltip="add_country_phonecode" regex="promo_code" validation="common_code_2"
                                                value="{{ old('phonecode') }}"  />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit"
                            class="btn btn-primary floating-btn ajax-btn"> @lang('admin/ui.create') </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script src="{{ asset('backend/plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>
    <script src="{{ asset('admin/js/form-advanced.js') }}"></script>
    <script src="{{ asset('panel/admin/plugins/ckeditor5/ckeditor.js') }}"></script>
    {{-- START AJAX FORM INIT --}}

    <script>
        // STORE DATA USING AJAX
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            var redirectUrl = "{{ url('admin/locations/country') }}";
            var response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);

        })
    </script>
    {{-- END AJAX FORM INIT --}}


    {{-- START FILEMANAGER INIT --}}
    <script>
        var options = {
            filebrowserImageBrowseUrl: "{{ url('/laravel-filemanager?type=Images') }}",
            filebrowserImageUploadUrl: "{{ url('/laravel-filemanager/upload?type=Images&_token=' . csrf_token()) }}",
            filebrowserBrowseUrl: "{{ url('/laravel-filemanager?type=Files') }}",
            filebrowserUploadUrl: "{{ url('/laravel-filemanager/upload?type=Files&_token=' . csrf_token()) }}"
        };
        $(window).on('load', function() {
            CKEDITOR.replace('description', options);
        });
    </script>
    {{-- END FILEMANAGER INIT --}}


    {{-- START JS HELPERS INIT --}}
    <script>
        function slugFunction() {
            var x = document.getElementById("slugInput").value;
            document.getElementById("slugOutput").innerHTML = "{{ url('/article/') }}/" + x;
        }

        function convertToSlug(Text) {
            return Text
                .toLowerCase()
                .replace(/ /g, '-')
                .replace(/[^\w-]+/g, '');
        }
        $(window).on('load', function() {
            CKEDITOR.replace('content', options);
        });
        $('#title').on('keyup', function() {
            $('#slugInput').val(convertToSlug($('#title').val()));
            slugFunction();
        });
    </script>
    {{-- END JS HELPERS INIT --}}
@endpush

