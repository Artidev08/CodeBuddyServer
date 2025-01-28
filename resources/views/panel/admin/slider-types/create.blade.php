@extends('layouts.main')
@section('title', @$label . ' Add')
@section('content')
    @php
        $breadcrumb_arr = [
            ['name' => 'Slider Group', 'url' => route('panel.admin.slider-types.index'), 'class' => ''],
            ['name' => 'Add Slider Group', 'url' => route('panel.admin.slider-types.index'), 'class' => 'active'],
        ];
    @endphp

    @push('head')
        <link rel="stylesheet" href="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.css') }}">
        <style>
            @media (min-width: 992px) {
                .container-fluid-height {
                    height: 85vh !important;
                }
            }
        </style>
    @endpush

    <div class="container-fluid container-fluid-height" style="height: 100vh !important;">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5> @lang('admin/ui.add') {{ @$label ?? '' }}</h5>

                            <span> @lang('admin/ui.create_record') {{ @$label ?? '' }}</span>

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
                <!-- start message area-->
                @include('panel.admin.include.message')
                <!-- end message area-->
                <div class="card ">
                    <div class="card-header">
                        <h3> @lang('admin/ui.create') {{ @$label ?? '' }}</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('panel.admin.slider-types.store') }}" method="post"
                            enctype="multipart/form-data" class="ajaxForm">
                            @csrf

                            <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip=""
                                regex="" validation="" value="create" />
                            <div class="row">
                                <div class="col-md-6 col-6">
                                    <div class="form-group {{ @$errors->has('code') ? 'has-error' : '' }}">
                                        <x-label name="code" validation="slider_headline"
                                            tooltip="add_slider_types_code" />
                                        <x-input name="code" placeholder="Enter Code" type="text"
                                            tooltip="add_slider_types_code" regex="slider_code"
                                            validation="slider_headline" value="{{ old('code') }}" />

                                    </div>
                                </div>
                                <div class="col-md-6 col-6">
                                    <div class="form-group {{ @$errors->has('title') ? 'has-error' : '' }}">
                                        <x-label name="headline" validation="slider_headline"
                                            tooltip="add_slider_types_headline" />
                                        <x-input name="title" placeholder="Enter Headline" type="text"
                                            tooltip="add_slider_types_headline" regex="slider_headline" validation="slider_headline"
                                            value="{{ old('title') }}" />
                                    </div>
                                </div>

                                <div class="col-md-12 col-12">
                                    <div class="form-group">
                                        <x-label name="sub_headline" validation="slider_sub_headline"
                                            tooltip="add_slider_types_sub_headline" />
                                        <x-textarea rows="3" regex="subject" validation="slider_sub_headline"
                                            value="{{ old('short_text') }}" name="short_text" id="short_text"
                                            placeholder="Enter Sub Headline " />
                                    </div>
                                </div>
                                <div class="col-md-12 col-12">
                                    <div class="form-group">
                                        <x-label name="remark" validation="slider_remark"
                                            tooltip="add_slider_types_remark" />
                                        <x-textarea rows="3" regex="short_description" validation="slider_remark"
                                            value="{{ old('remark') }}" name="remark" id="remark"
                                            placeholder="Enter Remark" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-6">
                                    <div class="form-group {{ @$errors->has('is_published') ? 'has-error' : '' }}">
                                        @php
                                            $checkbox_arr = ['is_published'];
                                        @endphp
                                        <x-checkbox name="is_published" class="js-switch switch-input" value="1"
                                            type="checkbox" tooltip="" :arr="@$checkbox_arr" />

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group {{ @$errors->has('is_permanent') ? 'has-error' : '' }}">
                                        @php
                                            $isPermanent_arr = ['Yes', 'No'];
                                        @endphp
                                        <x-label name="is_permanent" validation="slider_permanent"
                                            tooltip="add_slider_types_permanent" />
                                        <x-radio name="is_permanent" type="radio" value="{{ old('is_permanent') ?? 1 }}"
                                            :arr="@$isPermanent_arr" />
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary floating-btn ajax-btn"> @lang('admin/ui.create')
                                </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script src="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>
    {{-- START CKEDITOR INIT --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/34.2.0/classic/ckeditor.js"></script>
    <script>
        let editor;
        $(window).on('load', function() {
            $('#remarkType').on('change', function() {
                var type = $(this).val();
                if (type == 2) {
                    $('#txt_area').addClass('ck-editor');
                    ClassicEditor
                        .create(document.querySelector('.ck-editor'), {
                            ckfinder: {
                                uploadUrl: "{{ route('panel.admin.media.ckeditor.upload') . '?_token=' . csrf_token() }}",
                            }
                        })
                        .then(newEditor => {
                            editor = newEditor;
                        })
                        .catch(error => {

                        });

                } else {
                    $('#content-holder').html('');
                    $('#content-holder').html(
                        ' <textarea  class="form-control" name="description" id="txt_area" placeholder="Enter Description"></textarea>'
                    );
                }
            });
        });
    </script>
    {{-- END CKEDITOR INIT --}}

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
            var redirectUrl = "{{ url('admin/slider-types') }}";
            var response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
        })
    </script>
    {{-- END AJAX FORM INIT --}}
@endpush
