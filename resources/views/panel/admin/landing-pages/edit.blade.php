{{--
* Project: Landing Page
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
@section('title', $landingPage->getPrefix(). ' - Landing Page' . ' Edit')
@section('content')
    @php
        $breadcrumb_arr = [
            ['name' => 'Landing Page', 'url' => route('panel.admin.landing-pages.index'), 'class' => ''],
            ['name' => 'Edit ' . $landingPage->getPrefix(), 'url' => 'javascript:void(0);', 'class' => 'Active'],
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
                            <h5>@lang('admin/ui.edit') Landing Page </h5>
                            <span>@lang('admin/ui.update_a_record_for')
                                Landing Page</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb')
                </div>
            </div>
        </div>
        <form class="row ajaxForm" action="{{ route('panel.admin.landing-pages.update', $landingPage->id) }}" method="post"
            enctype="multipart/form-data" id="LandingPageForm">
            @csrf
            <x-input type="hidden" validation="" value="update" name="request_with" id="request_with"
                placeholder="Enter Request With" class="form-control" tooltip="add_request_with" />
            <x-input type="hidden" validation="" value="{{ $landingPage->id }}" name="id" id="id"
                placeholder="Enter Id" class="form-control" tooltip="add_id" />

            <div class="col-md-12">
                <!-- start message area-->
                @include('panel.admin.include.message')
                <!-- end message area-->
            </div>
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h3> @lang('admin/ui.item') @lang('admin/ui.details') </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                                    <x-label name="title" validation="title" tooltip="add_landing_page_title" class="" />
                                    <x-input type="text" validation="title" value="{{ $landingPage->title }}"
                                        name="title" id="title" placeholder="Enter Title" class="form-control"
                                        tooltip="add_landing_page_title" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('slug') ? 'has-error' : '' }}">
                                    <x-label name="slug" validation="item_slug" tooltip="add_landing_page_slug" class="" />
                                    <x-input type="text" validation="item_slug" value="{{ $landingPage->slug }}"
                                        name="slug" id="slug" placeholder="Enter Slug" class="form-control"
                                        tooltip="add_slug" />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <x-label name="short_description" validation="item_short_description"
                                tooltip="add_landing_page_short_description" />
                            <x-textarea regex="short_description" validation="item_short_description"
                                value="{{ $landingPage->short_description }}" name="short_description"
                                id="short_description" placeholder="Enter Short Description" />
                        </div>

                        <div class="form-group">
                            <label for="description" class="control-label" tooltip="add_landing_page_description">
                                @lang('admin/ui.description') @if (@validation('item_sub_category')['pattern']['mandatory'])
                                @endif <span class="text-danger">*</span></label>
                            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                <div id="content-holder">
                                    <div class="init-ck-editor">
                                        {!! @$landingPage->description !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3><x-label name="meta" validation="" tooltip="" /><x-label name="config" validation=""
                                tooltip="" /></h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <x-label name="meta_title" validation="" tooltip="add_landing_page_meta_title" class="" />
                                    </div>
                                    <div>
                                        <button id="auto_fill_title"
                                            class="p-0 btn btn-link btn-sm text-primary float-end fw-800"><i
                                                class="ik ik-corner-left-down"></i> @lang('admin/ui.auto_fill')
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <x-input name="meta_title" placeholder="Enter Meta Title" type="text"
                                        tooltip="add_blog_meta_keywords" regex="item_meta_title" validation=""
                                        value="{{ @$landingPage->meta_title }}" id="meta_title"
                                        class="cyBlogSeoKeywords" />

                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <x-label name="meta_keywords" validation="" tooltip="add_landing_page_meta_keyword" />

                                    <x-input name="meta_keywords"
                                        placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.meta_keyword') }}"
                                        type="text" tooltip="add_item_meta_title" regex="item_meta_keywords" validation=""
                                        value="{{ @$landingPage->meta_keywords}}" id="meta_keywords" class="cyBlogMetaDescription" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <x-label name="meta_descriptions" validation=""
                                            tooltip="add_landing_page_meta_description" />
                                    </div>
                                    <div>
                                        <button id="auto_fill_description"
                                            class="p-0 btn btn-link btn-sm text-primary float-end fw-800"><i
                                                class="ik ik-corner-left-down"></i> @lang('admin/ui.auto_fill')
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <x-textarea regex="short_description" validation=""
                                        value="{{ @$landingPage->meta_description }}" name="meta[meta_description]"
                                        id="meta_description"
                                        placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.meta_description') }}"
                                        rows="2" class="cyBlogMetaDescription" />
                                </div>



                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <div class="col-md-5">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            <h3> @lang('admin/ui.organise') @lang('admin/ui.item') </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 pr-2">

                                <div class="form-group">
                                    <x-label name="content_category_id" validation="" tooltip="add_landing_page_category"
                                        class="" />
                                    @php
                                        $optionsContent = App\Models\ContentCategory::get();
                                    @endphp
                                    <x-select name="content_category_id" optionName="name"
                                        value="{{ $landingPage->content_category_id }}" label="Content"
                                        optionName="name" class=" select2 content_category_id" validation=""
                                        id="content_category_id" valueName="id" payload="" payloadvalue=""
                                        :arr="@$optionsContent" />
                                </div>
                            </div>
                            <div class="col-md-6 pr-2">

                                <div class="form-group">
                                    <x-label name="occasion_id" validation="" tooltip="add_landing_page_occasion"
                                        class="" />
                                    @php
                                        $optionsoccasion = App\Models\Occasion::get();
                                    @endphp
                                    <x-select name="occasion_id" optionName="name"
                                        value="{{ $landingPage->occasion_id }}" label="occasion" optionName="name"
                                        class=" select2 occasion_id" validation="" id="occasion_id" valueName="id"
                                        payload="" payloadvalue="" :arr="@$optionsoccasion" />
                                </div>
                            </div>
                            <div class="col-md-6 pr-2">

                                <div class="form-group">
                                    <x-label name="event_id" validation="" tooltip="add_landing_page_event" class="" />
                                    @php
                                        $optionsEvent = App\Models\Event::get();
                                    @endphp
                                    <x-select name="event_id" optionName="name" value="{{ $landingPage->event_id }}"
                                        label="Event" optionName="name" class=" select2 event_id" validation=""
                                        id="event_id" valueName="id" payload="" payloadvalue=""
                                        :arr="@$optionsEvent" />
                                </div>
                            </div>
                            <div class="col-md-6 pr-2">

                                <div class="form-group">
                                    <x-label name="language_ids" validation="" tooltip="add_landing_page_language"
                                        class="" />
                                    @php
                                        $optionsLanguage = App\Models\Language::get();
                                        $selectedLanguages = old('language_ids', $landingPage->language_ids ?? []);
                                        // Ensure $selectedLanguages is an array
                                        $selectedLanguages = is_array($selectedLanguages)
                                            ? $selectedLanguages
                                            : explode(',', $selectedLanguages);
                                    @endphp
                                    <select name="language_ids[]" id="language_ids"
                                        class="form-control select2 language_ids" multiple="multiple">
                                        @foreach ($optionsLanguage as $language)
                                            <option value="{{ $language->id }}"
                                                {{ in_array($language->id, $selectedLanguages) ? 'selected' : '' }}>
                                                {{ $language->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-6 pr-2">
                                <div class="form-group">
                                    <x-label name="sentiment_ids" validation="" tooltip="add_landing_page_sentiment"
                                        class="" />
                                    @php
                                        $optionsSentiment = App\Models\Sentiment::get();
                                        $selectedSentiments = old('sentiment_ids', $landingPage->sentiment_ids ?? []);
                                        $selectedSentiments = is_array($selectedSentiments)
                                            ? $selectedSentiments
                                            : explode(',', $selectedSentiments);
                                    @endphp
                                    <select name="sentiment_ids[]" id="sentiment_ids"
                                        class="form-control select2 sentiment_ids" multiple="multiple">
                                        @foreach ($optionsSentiment as $sentiment)
                                            <option value="{{ $sentiment->id }}"
                                                {{ in_array($sentiment->id, $selectedSentiments) ? 'selected' : '' }}>
                                                {{ $sentiment->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-6 pr-2">

                                <div class="form-group">
                                    <x-label name="age_group_ids" validation="" tooltip="add_landing_page_age_group"
                                        class="" />
                                    @php
                                        // Fetch age group options from the model
                                        $optionsAge = App\Models\AgeGroup::get();

                                        // Handle old values and model values
                                        $selectedAgeGroups = old('age_group_ids', $landingPage->age_group_ids ?? []);

                                        // Ensure $selectedAgeGroups is an array
                                        $selectedAgeGroups = is_array($selectedAgeGroups)
                                            ? $selectedAgeGroups
                                            : explode(',', $selectedAgeGroups);
                                    @endphp
                                    <select name="age_group_ids[]" id="age_group_ids"
                                        class="form-control select2 age_group_ids" multiple="multiple">
                                        @foreach ($optionsAge as $ageGroup)
                                            <option value="{{ $ageGroup->id }}"
                                                {{ in_array($ageGroup->id, $selectedAgeGroups) ? 'selected' : '' }}>
                                                {{ $ageGroup->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-6 pr-2">

                                <div class="form-group">
                                    <x-label name="relation_ids" validation="" tooltip="add_landing_page_relation"
                                        class="" />
                                    @php
                                        $optionsRelation = App\Models\Relation::get();
                                        $selectedRelations = old('relation_ids', $landingPage->relation_ids ?? []);
                                        $selectedRelations = is_array($selectedRelations)
                                            ? $selectedRelations
                                            : explode(',', $selectedRelations);
                                    @endphp
                                    <select name="relation_ids[]" id="relation_ids"
                                        class="form-control select2 relation_ids" multiple="multiple">
                                        @foreach ($optionsRelation as $relation)
                                            <option value="{{ $relation->id }}"
                                                {{ in_array($relation->id, $selectedRelations) ? 'selected' : '' }}>
                                                {{ $relation->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-6 pr-2">

                                <div class="form-group">
                                    <x-label name="gender_specificity_ids" validation=""
                                        tooltip="add_landing_page_gender_specificity" class="" />
                                    @php
                                        // Fetch gender options from the model
                                        $optionsGender = App\Models\GenderSpecificity::get();

                                        $selectedGenders = old(
                                            'gender_specificity_ids',
                                            $landingPage->gender_specificity_ids ?? [],
                                        );

                                        // Ensure $selectedGenders is an array
                                        $selectedGenders = is_array($selectedGenders)
                                            ? $selectedGenders
                                            : explode(',', $selectedGenders);
                                    @endphp
                                    <select name="gender_specificity_ids[]" id="gender_specificity_ids"
                                        class="form-control select2 gender_specificity_ids" multiple="multiple">
                                        @foreach ($optionsGender as $gender)
                                            <option value="{{ $gender->id }}"
                                                {{ in_array($gender->id, $selectedGenders) ? 'selected' : '' }}>
                                                {{ $gender->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-6 pr-2">

                                <div class="form-group">
                                    <x-label name="content_length_ids" validation="" tooltip="add_landing_page_content_length"
                                        class="" />
                                    @php
                                        $optionsContent = App\Models\ContentLength::get();
                                        // Ensure the selected content length IDs are an array
                                        $selectedContentLengths = old(
                                            'content_length_ids',
                                            $landingPage->content_length_ids ?? [],
                                        );
                                        $selectedContentLengths = is_array($selectedContentLengths)
                                            ? $selectedContentLengths
                                            : explode(',', $selectedContentLengths);
                                    @endphp
                                    <select name="content_length_ids[]" id="content_length_ids"
                                        class="form-control select2 content_length_ids" multiple="multiple">
                                        @foreach ($optionsContent as $content)
                                            <option value="{{ $content->id }}"
                                                {{ in_array($content->id, $selectedContentLengths) ? 'selected' : '' }}>
                                                {{ $content->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-6 pr-2">

                                <div class="form-group">
                                    <x-label name="badge_ids" validation="" tooltip="add_landing_page_badge" class="" />
                                    @php
                                        $optionsBadge = App\Models\Badge::get();
                                        // Ensure the selected badge IDs are an array
                                        $selectedBadges = old('badge_ids', $landingPage->badge_ids ?? []);
                                        $selectedBadges = is_array($selectedBadges)
                                            ? $selectedBadges
                                            : explode(',', $selectedBadges);
                                    @endphp
                                    <select name="badge_ids[]" id="badge_ids" class="form-control select2 badge_ids"
                                        multiple="multiple">
                                        @foreach ($optionsBadge as $badge)
                                            <option value="{{ $badge->id }}"
                                                {{ in_array($badge->id, $selectedBadges) ? 'selected' : '' }}>
                                                {{ $badge->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3> @lang('admin/ui.quick_option') </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 pr-2">
                                <div class="form-group {{ $errors->has('event_date') ? 'has-error' : '' }}">
                                    <x-label name="event_date" validation="" tooltip="add_landing_page_event_date" />
                                    <x-input type="date" value="{{ old('event_date', $landingPage->event_date) }}"
                                        name="event_date" id="event_date" class="form-control" />
                                </div>
                            </div>
                            <div class="col-md-6 pr-2">

                                <div class="form-group">
                                    <x-label name="media_type_id" validation="" tooltip="add_landing_page_media_type"
                                        class="" />
                                    @php
                                        $optionsMedia = App\Models\MediaType::get();
                                    @endphp
                                    <x-select name="media_type_id" optionName="name"
                                        value="{{ $landingPage->media_type_id }}" label="Media" optionName="name"
                                        class=" select2 media_type_id" validation="" id="media_type_id" valueName="id"
                                        payload="" payloadvalue="" :arr="@$optionsMedia" />
                                </div>
                            </div>
                            <div class="col-md-6 pr-2">

                                <div class="form-group">
                                    <x-label name="countries_id" validation="" tooltip="add_landing_page_countries"
                                        class="" />
                                    @php
                                        $optionsCountries = App\Models\Country::get();
                                    @endphp
                                    <x-select name="countries_id" optionName="name"
                                        value="{{ $landingPage->countries_id }}" label="Countries" optionName="name"
                                        class=" select2 countries_id" validation="" id="countries_id" valueName="id"
                                        payload="" payloadvalue="" :arr="@$optionsCountries" />
                                </div>
                            </div>
                            <div class="col-md-12 d-flex justify-content-between p-0 mt-2">
                                @php
                                    $checkbox_arr = ['is_predefined_date'];
                                @endphp
                                <div class="col-md-6 pr-2">
                                    <div class="form-group {{ @$errors->has('is_predefined_date') ? 'has-error' : '' }}">
    
                                        <x-checkbox name="is_predefined_date" class="js-switch switch-input" value="1"
                                            type="checkbox" tooltip="" validation="" id="is_predefined_date"
                                            :arr="@$checkbox_arr" />
                                            <x-label name="/" validation="" tooltip="is_landing_page_predefined_date"
                                            class="" />
    
                                    </div>
                                </div>
                                @php
                                $is_popular_arr = ['is_popular'];
                               @endphp
                                <div class="col-md-6 pr-2">
                                    <div class="form-group {{ @$errors->has('is_predefined_date') ? 'has-error' : '' }}">
                                        <x-checkbox name="is_popular" class="js-switch switch-input" value="1"
                                            type="checkbox" tooltip="" validation="" id="is_predefined_date"
                                            :arr="@$is_popular_arr" /> 
                                            <x-label name="/" validation="" tooltip="is_landing_page_popular"
                                            class="" />
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary floating-btn ajax-btn"> @lang('admin/ui.save_update') </button>
        </form>
    </div>

    <!-- push external js -->
    @push('script')
        {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js"></script> --}}
        <script src="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>

        <script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/decoupled-document/ckeditor.js"></script>

        {{-- START AUTO FILL BTN INIT --}}
        <script>
            $('#auto_fill_title').on('click', function(e) {
                e.preventDefault();
                var title_name = $('#name').val();
                $('#meta_title').val(title_name);
            })

            $('#auto_fill_description').on('click', function(e) {
                e.preventDefault();
                var short_desc = $('#short_description').val();
                $('#meta_description').val(short_desc);

            })
        </script>
        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    placeholder: "Select an option",
                    allowClear: true
                });
            });
            $('.ajaxForm').on('submit', function(e) {
                e.preventDefault();
                let route = $(this).attr('action');
                let method = $(this).attr('method');
                let data = new FormData(this);
                let redirectUrl = "{{ url('admin/landing-pages') }}";
                let response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
            })
        </script>
    @endpush
@endsection
