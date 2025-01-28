{{--
* Project: Content Category
* @category ZStarter
* @ref zCRUD GENERATOR 
* @license Proprietary - Unauthorized copying, use, or distribution is strictly prohibited.
* License details: https://www.defenzelite.com/license
* (c) Defenzelite. All rights reserved.
* @contact hq@defenzelite.com
* @version zStarter: 1.1.2
--}}

@extends('layouts.main')
@section('title', 'Content Category')
@section('content')
    @php

        $breadcrumb_arr = [
            ['name' => 'Content Category', 'url' => route('panel.member.content-categories.index'), 'class' => ''],
            ['name' => 'Add Content Category', 'url' => 'javascript:void(0);', 'class' => 'active'],
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
                            <h5>@lang('admin/ui.add') Content Category</h5>
                            <span>@lang('admin/ui.add_a_new_record_for') Content Category</span>
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
                        <h3>@lang('admin/ui.create') Content Category</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('panel.member.content-categories.store') }}" method="post"
                            enctype="multipart/form-data" class="ajaxForm" id="ContentCategoryForm">
                            @csrf
                            <x-input type="hidden" validation="" value="create" name="request_with" id="request_with"
                                placeholder="Enter Request With" class="form-control" tooltip="add_request_with" />
                            <div class="row">
                                <div class="col-md-6 col-12">

                                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                        <x-label name="name" validation="common_name" tooltip="add_content_name"
                                            class="" />
                                        <x-input type="text" validation="common_name" value="{{ old('name') }}"
                                            name="name" id="name" placeholder="Enter Name" class="form-control"
                                            tooltip="add_name" />
                                    </div>
                                </div>


                                <div class="col-md-6 col-12">
                                    <div class="form-group {{ $errors->has('icon') ? 'has-error' : '' }}">
                                        <x-label name="icon" validation="" tooltip="add_content_icon" class="" />
                                        <div class="input-group">
                                            <x-input type="text" validation="" value="{{ old('icon') }}"
                                                name="icon" id="icon" placeholder="Enter icon" class="form-control"
                                                tooltip="add_content_icon" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                        <x-label name="description" validation="" tooltip="add_content_description"
                                            class="" />
                                        <textarea name="description" id="description" placeholder="Enter Description" class="form-control" rows="4"
                                            tooltip="add_content_description">{{ old('description') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mt-25">
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
    @push('script')
        <script>
            $('.ajaxForm').on('submit', function(e) {
                e.preventDefault();
                let route = $(this).attr('action');
                let method = $(this).attr('method');
                let data = new FormData(this);
                let redirectUrl = "{{ url('member/content-categories') }}";
                let response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
            })
        </script>
    @endpush
@endsection
