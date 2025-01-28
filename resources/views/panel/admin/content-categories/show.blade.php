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
@section('title', 'Content Category' . ' Show')
@section('content')
    @php
        $breadcrumb_arr = [
            ['name' => 'Content Category', 'url' => route('panel.admin.content-categories.index'), 'class' => ''],
            ['name' => 'Show ' . $contentCategory->getPrefix(), 'url' => 'javascript:void(0);', 'class' => 'Active'],
        ];
    @endphp
    <!-- push external head elements to head -->
    @push('head')
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
                            <h5>Content Categories </h5>
                            <span>Content Categories Details</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb')
                </div>
            </div>
        </div>
        <!-- start message area-->
        <div class="ajax-message text-center"></div>
        <!-- end message area-->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-0">
                    <div class="card-header d-flex justify-content-between">
                        <h3>Content Categories Details</h3>
                    </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-6 text-muted">
                            Name
                            <br>

                            <h6 class="text-black">{{ $contentCategory->name }}</h6>

                        </div>
                        <div class="col-md-4 col-6 text-muted">
                            Is_published
                            <br>

                            <h6 class="text-black">
                                {{ $contentCategory->is_published ? 'Yes' : 'No' }}
                            </h6>
                            
                        </div>
                        <div class="col-md-4 col-6 text-muted">
                            Is_featured
                            <br>

                            <h6 class="text-black">{{ $contentCategory->is_featured  ? 'Yes' : 'No'}}</h6>

                        </div>
                        <div class="col-md-4 col-6 text-muted">
                            Icon
                            <br>

                            <h6 class="text-black">{{ $contentCategory->icon }}</h6>

                        </div>
                        <div class="col-md-4 col-6 text-muted">
                            Description
                            <br>

                            <h6 class="text-black">{{ $contentCategory->description }}</h6>

                        </div>
                    </div>
                    <div class="col-md-12 mx-auto">
                        <div class="form-group">
                            <span class="updated-at-floating-btn" title="@lang('admin/ui.last_updated_at')"><i
                                    class="ik ik-clock mr-1"></i>{{ $contentCategory->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        </div>
    </div>
    </div>

@endsection
