{{--
* Project: Content
* @category ZStarter
* @ref zCRUD GENERATOR 
* @license Proprietary - Unauthorized copying, use, or distribution is strictly prohibited.
* License details: https://www.defenzelite.com/license
* (c) Defenzelite. All rights reserved.
* @contact hq@defenzelite.com
* @version zStarter: 1.1.2
--}}

@extends('layouts.main')
@section('title', 'Content')
@section('content')
    @php

        $breadcrumb_arr = [
            ['name' => 'Content', 'url' => route('panel.member.contents.index'), 'class' => ''],
            ['name' => 'Add Content', 'url' => 'javascript:void(0);', 'class' => 'active'],
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
                            <h5>@lang('admin/ui.add') Content</h5>
                            <span>@lang('admin/ui.add_a_new_record_for') Content</span>
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
                <div class="card">
                    <div class="card-header">
                        <h3>@lang('admin/ui.create') Content</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('panel.member.contents.store') }}" method="post"
                            enctype="multipart/form-data" class="" id="ContentForm">
                            @csrf
                            <x-input type="hidden" validation="" value="create" name="request_with" id="request_with"
                                placeholder="Enter Request With" class="form-control" tooltip="add_request_with" />
                            <div class="row">
                                <div class="col-md-12 col-12">

                                    <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                        <x-label name="description" validation="add-content-description" tooltip="add_content_description"
                                            class="" />
                                        <x-textarea type="text" validation="add-content-description" value="{{ old('description') }}"
                                            name="description" id="description" placeholder="Enter Description"
                                            class="form-control" tooltip="add_description" /></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="content_category_id" validation="add-content-category" tooltip="add_content_category"
                                            class="" />
                                        @php
                                            $optionsContent = App\Models\ContentCategory::get();
                                        @endphp
                                        <x-select name="content_category_id" optionName="name" value=""
                                            label="Content" optionName="name" class=" select2 content_category"
                                            validation="add-content-category" id="content_category_id" valueName="id" payload=""
                                            payloadvalue="" :arr="@$optionsContent" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="occasion_id" validation="add-content-occasion" tooltip="add_content_occasion"
                                            class="" />
                                        @php
                                            $optionsoccasion = App\Models\Occasion::get();
                                        @endphp
                                        <x-select name="occasion_id" optionName="name" value="" label="occasion"
                                            optionName="name" class=" select2 occasion_id" validation="add-content-occasion" id="occasion_id"
                                            valueName="id" payload="" payloadvalue="" :arr="@$optionsoccasion" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="event_id" validation="add-content-event" tooltip="add_content_event" class="" />
                                        @php
                                            $optionsEvent = App\Models\Event::get();
                                        @endphp
                                        <x-select name="event_id" optionName="name" value="" label="Event"
                                            optionName="name" class=" select2 event_id" validation="add-content-event" id="event_id"
                                            valueName="id" payload="" payloadvalue="" :arr="@$optionsEvent" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="language_id" validation="" tooltip="add_content_language"
                                            class="" />
                                        @php
                                            $optionsLanguage = App\Models\Language::get();
                                        @endphp
                                        <x-select name="language_id" optionName="name" value="" label="Language"
                                            optionName="name" class=" select2 language_id" validation=""
                                            id="language_id" valueName="id" payload="" payloadvalue=""
                                            :arr="@$optionsLanguage" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="sentiment_id" validation="" tooltip="add_content_sentiment"
                                            class="" />
                                        @php
                                            $optionsSentiment = App\Models\Sentiment::get();
                                        @endphp
                                        <x-select name="sentiment_id" optionName="name" value="" label="Sentiment"
                                            optionName="name" class=" select2 sentiment_id" validation=""
                                            id="sentiment_id" valueName="id" payload="" payloadvalue=""
                                            :arr="@$optionsSentiment" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="age_group_id" validation="" tooltip="add_content_age_group"
                                            class="" />
                                        @php
                                            $optionsAge = App\Models\AgeGroup::get();
                                        @endphp
                                        <x-select name="age_group_id" optionName="name" value="" label="Age"
                                            optionName="name" class=" select2 age_group_id" validation=""
                                            id="age_group_id" valueName="id" payload="" payloadvalue=""
                                            :arr="@$optionsAge" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="relation_id" validation="" tooltip="add_content_relation"
                                            class="" />
                                        @php
                                            $optionsRelation = App\Models\Relation::get();
                                        @endphp
                                        <x-select name="relation_id" optionName="name" value="" label="Relation"
                                            optionName="name" class=" select2 relation_id" validation=""
                                            id="relation_id" valueName="id" payload="" payloadvalue=""
                                            :arr="@$optionsRelation" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="gender_specificity_id" validation=""
                                            tooltip="add_content_gender_specificity" class="" />
                                        @php
                                            $optionsGender = App\Models\GenderSpecificity::get();
                                        @endphp
                                        <x-select name="gender_specificity_id" optionName="name" value=""
                                            label="Gender" optionName="name" class=" select2 gender_specificity"
                                            validation="" id="gender_specificity_id" valueName="id" payload=""
                                            payloadvalue="" :arr="@$optionsGender" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="content_length_id" validation="" tooltip="add_content_content_length"
                                            class="" />
                                        @php
                                            $optionsContent = App\Models\ContentLength::get();
                                        @endphp
                                        <x-select name="content_length_id" optionName="name" value=""
                                            label="Content" optionName="name" class=" select2 content_length"
                                            validation="" id="content_length_id" valueName="id" payload=""
                                            payloadvalue="" :arr="@$optionsContent" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="badge_id" validation="" tooltip="add_content_badge" class="" />
                                        @php
                                            $optionsBadge = App\Models\Badge::get();
                                        @endphp
                                        <x-select name="badge_id" optionName="name" value="" label="Badge"
                                            optionName="name" class=" select2 badge_id" validation="" id="badge_id"
                                            valueName="id" payload="" payloadvalue="" :arr="@$optionsBadge" />
                                    </div>
                                </div>

                                <div class="col-md-6 col-12">

                                    <div class="form-group {{ $errors->has('event_date') ? 'has-error' : '' }}">
                                        <x-label name="event_date" validation="" tooltip="add_content_event_date"
                                            class="" />
                                        <x-input type="date" validation="" value="{{ old('event_date') }}"
                                            name="event_date" id="event_date" placeholder="Enter Event Date"
                                            class="form-control" tooltip="add_content_event_date" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="media_type_id" validation="" tooltip="add_content_media_type"
                                            class="" />
                                        @php
                                            $optionsMedia = App\Models\MediaType::get();
                                        @endphp
                                        <x-select name="media_type_id" optionName="name" value="" label="Media"
                                            optionName="name" class=" select2 media_type_id" validation=""
                                            id="media_type_id" valueName="id" payload="" payloadvalue=""
                                            :arr="@$optionsMedia" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group">
                                        <x-label name="countries_id" validation="" tooltip="add_content_countries"
                                            class="" />
                                        @php
                                            $optionsCountries = App\Models\Country::get();
                                        @endphp
                                        <x-select name="countries_id" optionName="name" value="" label="Countries"
                                            optionName="name" class=" select2 countries_id" validation=""
                                            id="countries_id" valueName="id" payload="" payloadvalue=""
                                            :arr="@$optionsCountries" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group {{ $errors->has('bannerImage') ? 'has-error' : '' }}">
                                        <x-label name="card_background" validation="" tooltip="add_card_background_banner_image"
                                            class="" />
                                        <x-input type="file" validation="" value=""
                                            name="card_background" id="card_background" placeholder="Enter banner image" class="form-control"
                                            tooltip="add_card_background_banner_image" />
                                    </div>
                                    <img id="bannerImagePreview" src="#" alt="Banner Image Preview" style="display: none; margin-top: 10px; margin-bottom : 10px; height: 200px;" />
                                </div>
                                @php
                                $arr = ['Yes', 'No'];
                                $arrayIs = getSelectValues($arr);
                            @endphp
                            <div class="col-md-6 col-12">
                                <div class="form-group {{ $errors->has('is_predefined_date') ? 'has-error' : '' }}">
                                    <x-label name="is_predefined_date" validation="" tooltip="add_content_is_predefined_date"
                                        class="" />
                                    <br>
                                    <x-radio name="is_predefined_date" type="radio" value="1" valueName="" class=""
                                        :arr="@$arrayIs" />
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
        
            // Attach the event listener for card_background input
            document.addEventListener('DOMContentLoaded', function () {
                const bannerImageInput = document.getElementById('card_background');
                if (bannerImageInput) {
                    bannerImageInput.addEventListener('change', previewBannerImage);
                }
            });
        </script>
    @endpush
@endsection
