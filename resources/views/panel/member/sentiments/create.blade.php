{{--
* Project: Sentiment
* @category ZStarter
* @ref zCRUD GENERATOR 
* @license Proprietary - Unauthorized copying, use, or distribution is strictly prohibited.
* License details: https://www.defenzelite.com/license
* (c) Defenzelite. All rights reserved.
* @contact hq@defenzelite.com
* @version zStarter: 1.1.2
--}}

@extends('layouts.main')
@section('title', 'Sentiment')
@section('content')
    @php

        $breadcrumb_arr = [
            ['name' => 'Sentiment', 'url' => route('panel.admin.sentiments.index'), 'class' => ''],
            ['name' => 'Add Sentiment', 'url' => 'javascript:void(0);', 'class' => 'active'],
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
                            <h5>@lang('admin/ui.add') Sentiment</h5>
                            <span>@lang('admin/ui.add_a_new_record_for') Sentiment</span>
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
                        <h3>@lang('admin/ui.create') Sentiment</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('panel.admin.sentiments.store') }}" method="post"
                            enctype="multipart/form-data" class="ajaxForm" id="SentimentForm">
                            @csrf
                            <x-input type="hidden" validation="" value="create" name="request_with" id="request_with"
                                placeholder="Enter Request With" class="form-control" tooltip="add_request_with" />
                            <div class="row">
                                <div class="col-md-6 col-12">

                                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                        <x-label name="name" validation="common_name" tooltip="add_sentiment_name"
                                            class="" />
                                        <x-input type="text" validation="common_name" value="{{ old('name') }}"
                                            name="name" id="name" placeholder="Enter Name" class="form-control"
                                            tooltip="add_sentiment_name" />
                                    </div>  
                                </div>
                               
                                <div class="col-md-6 col-12">

                                    <div class="form-group {{ $errors->has('emoji') ? 'has-error' : '' }}">
                                        <x-label name="emoji" validation="" tooltip="add_sentiment_emoji" class="" />
                                        <x-input type="text" validation="" value="{{ old('emoji') }}" name="emoji"
                                            id="emoji" placeholder="Enter Emoji" class="form-control"
                                            tooltip="add_sentiment_emoji" />
                                    </div>
                                </div>
                                
                                <div class="col-md-6 col-12">

                                    <div class="form-group {{ $errors->has('sequence') ? 'has-error' : '' }}">
                                        <x-label name="sequence" validation="" tooltip="add_sentiment_sequence" class="" />
                                        <x-input type="text" validation="" value="{{ old('sequence') }}" name="sequence"
                                            id="sequence" placeholder="Enter Sequence" class="form-control"
                                            tooltip="add_sentiment_sequence" />
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">

                                    <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                        <x-label name="description" validation="" tooltip="add_sentiment_description"
                                            class="" />
                                        <textarea type="text" validation="" value="{{ old('description') }}" name="description" id="description"
                                            placeholder="Enter Description" class="form-control" tooltip="add_sentiment_description"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 p-0">
                                    @php
                                            $checkbox_arr = ['is_published'];
                                            $ai_enabled_arr = ['is_ai_enabled'];

                                        @endphp
                                        <!-- is_published Checkbox -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="col-md-6">
                                                <div class="form-group {{ @$errors->has('is_published') ? 'has-error' : '' }}">
    
                                                    <x-checkbox name="is_published" class="js-switch switch-input m-0"
                                                        value="1" type="checkbox" tooltip="" validation=""
                                                        id="is_published" :arr="@$checkbox_arr" />
                                                        <x-label name="/" validation="" tooltip="is_published"
                                                        class="" />
                                                </div>
                                            </div>
    
                                            <div class="col-md-6">
                                                <div class="form-group {{ @$errors->has('is_ai_enabled') ? 'has-error' : '' }}">
                                                    <x-checkbox name="is_ai_enabled" class="js-switch switch-input" value="1"
                                                        type="checkbox" tooltip="" validation="" id="is_ai_enabled"
                                                        :arr="@$ai_enabled_arr" />
                                                    <x-label name="/" validation="" tooltip="is_ai_enabled"
                                                        class="" />
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
        <script>
            $('.ajaxForm').on('submit', function(e) {
                e.preventDefault();
                let route = $(this).attr('action');
                let method = $(this).attr('method');
                let data = new FormData(this);
                let redirectUrl = "{{ url('admin/sentiments') }}";
                let response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
            })
        </script>
    @endpush
@endsection
