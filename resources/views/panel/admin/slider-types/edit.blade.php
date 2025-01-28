@extends('layouts.main')
@section('title',  $sliderType->getPrefix().' Slider-type Edit')
@section('content')
@php
    $breadcrumb_arr = [['name' => $label, 'url' => route('panel.admin.slider-types.index'), 'class' => ''],
    ['name' => $sliderType->getPrefix(), 'url' => route('panel.admin.slider-types.index'), 'class' => ''],
    ['name' => 'Edit', 'url' => 'javascript:void(0);', 'class' => 'active']];
@endphp
    
@push('head')
    <link rel="stylesheet" href="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.css') }}">
@endpush

<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-grid bg-blue"></i>
                    <div class="d-inline">
                        <h5>  @lang('admin/ui.edit')  {{ @$label ?? '' }}</h5>

                        <span>  @lang('admin/ui.update_record')  {{ @$label ?? '' }}</span>

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
                    <h3>  @lang('admin/ui.update')  {{ @$label ?? '' }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('panel.admin.slider-types.update', $sliderType->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        
                        <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip=""
                        regex="" validation="" value="update" />
                        <div class="row">
                            <div class="col-md-6 col-6">
                                <div class="form-group {{ @$errors->has('code') ? 'has-error' : '' }}">
                                    <x-label name="code" validation="slider_code" tooltip="edit_slider_types_code" />
                                    <x-input name="code" placeholder="Enter Code" type="text"
                                        tooltip="edit_slider_types_code" hint="Only accept small letters with underscore" regex="slider_code" validation="slider_code"
                                        value="{{ @$sliderType->code }}" readonly="true"/>
                                </div>
                            </div>  
                            <div class="col-md-6 col-6">
                                <div class="form-group {{ @$errors->has('title') ? 'has-error' : '' }}">
                                    <x-label name="headline" validation="slider_headline"
                                            tooltip="add_slider_types_headline" />
                                        <x-input name="title" placeholder="Enter Headline" type="text"
                                            tooltip="add_slider_types_headline" regex="slider_headline" validation="slider_headline"
                                            value="{{ @$sliderType->title }}" />

                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <x-label name="sub_headline" validation="slider_sub_headline" tooltip="add_slider_types_sub_headline" />
                                    <x-textarea rows="3" regex="subject" validation="slider_sub_headline" value="{{ @$sliderType->short_text ?? '' }}" name="short_text"
                                    id="short_text" placeholder="Enter Sub Headline " />
                                    
                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <x-label name="remark"  validation="slider_remark" tooltip="edit_slider_types_remark"/>
                                    <x-textarea rows="3" regex="short_description" validation="slider_remark" value="{{ @$sliderType->remark  }}" name="remark"
                                    id="remark" placeholder="Enter Remark" />
                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <div class="form-group {{ @$errors->has('is_published') ? 'has-error' : '' }}">
                                    @php
                                        $checkbox_arr = ["is_published"];
                                    @endphp
                                <x-checkbox name="is_published"  class="js-switch switch-input"  value="{{ @$sliderType->is_published }}" type="checkbox" tooltip=""  :arr="@$checkbox_arr"/>
                                </div>
                            </div>
                        </div>
                        <button type="submit"
                            class="btn btn-primary floating-btn ajax-btn">  @lang('admin/ui.save_update') </button>
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
    <script  src = "{{ asset('admin/js/ajaxForm.js') }}" ></script>
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
        });

    </script>
    {{-- START AJAX FORM INIT --}}

@endpush
