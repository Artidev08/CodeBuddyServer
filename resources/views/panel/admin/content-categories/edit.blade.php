{{--
* Project: Content Category
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
@section('title', 'Content Category' . ' Edit')
@section('content')
    @php
        $breadcrumb_arr = [
            ['name' => 'Content Category', 'url' => route('panel.admin.content-categories.index'), 'class' => ''],
            ['name' => 'Edit ' . $contentCategory->getPrefix(), 'url' => 'javascript:void(0);', 'class' => 'Active'],
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
                            <h5>@lang('admin/ui.edit') Content Category </h5>
                            <span>@lang('admin/ui.update_a_record_for')
                                Content Category</span>
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
                        <h3>@lang('admin/ui.update') Content Category</h3>
                    </div>
                    <div class="card-body">
                        <form class="ajaxForm"
                            action="{{ route('panel.admin.content-categories.update', $contentCategory->id) }}"
                            method="post" enctype="multipart/form-data" id="ContentCategoryForm">
                            @csrf
                            <x-input type="hidden" validation="" value="update" name="request_with" id="request_with"
                                placeholder="Enter Request With" class="form-control" tooltip="add_request_with" />
                            <x-input type="hidden" validation="" value="{{ $contentCategory->id }}" name="id"
                                id="id" placeholder="Enter Id" class="form-control" tooltip="add_id" />

                            <div class="row">
                                <!-- Name Input -->
                                <div class="col-md-6 col-12">
                                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                        <x-label name="name" validation="common_name"  tooltip="add_content_name" class="" /> 
                                        <x-input type="text" validation="common_name"  value="{{ $contentCategory->name }}"
                                            name="name" id="name" placeholder="Enter Name" class="form-control"
                                            tooltip="add_name" />
                                    </div>
                                </div>

                                <!-- Checkbox Inputs -->
                               
                        
                        

                            <!-- Icon Input with Icon Display -->
                            <div class="col-md-6 col-12">
                                <div class="form-group {{ $errors->has('icon') ? 'has-error' : '' }}">
                                    <x-label name="icon" validation="" tooltip="add_content_icon" class="" />
                                    <div class="input-group">
                                        <x-input type="text" validation="" value="{{ $contentCategory->icon }}"
                                            name="icon" id="icon" placeholder="Enter Icon" class="form-control"
                                            tooltip="add_icon" />
                                    </div>
                                </div>
                            </div>
                             <!-- description -->
                         {{-- <div class="col-md-6 col-12">
                            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                <x-label name="description" validation="" tooltip="add_content_description" class="" />
                                <textarea name="description" id="description"  
                                          class="form-control"   >
                                          {{ old('description', $contentCategory->description) }}
                                </textarea>
                            </div>
                        </div> --}}
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <x-label name="description" validation="" tooltip="add_content_description" class="" />

                                <textarea name="description" id="description" class="form-control" > {{ old('description', $contentCategory->description) }}</textarea>
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
                                        <div class="form-group {{ $errors->has('is_published') ? 'has-error' : '' }}">
                                            <x-checkbox name="is_published" class="js-switch switch-input"
                                                value="{{ $contentCategory->is_published }}" type="checkbox" tooltip=""
                                                id="is_published" :arr="@$checkbox_arr" />
                                                <x-label name="/" validation="" tooltip="is_published"
                                            class="" />

                                        </div>
                                    </div>
                                    <!-- is_featured Checkbox -->
                                    <div class="col-md-4">
                                        <div class="form-group {{ $errors->has('is_featured') ? 'has-error' : '' }}">
                                            <x-checkbox name="is_featured" class="js-switch switch-input"
                                                value="{{ $contentCategory->is_featured }}" type="checkbox" tooltip=""
                                                id="is_featured" :arr="@$featured_arr" />
                                                <x-label name="/" validation="" tooltip="is_featured"
                                                class="" />
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group {{ @$errors->has('is_ai_enabled') ? 'has-error' : '' }}">
                                            <x-checkbox name="is_ai_enabled" class="js-switch switch-input" value="{{ $contentCategory->is_ai_enabled }}"
                                                type="checkbox" tooltip="" validation="" id="is_ai_enabled"
                                                :arr="@$ai_enabled_arr" />
                                            <x-label name="/" validation="" tooltip="is_ai_enabled"
                                                class="" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <!-- Updated At and Submit Button -->
                            <div class="col-md-12 mx-auto">
                                <div class="form-group">
                                    <span class="updated-at-floating-btn" title="@lang('admin/ui.last_updated_at')">
                                        <i
                                            class="ik ik-clock mr-1"></i>{{ $contentCategory->updated_at->diffForHumans() }}
                                    </span>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary floating-btn ajax-btn">
                                        @lang('admin/ui.save_update')
                                    </button>
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
                let redirectUrl = "{{ url('admin/content-categories') }}";
                let response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
            })
        </script>
    @endpush
@endsection
