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
@section('title', 'Content' . ' Show')
@section('content')
    @php
        $breadcrumb_arr = [
            ['name' => 'Content', 'url' => route('panel.admin.contents.index'), 'class' => ''],
            ['name' => 'Show ' . $content->getPrefix(), 'url' => 'javascript:void(0);', 'class' => 'Active'],
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
                            <h5>Contents </h5>
                            <span>Contents Details</span>
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
                        <h3>Contents Details</h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-6 text-muted">
                                Description
                                <br>

                                <h6 class="text-black">{{ $content->description }}</h6>

                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Content Category
                                <br>
                                <h6 class="text-black">{{ @$content->contentCategory->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                occasion
                                <br>
                                <h6 class="text-black">{{ @$content->occasion->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Event
                                <br>
                                <h6 class="text-black">{{ @$content->event->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Language
                                <br>
                                <h6 class="text-black">{{ @$content->language->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Sentiment
                                <br>
                                <h6 class="text-black">{{ @$content->sentiment->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Age Group
                                <br>
                                <h6 class="text-black">{{ @$content->ageGroup->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Relation
                                <br>
                                <h6 class="text-black">{{ @$content->relation->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Gender Specificity
                                <br>
                                <h6 class="text-black">{{ @$content->genderSpecificity->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Content Length
                                <br>
                                <h6 class="text-black">{{ @$content->contentLength->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Badge
                                <br>
                                <h6 class="text-black">{{ @$content->badge->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Is_predefined_date
                                <br>

                                <h6 class="text-black">{{ $content->is_predefined_date  ? 'Yes' : 'No' }}</h6>

                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Event_date
                                <br>

                                <h6 class="text-black">{{ $content->event_date }}</h6>

                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Media Type
                                <br>
                                <h6 class="text-black">{{ @$content->mediaType->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Countries
                                <br>
                                <h6 class="text-black">{{ @$content->countries->name ?? 'N/A' }}</h6>
                            </div>
                        </div>
                        <div class="col-md-12 mx-auto">
                            <div class="form-group">
                                <span class="updated-at-floating-btn" title="@lang('admin/ui.last_updated_at')"><i
                                        class="ik ik-clock mr-1"></i>{{ $content->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection
