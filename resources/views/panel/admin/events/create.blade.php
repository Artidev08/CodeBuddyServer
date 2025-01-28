{{--
* Project: Event
* @category ZStarter
* @ref zCRUD GENERATOR 
* @license Proprietary - Unauthorized copying, use, or distribution is strictly prohibited.
* License details: https://www.defenzelite.com/license
* (c) Defenzelite. All rights reserved.
* @contact hq@defenzelite.com
* @version zStarter: 1.1.2
--}}

@extends('layouts.main')
@php
    $occasion = '';
    $occasionBreadcrumb = '';
    if ($occasion_id != null) {
            $occasion = 'occasion ';
            $occasionBreadcrumb = 'occasion / ';
        }
@endphp
@section('title',$occasion. ' Event - Create')
@section('content')
    @php

        $breadcrumb_arr = [
            ['name' =>$occasionBreadcrumb. ' Event', 'url' => route('panel.admin.events.index'), 'class' => ''],
            ['name' => 'Add Event', 'url' => 'javascript:void(0);', 'class' => 'active'],
        ];
    @endphp
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('panel/admin/plugins/mohithg-switchery/dist/switchery.min.css') }}">
        <style>
            .error {
                color: red;
            }
        </style>
    @endpush
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>@lang('admin/ui.add') Event</h5>
                            <span>@lang('admin/ui.add_a_new_record_for') Event</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb')
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mx-auto">
                <!-- start message area-->
                @include('panel.admin.include.message')
                <!-- end message area-->
                <div class="card">
                    <div class="card-header">
                        <h3>@lang('admin/ui.create') Event</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('panel.admin.events.store') }}" method="post" enctype="multipart/form-data"
                            class="ajaxForm" id="EventForm">
                            @csrf
                            <x-input type="hidden" validation="" value="create" name="request_with" id="request_with"
                                placeholder="Enter Request With" class="form-control" tooltip="add_request_with" />
                            <div class="row">
                                <div class="col-md-6 col-12">

                                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                        <x-label name="name" validation="common_name"  tooltip="add_event_name"
                                            class="" />
                                        <x-input type="text" validation="common_name"  value="{{ old('name') }}"
                                            name="name" id="name" placeholder="Enter Name" class="form-control"
                                            tooltip="add_name" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group {{ $errors->has('icon') ? 'has-error' : '' }}">
                                        <x-label name="icon" validation="common_icon"  tooltip="add_icon"
                                            class="" />
                                        <x-input type="text" validation="common_icon"  value="{{ old('name') }}"
                                            name="icon" id="icon" placeholder="Enter Icon" class="form-control"
                                            tooltip="add_icon" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
                                        <x-label name="event_date" tooltip="add_event_date"
                                            class="" />
                                        <x-input type="date"  value="{{ old('date') }}"
                                            name="date" id="date" placeholder="Enter event Date" class="form-control"
                                            tooltip="event_date" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <x-label name="occasion_id" validation="" tooltip="add_occasion_id"
                                            class="" />
                                        @php
                                            $optionsoccasion = App\Models\Occasion::get();
                                        @endphp
                                        <x-select name="occasion_id" optionName="name" value="{{$occasion_id}}" label="occasion"
                                            optionName="name" class=" select2 occasion_id" validation="" id="occasion_id"
                                            valueName="id" payload="" payloadvalue="" :arr="@$optionsoccasion" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div >
                                        <div>
                                            <x-label name="keywords" validation="" tooltip="add_event_meta_keyword" />
                                        </div>
                                        <div class="form-group" >
                                                <x-input name="keywords"
                                            placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.keywords') }}"
                                            type="text" tooltip="add_event_meta_keyword" regex="item_meta_keywords" validation=""
                                            value="{{ @old('keywords') }}" id="keywords" class="cyBlogMetaDescription" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-12">
                                    <div class="form-group {{ $errors->has('short_description') ? 'has-error' : '' }}">
                                        <x-label name="short_description" validation="" tooltip="add_event_short_description"
                                            class="" />
                                        <textarea type="text" validation="" value="{{ old('short_description') }}"
                                            name="short_description" id="short_description" placeholder="Enter Short Description"
                                            class="form-control" tooltip="add_short_description"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 col-12">
                                    <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                        <x-label name="description" validation="" tooltip="add_event_description"
                                            class="" />
                                        <div class="init-ck-editor"></div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                                        <x-label name="image" validation="" tooltip="add_event_image"
                                            class="" />
                                        {{-- <x-input type="file" validation="" value="{{ old('date') }}"
                                            name="image" id="image" placeholder="Enter image" class="form-control"
                                            tooltip="add_event_image"  onchange="previewImage()"/> --}}
                                            <input type="file" validation="" value="{{ old('date') }}" name="image" id="image" 
                                            placeholder="Enter image" class="form-control" tooltip="add_event_image" />
                                    </div>
                                    <img id="imagePreview" src="#" alt="Image Preview" style="display: none; margin-top: 10px; margin-bottom : 10px; height: 200px;" />
                                </div>
                                
                                <div class="col-md-6 col-12">
                                    <div class="form-group {{ $errors->has('bannerImage') ? 'has-error' : '' }}">
                                        <x-label name="banner_image" validation="" tooltip="add_event_banner_image"
                                            class="" />
                                        <x-input type="file" validation="" value="{{ old('date') }}"
                                            name="banner_image" id="banner_image" placeholder="Enter banner image" class="form-control"
                                            tooltip="add_event_bannerImage" />
                                    </div>
                                    <img id="bannerImagePreview" src="#" alt="Banner Image Preview" style="display: none; margin-top: 10px; margin-bottom : 10px; height: 200px;" />
                                </div>

                                
                                @php
                                    $arrayIs = ['1' => 'Yes', '0' => 'No'];
                                @endphp
                                <div class="col-md-6 col-12">
                                    <div class="form-group {{ $errors->has('is_predefined_date') ? 'has-error' : '' }}">
                                        <x-label name="is_predefined_date" validation="" tooltip="add_is_predefined_date"
                                            class="" />
                                        <br>
                                        @foreach ($arrayIs as $value => $label)
                                            <label class="mr-4">
                                                <input type="radio" name="is_predefined_date" value="{{ $value }}"
                                                    {{ old('is_predefined_date') == $value ? 'checked' : '' }} />
                                                {{ $label }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-6 mt-3">
                                    <div class="row">
                                        @php
                                            $featured_arr = ['is_featured'];
                                            $checkbox_arr = ['is_published'];
                                            $ai_enabled_arr = ['is_ai_enabled'];

                                        @endphp
                                        <!-- is_published Checkbox -->
                                        <div class="col-md-4">
                                            <div class="form-group {{ @$errors->has('is_published') ? 'has-error' : '' }}">
                                                <x-checkbox name="is_published" class="js-switch switch-input"
                                                    value="1" type="checkbox" tooltip="" validation=""
                                                    id="is_published" :arr="@$checkbox_arr" />
                                                    <x-label name="/" validation="" tooltip="is_published"
                                                    class="" />
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group {{ @$errors->has('is_featured') ? 'has-error' : '' }}">
                                                <x-checkbox name="is_featured" class="js-switch switch-input" value="1"
                                                    type="checkbox" tooltip="" validation="" id="is_featured"
                                                    :arr="@$featured_arr" />
                                                    <x-label name="/" validation="" tooltip="is_featured"
                                                class="" />
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group {{ @$errors->has('is_ai_enabled') ? 'has-error' : '' }}">
                                                <x-checkbox name="is_ai_enabled" class="js-switch switch-input" value="1"
                                                    type="checkbox" tooltip="" validation="" id="is_ai_enabled"
                                                    :arr="@$ai_enabled_arr" />
                                                <x-label name="/" validation="" tooltip="is_ai_enabled"
                                                    class="" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 ml-auto">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary floating-btn ajax-btn">
                                        @lang('admin/ui.create') </button>
                                </div>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- push external js -->
    @push('script')
    <script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/decoupled-document/ckeditor.js"></script>
    <script>
        // Define the previewImage function in the global scope
        function previewImage(event) { 
            const input = event.target;
            const preview = document.getElementById('imagePreview');
    
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block'; // Show the image preview
                };
    
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '#';
                preview.style.display = 'none';
            }
        }
    
        // Attach the event listener once the DOM is loaded
        document.addEventListener('DOMContentLoaded', function () {
            const imageInput = document.getElementById('image');
            if (imageInput) {
                imageInput.addEventListener('change', previewImage);
            }
        });
    </script>
    <!-- JavaScript to preview the banner image -->
<script>
    // Define the function to preview banner image
    function previewBannerImage(event) {
        const input = event.target;
        const preview = document.getElementById('bannerImagePreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block'; // Show the banner image preview
            };

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            preview.style.display = 'none';
        }
    }

    // Attach the event listener for banner_image input
    document.addEventListener('DOMContentLoaded', function () {
        const bannerImageInput = document.getElementById('banner_image');
        if (bannerImageInput) {
            bannerImageInput.addEventListener('change', previewBannerImage);
        }
    });
</script>
        <script>
            $('.ajaxForm').on('submit', function(e) {
                e.preventDefault();
                let route = $(this).attr('action');
                let method = $(this).attr('method');
                let data = new FormData(this);
                ckEditors.forEach((editor, index) => {
                    if(index == 0) {
                        data.append('description', editor.getData());
                    }else{
                        data.append(`editor_content_${index}`, editor.getData());
                    }
                });
                let redirectUrl = "{{ url('admin/events') }}";
                let response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
            })
        </script>
      
         
    @endpush
@endsection
