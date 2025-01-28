{{--
* Project: Content
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
@section('title', 'Content' . ' Edit')
@section('content')
    @php
        $breadcrumb_arr = [
            ['name' => 'Content', 'url' => route('panel.member.contents.index'), 'class' => ''],
            ['name' => 'Edit ' . $content->getPrefix(), 'url' => 'javascript:void(0);', 'class' => 'Active'],
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
                            <h5>@lang('admin/ui.edit') Content </h5>
                            <span>@lang('admin/ui.update_a_record_for')
                                Content</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.member.include.breadcrumb')
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mx-auto">
                <!-- start message area-->
                @include('panel.member.include.message')
                <!-- end message area-->
                <div class="card ">
                    <div class="card-header">
                        <h3>@lang('admin/ui.update') Content</h3>
                    </div>
                    <div class="card-body">
                        <form class="ajaxForm" action="{{ route('panel.member.contents.update', $content->id) }}"
                            method="post" enctype="multipart/form-data" id="ContentForm">
                            @csrf
                            <x-input type="hidden" validation="" value="update" name="request_with" id="request_with"
                                placeholder="Enter Request With" class="form-control" tooltip="add_request_with" />
                            <x-input type="hidden" validation="" value="{{ $content->id }}" name="id" id="id"
                                placeholder="Enter Id" class="form-control" tooltip="add_id" />

                            <div class="row">
                                <div class="col-md-12 col-12">

                                    <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                        <x-label name="description" validation="add-content-description"
                                            tooltip="add_content_description" class="" />
                                        <x-textarea type="text" validation="add-content-description"
                                            value="{{ $content->description }}" name="description" id="description"
                                            placeholder="Enter Description" class="form-control"
                                            tooltip="add_description" /></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="content_category_id" validation="add-content-category"
                                            tooltip="add_content_category" class="" />
                                        @php
                                            $optionsContent = App\Models\ContentCategory::get();
                                        @endphp
                                        <x-select name="content_category_id" optionName="name"
                                            value="{{ $content->content_category_id }}" label="Content" optionName="name"
                                            class=" select2 content_category_id" validation="add-content-event"
                                            id="content_category_id" valueName="id" payload="" payloadvalue=""
                                            :arr="@$optionsContent" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="occasion_id" validation="add-content-occasion"
                                            tooltip="add_content_occasion" class="" />
                                        @php
                                            $optionsoccasion = App\Models\Occasion::get();
                                        @endphp
                                        <x-select name="occasion_id" optionName="name" value="{{ $content->occasion_id }}"
                                            label="occasion" optionName="name" class=" select2 occasion_id"
                                            validation="add-content-occasion" id="occasion_id" valueName="id" payload=""
                                            payloadvalue="" :arr="@$optionsoccasion" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="event_id" validation="add-content-event" tooltip="add_content_event"
                                            class="" />
                                        @php
                                            $optionsEvent = App\Models\Event::get();
                                        @endphp
                                        <x-select name="event_id" optionName="name" value="{{ $content->event_id }}"
                                            label="Event" optionName="name" class=" select2 event_id"
                                            validation="add-content-event" id="event_id" valueName="id" payload=""
                                            payloadvalue="" :arr="@$optionsEvent" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <x-label name="share_count" validation="" tooltip="add_share_count"
                                            class="" />
                                        <x-input type="number" validation="add-content-description"
                                            value="{{ $content->share_count }}" name="share_count" id="share_count"
                                            placeholder="Enter share_count" class="form-control"
                                            tooltip="add_share_count" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="language_id" validation="" tooltip="add_content_language"
                                            class="" />
                                        @php
                                            $optionsLanguage = App\Models\Language::get();
                                        @endphp
                                        <x-select name="language_id" optionName="name"
                                            value="{{ $content->language_id }}" label="Language" optionName="name"
                                            class=" select2 language_id" validation="" id="language_id" valueName="id"
                                            payload="" payloadvalue="" :arr="@$optionsLanguage" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="sentiment_id" validation="" tooltip="add_content_sentiment"
                                            class="" />
                                        @php
                                            $optionsSentiment = App\Models\Sentiment::get();
                                        @endphp
                                        <x-select name="sentiment_id" optionName="name"
                                            value="{{ $content->sentiment_id }}" label="Sentiment" optionName="name"
                                            class=" select2 sentiment_id" validation="" id="sentiment_id"
                                            valueName="id" payload="" payloadvalue="" :arr="@$optionsSentiment" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="age_group_id" validation="" tooltip="add_content_age_group"
                                            class="" />
                                        @php
                                            $optionsAge = App\Models\AgeGroup::get();
                                        @endphp
                                        <x-select name="age_group_id" optionName="name"
                                            value="{{ $content->age_group_id }}" label="Age" optionName="name"
                                            class=" select2 age_group_id" validation="" id="age_group_id"
                                            valueName="id" payload="" payloadvalue="" :arr="@$optionsAge" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="relation_id" validation="" tooltip="add_content_relation"
                                            class="" />
                                        @php
                                            $optionsRelation = App\Models\Relation::get();
                                        @endphp
                                        <x-select name="relation_id" optionName="name"
                                            value="{{ $content->relation_id }}" label="Relation" optionName="name"
                                            class=" select2 relation_id" validation="" id="relation_id" valueName="id"
                                            payload="" payloadvalue="" :arr="@$optionsRelation" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="gender_specificity_id" validation=""
                                            tooltip="add_content_gender_specificity" class="" />
                                        @php
                                            $optionsGender = App\Models\GenderSpecificity::get();
                                        @endphp
                                        <x-select name="gender_specificity_id" optionName="name"
                                            value="{{ $content->gender_specificity_id }}" label="Gender"
                                            optionName="name" class=" select2 gender_specificity_id" validation=""
                                            id="gender_specificity_id" valueName="id" payload="" payloadvalue=""
                                            :arr="@$optionsGender" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="content_length_id" validation=""
                                            tooltip="add_content_content_length" class="" />
                                        @php
                                            $optionsContent = App\Models\ContentLength::get();
                                        @endphp
                                        <x-select name="content_length_id" optionName="name"
                                            value="{{ $content->content_length_id }}" label="Content" optionName="name"
                                            class=" select2 content_length_id" validation="" id="content_length_id"
                                            valueName="id" payload="" payloadvalue="" :arr="@$optionsContent" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="badge_id" validation="" tooltip="add_content_badge"
                                            class="" />
                                        @php
                                            $optionsBadge = App\Models\Badge::get();
                                        @endphp
                                        <x-select name="badge_id" optionName="name" value="{{ $content->badge_id }}"
                                            label="Badge" optionName="name" class=" select2 badge_id" validation=""
                                            id="badge_id" valueName="id" payload="" payloadvalue=""
                                            :arr="@$optionsBadge" />
                                    </div>
                                </div>


                                <div class="col-md-6 col-12">

                                    <div class="form-group {{ $errors->has('event_date') ? 'has-error' : '' }}">
                                        <x-label name="event_date" validation="" tooltip="add_content_event_date"
                                            class="" />
                                        <x-input type="date" validation="" value="{{ $content->event_date }}"
                                            name="event_date" id="event_date" placeholder="Enter Event Date"
                                            class="form-control" tooltip="add_event_date" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="media_type_id" validation="" tooltip="add_content_media_type"
                                            class="" />
                                        @php
                                            $optionsMedia = App\Models\MediaType::get();
                                        @endphp
                                        <x-select name="media_type_id" optionName="name"
                                            value="{{ $content->media_type_id }}" label="Media" optionName="name"
                                            class=" select2 media_type_id" validation="" id="media_type_id"
                                            valueName="id" payload="" payloadvalue="" :arr="@$optionsMedia" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="countries_id" validation="" tooltip="add_content_countries"
                                            class="" />
                                        @php
                                            $optionsCountries = App\Models\Country::get();
                                        @endphp
                                        <x-select name="countries_id" optionName="name"
                                            value="{{ $content->countries_id }}" label="Countries" optionName="name"
                                            class=" select2 countries_id" validation="" id="countries_id"
                                            valueName="id" payload="" payloadvalue="" :arr="@$optionsCountries" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group {{ $errors->has('bannerImage') ? 'has-error' : '' }}">
                                        <x-label name="card_background" validation=""
                                            tooltip="add_card_background_banner_image" class="" />
                                        <x-input type="file" validation="" value="{{ old('date') }}"
                                            name="card_background" id="bannerImage" placeholder="Enter banner image"
                                            class="form-control" tooltip="add_card_background_bannerImage" />
                                    </div>
                                    @if (@$content->getMedia('card_background')->count() > 0)
                                        <div class="my-1">
                                            <img id="card_background_img"
                                                src="{{ @$content->getFirstMediaUrl('card_background') }}" class="my-1"
                                                alt="{{ @$content->title }}"
                                                style="border-radius: 10px;  height:200px; max-width: 400px" />
                                            <a href="#" style="position: absolute;"
                                                class="bg-danger p-0 m-0 rounded-circle delete-icon"
                                                data-content-id="{{ @$content->id }}" data-image-type="1"><i
                                                    class="fa fa-trash px-1 pt-1"></i></a>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group {{ $errors->has('is_predefined_date') ? 'has-error' : '' }}">
                                        <x-label name="is_predefined_date" validation=""
                                            tooltip="add_content_is_predefined_date" class="" />
                                        <br>
                                        @php
                                            $arr = ['Yes', 'No'];
                                            $arrayIs = getSelectValues($arr);
                                        @endphp
                                        <x-radio name="is_predefined_date" type="radio"
                                            value="{{ $content->is_predefined_date }}" valueName="" class=""
                                            :arr="@$arrayIs" />
                                    </div>
                                </div>

                                <div class="col-md-12 mx-auto">
                                    <div class="form-group">
                                        <span class="updated-at-floating-btn" title="@lang('admin/ui.last_updated_at')"><i
                                                class="ik ik-clock mr-1"></i>{{ $content->updated_at->diffForHumans() }}</span>
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
        <script>
            $('.ajaxForm').on('submit', function(e) {
                e.preventDefault();
                let route = $(this).attr('action');
                let method = $(this).attr('method');
                let data = new FormData(this);
                let redirectUrl = "{{ url('admin/contents') }}";
                let response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
            })
        </script>
        <script>
            // Function to preview the new selected banner image
            function previewBannerImage(event) {
                const input = event.target;
                const preview = document.getElementById('card_background_img'); // Use the existing img tag for banner image

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
                const bannerImageInput = document.getElementById('bannerImage');
                if (bannerImageInput) {
                    bannerImageInput.addEventListener('change', previewBannerImage);
                }
            });
        </script>
        <script>
            $(document).on('click', '.delete-icon', function(e) {
                e.preventDefault(); // Prevent the default anchor behavior

                var contentId = $(this).data('content-id');
                var imageType = $(this).data('image-type');
                var url = window.location.origin + '/admin/contents/image/delete/' + contentId + '/' + imageType;
                $.ajax({
                    url: url, // Change this to your actual delete URL
                    type: 'get', // Or 'POST' depending on your setup
                    success: function(response) {
                        // alert('Image deleted successfully');
                        if (imageType == 0 || imageType == '0') {

                            $('#image_img').parent().remove();
                        } else {
                            $('#card_background_img').parent().remove();
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
