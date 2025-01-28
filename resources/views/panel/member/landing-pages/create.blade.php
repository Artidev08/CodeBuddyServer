{{--
* Project: Landing Page
* @category ZStarter
* @ref zCRUD GENERATOR 
* @license Proprietary - Unauthorized copying, use, or distribution is strictly prohibited.
* License details: https://www.defenzelite.com/license
* (c) Defenzelite. All rights reserved.
* @contact hq@defenzelite.com
* @version zStarter: 1.1.2
--}}

@extends('layouts.main')
@section('title', 'Landing Page')
@section('content')
    @php

        $breadcrumb_arr = [
            ['name' => 'Landing Page', 'url' => route('panel.member.landing-pages.index'), 'class' => ''],
            ['name' => 'Add Landing Page', 'url' => 'javascript:void(0);', 'class' => 'active'],
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
                            <h5>@lang('admin/ui.add') Landing Page</h5>
                            <span>@lang('admin/ui.add_a_new_record_for') Landing Page</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.member.include.breadcrumb')
                </div>
            </div>
        </div>
        <form class="row" action="{{ route('panel.member.landing-pages.store') }}" method="post"
            enctype="multipart/form-data" class="ajaxForm">
            @csrf
            <x-input name="request_with" type="hidden" value="create" />
            <div class="col-md-12">
                <!-- start message area-->
                @include('panel.member.include.message')
                <!-- end message area-->
            </div>
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h3>Add landing page </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group {{ @$errors->has('title') ? 'has-error' : '' }}">
                                    <x-label name="title" validation="item_title" tooltip="add_landing_page_title" />
                                    <x-input name="title"
                                        placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.title') }}" type="text"
                                        tooltip="add_landing_page_title" regex="item_title" validation="item_title"
                                        value="{{ old('title') }}" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group {{ @$errors->has('slug') ? 'has-error' : '' }}">
                                    <x-label name="slug" validation="item_slug" tooltip="add_landing_page_slug" />
                                    <div class="input-group d-block d-md-flex">
                                        {{-- <input {{ @validation('item_slug')['pattern']['mandatory']}} type="hidden" class="form-control w-100 w-md-auto" id="slugInput"  oninput="slugFunction()" placeholder="{{ 'Slug' }}" name="slug"> --}}
                                        <x-input name="slug" id="slugInput" oninput="slugFunction()"
                                            placeholder="{{ __('admin/ui.slug') }}" type="hidden" tooltip="add_item_slug"
                                            regex="item_slug" id="slugInput" validation="item_slug"
                                            value="{{ old('slug') }}" />
                                        <div class="input-group-prepend"><span class="input-group-text flex-grow-1"
                                                style="overflow: auto" id="slugOutput">{{ url('content/') }}</span>
                                            <span id="slugOutput"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <x-label name="short_description" validation="item_short_description"
                                tooltip="add_landing_page_short_description" />
                            <x-textarea regex="" validation="" value="{{ old('short_description') }}"
                                name="short_description" id="short_description"
                                placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.description') }}" />
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <x-label name="closing_description" validation="item_description" tooltip="add_landing_page_description" />
                                <div id="content-holder">
                                    <div id="toolbar-container"></div>
                                    <div id="txt_area">
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
                                        <x-label name="meta_title" validation="" tooltip="add_landing_page_meta_title" />
                                    </div>
                                    <div>
                                        <button id="auto_fill_title"
                                            class="p-0 btn btn-link btn-sm text-primary float-end fw-800"><i
                                                class="ik ik-corner-left-down"></i> @lang('admin/ui.auto_fill')
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <x-input name="meta_title"
                                        placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.meta_title') }}"
                                        type="text" tooltip="add_landing_page_meta_title" regex="" validation=""
                                        value="{{ @old('meta_title') }}" id="meta_title" class="cyBlogSeoKeywords" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div >
                                    <div>
                                        <x-label name="meta_keywords" validation="" tooltip="add_landing_page_meta_keyword" />
                                    </div>
                                    <div class="form-group" >
                                            <x-input name="meta_keywords"
                                        placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.meta_keyword') }}"
                                        type="text" tooltip="add_landing_page_meta_title" regex="item_meta_keywords" validation=""
                                        value="{{ @old('meta_keywords') }}" id="meta_keywords" class="cyBlogMetaDescription" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
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
                                        value="{{ @old('meta_description') }}" name="meta_description"
                                        id="meta_description"
                                        placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.meta_description') }}"
                                        rows="2" class="cyBlogMetaDescription" />
                                </div>
                                <div class="form-group">
                                    <x-label name="ads_payload" validation="" tooltip="add_landing_page_ads_payload"
                                        class="" />

                                    <x-textarea validation="" value="{{ old('ads_payload') }}" name="ads_payload"
                                        id="ads_payload" placeholder="Enter Ads Payload" rows="2" cols="2"
                                        class="ads_payload" />
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
                            <h3> @lang('admin/ui.organise') Criteria </h3>
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
                                    <x-select name="content_category_id" optionName="name" value=""
                                        label="Content" optionName="name" class=" select2 content_category_id"
                                        validation="" id="content_category_id" valueName="id" payload=""
                                        payloadvalue="" :arr="@$optionsContent" />
                                </div>
                            </div>
                            <div class="col-md-6 pr-2">

                                <div class="form-group">
                                    <x-label name="occasion_id" validation="" tooltip="add_landing_page_occasion"
                                        class="" />
                                    @php
                                        $optionsoccasion = App\Models\Occasion::get();
                                    @endphp
                                    <x-select name="occasion_id" optionName="name" value="" label="occasion"
                                        optionName="name" class=" select2 occasion_id" validation="" id="occasion_id"
                                        valueName="id" payload="" payloadvalue="" :arr="@$optionsoccasion" />
                                </div>
                            </div>
                            <div class="col-md-6 pr-2">

                                <div class="form-group">
                                    <x-label name="event_id" validation="" tooltip="add_landing_page_event" class="" />
                                    @php
                                        $optionsEvent = App\Models\Event::get();
                                    @endphp
                                    <x-select name="event_id" optionName="name" value="" label="Event"
                                        optionName="name" class=" select2 event_id" validation="" id="event_id"
                                        valueName="id" payload="" payloadvalue="" :arr="@$optionsEvent" />
                                </div>
                            </div>
                            <div class="col-md-6 pr-2">

                                <div class="form-group">
                                    <x-label name="language_ids" validation="" tooltip="add_landing_page_language"
                                        class="" />

                                    @php
                                        $optionsLanguage = App\Models\Language::get();
                                    @endphp

                                    <select name="language_ids[]" id="language_ids"
                                        class="form-control select2 language_ids" multiple="multiple">
                                        @foreach ($optionsLanguage as $language)
                                            <option value="{{ $language->id }}"
                                                {{ in_array($language->id, old('language_ids', [])) ? 'selected' : '' }}>
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
                                    @endphp

                                    <select name="sentiment_ids[]" id="sentiment_ids"
                                        class="form-control select2 sentiment_ids" multiple="multiple">
                                        @foreach ($optionsSentiment as $sentiment)
                                            <option value="{{ $sentiment->id }}"
                                                {{ in_array($sentiment->id, old('sentiment_ids', [])) ? 'selected' : '' }}>
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
                                        $optionsAge = App\Models\AgeGroup::get();
                                    @endphp

                                    <select name="age_group_ids[]" id="age_group_ids"
                                        class="form-control select2 age_group_ids" multiple="multiple">
                                        @foreach ($optionsAge as $ageGroup)
                                            <option value="{{ $ageGroup->id }}"
                                                {{ in_array($ageGroup->id, old('age_group_ids', [])) ? 'selected' : '' }}>
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
                                    @endphp

                                    <select name="relation_ids[]" id="relation_ids"
                                        class="form-control select2 relation_ids" multiple="multiple">
                                        @foreach ($optionsRelation as $relation)
                                            <option value="{{ $relation->id }}"
                                                {{ in_array($relation->id, old('relation_ids', [])) ? 'selected' : '' }}>
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
                                        $optionsGender = App\Models\GenderSpecificity::get();
                                    @endphp

                                    <div class="form-group">


                                        @php
                                            $optionsGender = App\Models\GenderSpecificity::get();
                                        @endphp

                                        <select name="gender_specificity_ids[]" id="gender_specificity_ids"
                                            class="form-control select2 gender_landing_page_specificity" multiple="multiple">
                                            <!-- Dynamically generated options -->
                                            @foreach ($optionsGender as $gender)
                                                <option value="{{ $gender->id }}"
                                                    {{ in_array($gender->id, old('gender_specificity_ids', [])) ? 'selected' : '' }}>
                                                    {{ $gender->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                            </div>
                            <div class="col-md-6 pr-2">

                                <div class="form-group">
                                    <x-label name="content_length_ids" validation="" tooltip="add_landing_page_content_length"
                                        class="" />

                                    @php
                                        $optionsContent = App\Models\ContentLength::get();
                                    @endphp

                                    <select name="content_length_ids[]" id="content_length_ids"
                                        class="form-control select2 content_length_ids" multiple="multiple">
                                        <!-- Dynamically generated options -->
                                        @foreach ($optionsContent as $contentLength)
                                            <option value="{{ $contentLength->id }}"
                                                {{ in_array($contentLength->id, old('content_length_ids', [])) ? 'selected' : '' }}>
                                                {{ $contentLength->name }}
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
                                    @endphp

                                    <select name="badge_ids[]" id="badge_ids" class="form-control select2 badge_ids"
                                        multiple="multiple">
                                        @foreach ($optionsBadge as $badge)
                                            <option value="{{ $badge->id }}"
                                                {{ in_array($badge->id, old('badge_ids', [])) ? 'selected' : '' }}>
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
                                    <x-label name="event_date" validation="" tooltip="add_landing_page_event_date" class="" />
                                    <x-input type="date" validation="" value="{{ old('event_date') }}"
                                        name="event_date" id="event_date" placeholder="Enter Event Date"
                                        class="form-control" tooltip="add_event_date" />
                                </div>
                            </div>
                            <div class="col-md-6 pr-2">

                                <div class="form-group">
                                    <x-label name="media_type_id" validation="" tooltip="add_landing_page_media_type"
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
                            <div class="col-md-6 pr-2">

                                <div class="form-group">
                                    <x-label name="countries_id" validation="" tooltip="add_landing_page_countries"
                                        class="" />
                                    @php
                                        $optionsCountries = App\Models\Country::get();
                                    @endphp
                                    <x-select name="countries_id" optionName="name" value="" label="Countries"
                                        optionName="name" class=" select2 countries_id" validation="" id="countries_id"
                                        valueName="id" payload="" payloadvalue="" :arr="@$optionsCountries" />
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


            <button  type="submit" class="btn btn-primary floating-btn ajax-btn">
                @lang('admin/ui.create')
            </button>
        </form>
    </div>

    <!-- push external js -->
    @push('script')
        <script>
            function slugFunction() {
                var x = document.getElementById("slug").value;
                document.getElementById("slugOutput").innerHTML = "{{ url('Landing Page') }}/" + x;
            }

            function convertToSlug(Text) {
                return Text
                    .toLowerCase()
                    .replace(/ /g, '-')
                    .replace(/[^\w-]+/g, '');
            }

            $('#').on('keyup', function() {
                $('#slug').val(convertToSlug($('#').val()));
                slugFunction();
            });

            $(document).ready(function() {
                $('.select2').select2();
            }); <
            script src = "{{ asset('panel/admin/plugins/mohithg-switchery/dist/switchery.min.js') }}" >
        </script>
        <script src="{{ asset('panel/admin/plugins/ckeditor5/ckeditor.js') }}"></script>

        {{-- START DECOUPLEDEDITOR INIT --}}
        <script>
            let editor;
            $(window).on('load', function() {
                $('#txt_area').addClass('ck-editor');
                DecoupledEditor
                    .create(document.querySelector('.ck-editor'), {
                        ckfinder: {
                            uploadUrl: "{{ route('panel.member.media.ckeditor.upload') . '?_token=' . csrf_token() }}",
                        }
                    })
                    .then(newEditor => {
                        editor = newEditor;
                        const toolbarContainer = document.querySelector('#toolbar-container');

                        toolbarContainer.appendChild(editor.ui.view.toolbar.element);
                    })
                    .catch(error => {
                        console.error(error);
                    });

            });
        </script>
        {{-- END DECOUPLEDEDITOR INIT --}}

        {{-- START AUTOFILL BTN INIT --}}
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
        {{-- END AUTOFILL BTN INIT --}}

        {{-- START TAGINPUT INIT --}}
        <script src="{{ asset('panel/admin/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
        <script>
            $('#tags').tagsinput('items');
        </script>
        {{-- END TAGINPUT INIT --}}

        {{-- START AJAX FORM INIT --}}
        <script>
            // $('.ajaxForm').on('submit', function(e) {
            //     e.preventDefault();
            //     var route = $(this).attr('action');
            //     var method = $(this).attr('method');
            //     var data = new FormData(this);
            //     ckEditors.forEach((editor, index) => {
            //         if (index == 0) {
            //             data.append('description', editor.getData());
            //         } else {
            //             data.append(`editor_content_${index}`, editor.getData());
            //         }
            //     });
            //     var redirectUrl = "{{ url('admin/items') }}";
            //     var response = postData(method, route, 'json', data, null, null, '1', true, redirectUrl);
            // });
            $('.ajaxForm').on('submit', function(e) {
                alert('jj');
                e.preventDefault();
                // Ensure that the CKEditor content is captured correctly
                let descriptionContent = editor.getData(); // Assuming 'editor' is your CKEditor instance
                if (!descriptionContent.trim()) {
                    alert("Description is required.");
                    return; // Stop form submission if description is empty
                }
               
                var route = $(this).attr('action');
                var method = $(this).attr('method');
                var data = new FormData(this);
                data.append('description', descriptionContent); // Append description content

                var redirectUrl = "{{ url('admin/landing-pages') }}";
                postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
            });
        </script>
        </script>
        {{-- END AJAX FORM INIT --}}

        {{-- START JS HELPERS INIT --}}
        <script>
            function slugFunction() {
                var x = document.getElementById("slugInput").value;
                document.getElementById("slugOutput").innerHTML = "{{ url('/content/') }}/" + x;
            }

            function convertToSlug(Text) {
                return Text
                    .toLowerCase()
                    .replace(/ /g, '-')
                    .replace(/[^\w-]+/g, '');
            }

            $('#title').on('keyup', function() {
                $('#slugInput').val(convertToSlug($('#title').val()));
                slugFunction();
            });

            $('#item_image').on('change', function() {
                var src = URL.createObjectURL(this.files[0])
                $('#show-image').removeClass('d-none');
                document.getElementById('show-image').src = src
            })
        </script>
        {{-- END JS HELPERS INIT --}}

        {{-- START ON CHANGE CATAGORY --}}
        {{-- <script>
            $(document).ready(function() {
                // Change event for category selection
                $('#category_id').on('change', function() {
                    var category = $(this).val();
                    $.ajax({
                        url: "{{ route('panel.member.items.getSubCategories') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: category
                        },
                        dataType: "html",
                        method: "POST",
                        success: function(data) {
                            $('#subcategory_id').html(data);
                            $('#subcategory_id').select2("refresh");
                        }
                    });
                });
            });
        </script> --}}
        {{-- END ON CHANGE CATAGORY --}}
        </script>
    @endpush
@endsection
