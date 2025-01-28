{{--
* Project: Badge
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
@section('title', 'Badge' . ' Edit')
@section('content')
    @php
        $breadcrumb_arr = [
            ['name' => 'Badge', 'url' => route('panel.admin.badges.index'), 'class' => ''],
            ['name' => 'Edit ' . $badge->getPrefix(), 'url' => 'javascript:void(0);', 'class' => 'Active'],
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
                            <h5>@lang('admin/ui.edit') Badge </h5>
                            <span>@lang('admin/ui.update_a_record_for')
                                Badge</span>
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
                        <h3>@lang('admin/ui.update') Badge</h3>
                    </div>
                    <div class="card-body">
                        <form class="ajaxForm" action="{{ route('panel.admin.badges.update', $badge->id) }}" method="post"
                            enctype="multipart/form-data" id="BadgeForm">
                            @csrf
                            <x-input type="hidden" validation="" value="update" name="request_with" id="request_with"
                                placeholder="Enter Request With" class="form-control" tooltip="add_request_with" />
                            <x-input type="hidden" validation="" value="{{ $badge->id }}" name="id" id="id"
                                placeholder="Enter Id" class="form-control" tooltip="add_id" />

                            <div class="row">
                                <div class="col-md-6 col-12">

                                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                        <x-label name="name" validation="common_name" tooltip="add_badges_name" class="" />
                                        <x-input type="text" validation="common_name" value="{{ $badge->name }}"
                                            name="name" id="name" placeholder="Enter Name" class="form-control"
                                            tooltip="add_badges_name" />
                                    </div>
                                </div>
                                 <!-- description -->
                         <div class="col-md-6 col-12">
                            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                <x-label name="description" validation="" tooltip="add_badges_description" class="" />
                                <textarea name="description" id="description" placeholder="Enter Description" 
                                          class="form-control" tooltip="add_description">
                                    {{ old('description', $badge->description) }}
                                </textarea>
                            </div>
                        </div>


                                <div class="col-md-12 mx-auto">
                                    <div class="form-group">
                                        <span class="updated-at-floating-btn" title="@lang('admin/ui.last_updated_at')"><i
                                                class="ik ik-clock mr-1"></i>{{ $badge->updated_at->diffForHumans() }}</span>
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
                let redirectUrl = "{{ url('admin/badges') }}";
                let response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
            })
        </script>
    @endpush
@endsection
