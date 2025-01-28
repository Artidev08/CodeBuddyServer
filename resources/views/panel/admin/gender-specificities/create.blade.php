{{--
* Project: Gender Specificity
* @category ZStarter
* @ref zCRUD GENERATOR 
* @license Proprietary - Unauthorized copying, use, or distribution is strictly prohibited.
* License details: https://www.defenzelite.com/license
* (c) Defenzelite. All rights reserved.
* @contact hq@defenzelite.com
* @version zStarter: 1.1.2
--}}

@extends('layouts.main')
@section('title', 'Gender Specificity')
@section('content')
    @php

        $breadcrumb_arr = [
            ['name' => 'Gender Specificity', 'url' => route('panel.admin.gender-specificities.index'), 'class' => ''],
            ['name' => 'Add Gender Specificity', 'url' => 'javascript:void(0);', 'class' => 'active'],
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
                            <h5>@lang('admin/ui.add') Gender Specificity</h5>
                            <span>@lang('admin/ui.add_a_new_record_for') Gender Specificity</span>
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
                        <h3>@lang('admin/ui.create') Gender Specificity</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('panel.admin.gender-specificities.store') }}" method="post"
                            enctype="multipart/form-data" class="ajaxForm" id="GenderSpecificityForm">
                            @csrf
                            <x-input type="hidden" validation="" value="create" name="request_with" id="request_with"
                                placeholder="Enter Request With" class="form-control" tooltip="add_request_with" />
                            <div class="row">
                                <div class="col-md-6 col-12">

                                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                        <x-label name="name" validation="common_name"
                                            tooltip="add_gender_specificity_name" class="" />
                                        <x-input type="text" validation="common_name" value="{{ old('name') }}"
                                            name="name" id="name" placeholder="Enter Name" class="form-control"
                                            tooltip="add_gender_specificity_name" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group {{ $errors->has('emoji') ? 'has-error' : '' }}">
                                        <x-label name="emoji" validation="" tooltip="add_gender_specificity_emoji"
                                            class="" />
                                        <x-input type="text" validation="" value="{{ old('emoji') }}" name="emoji"
                                            id="emoji" placeholder="Enter Emoji" class="form-control"
                                            tooltip="add_gender_specificity_emoji" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                        <x-label name="description" validation=""
                                            tooltip="add_gender_specificity_description" class="" />
                                        <textarea name="description" id="description" placeholder="Enter Description" class="form-control" rows="4"
                                            tooltip="add_gender_specificity_description">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                                @php
                                    $ai_enabled_arr = ['is_ai_enabled'];
                                @endphp
                                <div class="col-md-6 mt-35">
                                    <div class="form-group {{ @$errors->has('is_ai_enabled') ? 'has-error' : '' }}">
                                        <x-checkbox name="is_ai_enabled" class="js-switch switch-input" value="1"
                                            type="checkbox" tooltip="" validation="" id="is_ai_enabled"
                                            :arr="@$ai_enabled_arr" />
                                        <x-label name="/" validation="" tooltip="is_ai_enabled" class="" />
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
                let redirectUrl = "{{ url('admin/gender-specificities') }}";
                let response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
            })
        </script>
    @endpush
@endsection
