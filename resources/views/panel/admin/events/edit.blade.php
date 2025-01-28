{{--
* Project: Event
* 
* @category ZStarter
* @ref zCRUD GENERATOR 
* 
* @license Proprietary - Unauthorized copying, use, or distribution is strictly prohibited.
* License details: https://www.defenzelite.com/license
* 
* (c) Defenzelite. All rights reserved.
* @contact hq@defenzelite.com
* 
* @version zStarter: 1.1.2
--}}
@extends('layouts.main')
@php
    $occasion = '';
    $occasionBreadcrumb = '';
    if ($occasion_id != null) {
        $occasion = 'occasion | ';
        $occasionBreadcrumb = 'occasion / ';
    }
@endphp
@section('title', $occasion . $event->getPrefix() . ' Event -' . ' Edit')
@section('content')
    @php
        $breadcrumb_arr = [
            ['name' => 'Event', 'url' => route('panel.admin.events.index'), 'class' => ''],
            [
                'name' => $occasionBreadcrumb . ' Edit ' . $event->getPrefix(),
                'url' => 'javascript:void(0);',
                'class' => 'Active',
            ],
        ];
    @endphp
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('panel/admin/plugins/mohithg-switchery/dist/switchery.min.css') }}">
        <style>
            .error {
                color: red;
            }

            .updated-at-floating-btn {
                padding: 8px 12px;
                color: #fff;
                background-color: #80808052;
                /* Blue background */
                border-radius: 20px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
                position: fixed;
                left: 10px;
                bottom: 80px;
            }

            .updated-at-floating-btn:hover {
                background-color: #80808087;
                /* Darker blue on hover */
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
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
                            <h5>@lang('admin/ui.edit') Event </h5>
                            <span>@lang('admin/ui.update_a_record_for')
                                Event</span>
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
                <div class="card ">
                    <div class="card-header">
                        <h3>@lang('admin/ui.update') Event</h3>
                    </div>
                    <div class="card-body">
                        <form class="ajaxForm" action="{{ route('panel.admin.events.update', $event->id) }}" method="post"
                            enctype="multipart/form-data" id="EventForm">
                            @csrf
                            <x-input type="hidden" validation="" value="update" name="request_with" id="request_with"
                                placeholder="Enter Request With" class="form-control" tooltip="add_request_with" />
                            <x-input type="hidden" validation="" value="{{ $event->id }}" name="id" id="id"
                                placeholder="Enter Id" class="form-control" tooltip="add_id" />

                            <div class="row">
                                <div class="col-md-6 col-12">

                                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                        <x-label name="name" validation="common_name" tooltip="add_event_name"
                                            class="" />
                                        <x-input type="text" validation="common_name" value="{{ $event->name }}"
                                            name="name" id="name" placeholder="Enter Name" class="form-control"
                                            tooltip="add_name" />
                                    </div>
                                </div>
                                <div class="col-md-3 col-12">
                                    <div class="form-group {{ $errors->has('icon') ? 'has-error' : '' }}">
                                        <x-label name="icon" validation="common_icon"  tooltip="add_icon"
                                            class="" />
                                        <x-input type="text" validation="common_icon"  value="{{ $event->icon }}"
                                            name="icon" id="icon" placeholder="Enter Icon" class="form-control"
                                            tooltip="add_icon" />
                                    </div>
                                </div>
                                <div class="col-md-3 col-12">

                                    <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
                                        <x-label name="event_date"  tooltip="add_event_date" class="" />
                                        <x-input type="date"  value="{{ $event->date }}" name="date"
                                            id="date" placeholder="Enter Date" class="form-control"
                                            tooltip="event_date" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group {{ $errors->has('date') ? 'has-error' : '' }}">
                                        <x-label name="view_count"  tooltip="add_view_count" class="" />
                                        <x-input type="number"  value="{{ $event->view_count }}" name="view_count"
                                            id="view_count" placeholder="Enter event Date" class="form-control"
                                            tooltip="add_view_count" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="occasion_id" validation="" tooltip="add_occasion_id"
                                            class="" />
                                        @php
                                            $optionsoccasion = App\Models\Occasion::get();
                                        @endphp
                                        <x-select name="occasion_id" optionName="name" value="{{ $event->occasion_id }}"
                                            label="occasion" optionName="name" class=" select2 occasion_id" validation=""
                                            id="occasion_id" valueName="id" payload="" payloadvalue=""
                                            :arr="@$optionsoccasion" />
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
                                            value="{{ $event->keywords }}" id="keywords" class="cyBlogMetaDescription" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 col-12">

                                    <div class="form-group {{ $errors->has('short_description') ? 'has-error' : '' }}">
                                        <x-label name="short_description" validation="" tooltip="add_event_short_description"
                                            class="" />
                                        <textarea type="text" validation="" value="{{ $event->short_description }}" name="short_description" id="short_description"
                                            placeholder="Enter Short Description" class="form-control" tooltip="add_short_description">{{ old('short_description', $event->short_description) }}</textarea>
                                    </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                    <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                        <x-label name="description" validation="" tooltip="add_event_description"
                                            class="" />
                                        <div class="init-ck-editor">
                                            {!! @$event->description ?? '' !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                                        <x-label name="image" validation="" tooltip="add_event_image" class="" />
                                        <x-input type="file" validation="" value="{{ old('date') }}"
                                            name="image" id="image" placeholder="Enter image" class="form-control"
                                            tooltip="add_event_image" />
                                    </div>
                                    @if (@$event->getMedia('image')->count() > 0)
                                        <div class="my-1">
                                            <img id="image_img" src="{{ @$event->getFirstMediaUrl('image') }}"
                                                class="my-1" alt="{{ @$event->title }}"
                                                style="border-radius: 10px;  height:200px;" />
                                            <a href="#" style="position: absolute;"
                                        class="bg-danger p-0 m-0 rounded-circle delete-icon"  data-event-id="{{ @$event->id }}" data-image-type="0"><i class="fa fa-trash px-1 pt-1"></i></a>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6 col-12">
                                    <div class="form-group {{ $errors->has('bannerImage') ? 'has-error' : '' }}">
                                        <x-label name="banner_image" validation="" tooltip="add_event_banner_image"
                                            class="" />
                                        <x-input type="file" validation="" value="{{ old('date') }}"
                                            name="banner_image" id="bannerImage" placeholder="Enter banner image"
                                            class="form-control" tooltip="add_event_bannerImage" />
                                    </div>
                                    @if (@$event->getMedia('banner_image')->count() > 0)
                                        <div class="my-1">
                                            <img id="banner_image_img"
                                                src="{{ @$event->getFirstMediaUrl('banner_image') }}" class="my-1"
                                                alt="{{ @$event->title }}" style="border-radius: 10px;  height:200px; max-width: 400px"/>
                                            <a href="#" style="position: absolute;"
                                        class="bg-danger p-0 m-0 rounded-circle delete-icon" data-event-id="{{ @$event->id }}" data-image-type="1"><i class="fa fa-trash px-1 pt-1"></i></a>
                                        </div>
                                    @endif
                                </div>

                                @php
                                    $arr = ['1' => 'Yes', '0' => 'No'];
                                    $arrayIs = getSelectValues($arr);
                                @endphp
                                <div class="col-md-6 col-12">
                                    <div class="form-group {{ $errors->has('is_predefined_date') ? 'has-error' : '' }}">
                                        <x-label name="is_predefined_date" validation=""
                                            tooltip="add_is_predefined_date" class="" />
                                        <br>
                                        @foreach ($arrayIs as $value => $label)
                                        <span @if ($value == 0)
                                        class="mx-4" 
                                        @endif >

                                            <input type="radio" name="is_predefined_date" value="{{ $value }}"
                                                {{ old('is_predefined_date', $event->is_predefined_date) == $value ? 'checked' : '' }} >
                                            <label>{{ $label }}</label>
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-6 mt-4">
                                    <div class="row">
                                        @php
                                            $featured_arr = ['is_featured'];
                                            $checkbox_arr = ['is_published'];
                                            $ai_enabled_arr = ['is_ai_enabled'];

                                        @endphp
                                        <!-- is_published Checkbox -->
                                        <div class="col-md-4">
                                            <div class="form-group {{ $errors->has('is_published') ? 'has-error' : '' }}">
                                                <x-checkbox name="is_published" class="js-switch switch-input"
                                                    value="{{ $event->is_published }}" type="checkbox" tooltip=""
                                                    id="is_published" :arr="@$checkbox_arr" />
                                                    <x-label name="/" validation="" tooltip="is_published"
                                                    class="" />
                                            </div>

                                        </div>
                                        <!-- is_featured Checkbox -->
                                        <div class="col-md-4">
                                            <div class="form-group {{ $errors->has('is_featured') ? 'has-error' : '' }}">
                                                <x-checkbox name="is_featured" class="js-switch switch-input"
                                                    value="{{ $event->is_featured }}" type="checkbox" tooltip=""
                                                    id="is_featured" :arr="@$featured_arr" />
                                                    <x-label name="/" validation="" tooltip="is_featured"
                                                class="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group {{ @$errors->has('is_ai_enabled') ? 'has-error' : '' }}">
                                                <x-checkbox name="is_ai_enabled" class="js-switch switch-input" value="{{ $event->is_ai_enabled }}"
                                                    type="checkbox" tooltip="" validation="" id="is_ai_enabled"
                                                    :arr="@$ai_enabled_arr" />
                                                <x-label name="/" validation="" tooltip="is_ai_enabled"
                                                    class="" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mx-auto">
                                    <div class="form-group">
                                        <span class="updated-at-floating-btn" title="@lang('admin/ui.last_updated_at')"><i
                                                class="ik ik-clock mr-1"></i>{{ $event->updated_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit"
                                            class="btn btn-primary floating-btn ajax-btn">@lang('admin/ui.save_update')</button>
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
            // Function to preview the new selected image
            function previewImage(event) {
                const input = event.target;
                const preview = document.getElementById('image_img'); // Use the existing img tag

                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        preview.src = e.target.result; // Update the image source with the new file
                    };

                    reader.readAsDataURL(input.files[0]); // Read the file to create the preview
                }
            }

            // Attach the event listener for file input change
            document.addEventListener('DOMContentLoaded', function() {
                const imageInput = document.getElementById('image');
                if (imageInput) {
                    imageInput.addEventListener('change', previewImage);
                }
            });
        </script>
        <script>
            // Function to preview the new selected banner image
            function previewBannerImage(event) {
                const input = event.target;
                const preview = document.getElementById('banner_image_img'); // Use the existing img tag for banner image
        
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
        
                    reader.onload = function(e) {
                        preview.src = e.target.result; // Update the image source with the new file
                    };
        
                    reader.readAsDataURL(input.files[0]); // Read the file to create the preview
                }
            }
        
            // Attach the event listener for file input change
            document.addEventListener('DOMContentLoaded', function () {
                const bannerImageInput = document.getElementById('bannerImage');
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
          <script>
            $(document).on('click', '.delete-icon', function(e) {
                e.preventDefault(); // Prevent the default anchor behavior
                
                var eventId = $(this).data('event-id');
                var imageType = $(this).data('image-type');
                var url = window.location.origin + '/admin/events/image/delete/' + eventId + '/' + imageType; 
                $.ajax({
                    url: url, // Change this to your actual delete URL
                    type: 'get', // Or 'POST' depending on your setup
                    success: function(response) {
                        // alert('Image deleted successfully');
                        if(imageType == 0 || imageType =='0'){

                            $('#image_img').parent().remove();   
                        }else{
                            $('#banner_image_img').parent().remove();
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                        alert('Error deleting image: ' + error);
                    }
                });
            });
        </script>
    @endpush
@endsection
