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
@section('title', 'Landing Page' . ' Show')
@section('content')
    @php
        $breadcrumb_arr = [
            ['name' => 'Landing Page', 'url' => route('panel.member.landing-pages.index'), 'class' => ''],
            ['name' => 'Show ' . $landingPage->getPrefix(), 'url' => 'javascript:void(0);', 'class' => 'Active'],
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
                            <h5>Landing Pages </h5>
                            <span>Landing Pages Details</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.member.include.breadcrumb')
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
                        <h3>Landing Pages Details</h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-6 text-muted">
                                Slug
                                <br>

                                <h6 class="text-black">{{ $landingPage->slug }}</h6>

                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Title
                                <br>

                                <h6 class="text-black">{{ $landingPage->title }}</h6>

                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Short_description
                                <br>

                                <h6 class="text-black">{{ $landingPage->short_description }}</h6>

                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Closing_description
                                <br>

                                <h6 class="text-black">{{ $landingPage->closing_description }}</h6>

                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Ads_payload
                                <br>

                                <h6 class="text-black">{{ $landingPage->ads_payload }}</h6>

                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Content Categories
                                <br>
                                <h6 class="text-black">{{ @$landingPage->contentCategory->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                occasion
                                <br>
                                <h6 class="text-black">{{ @$landingPage->occasion->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Event
                                <br>
                                <h6 class="text-black">{{ @$landingPage->event->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Languages
                                <br>
                                <h6 class="text-black">{{ @$landingPage->language->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Sentiments
                                <br>
                                <h6 class="text-black">{{ @$landingPage->sentiment->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Age Groups
                                <br>
                                <h6 class="text-black">{{ @$landingPage->ageGroup->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Relations
                                <br>
                                <h6 class="text-black">{{ @$landingPage->relation->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Gender Specificitys
                                <br>
                                <h6 class="text-black">{{ @$landingPage->genderSpecificity->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Content Lengths
                                <br>
                                <h6 class="text-black">{{ @$landingPage->contentLength->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Badges
                                <br>
                                <h6 class="text-black">{{ @$landingPage->badge->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Is_predefined_date
                                <br>

                                <h6 class="text-black">{{ $landingPage->is_predefined_date ? 'Yes' : 'No' }}</h6>

                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Event_date
                                <br>

                                <h6 class="text-black">{{ $landingPage->event_date }}</h6>

                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Media Type
                                <br>
                                <h6 class="text-black">{{ @$landingPage->mediaType->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Countries
                                <br>
                                <h6 class="text-black">{{ @$landingPage->countries->name ?? 'N/A' }}</h6>
                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Meta_title
                                <br>

                                <h6 class="text-black">{{ $landingPage->meta_title }}</h6>

                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Meta_description
                                <br>

                                <h6 class="text-black">{{ $landingPage->meta_description }}</h6>

                            </div>
                            <div class="col-md-4 col-6 text-muted">
                                Meta_keywords
                                <br>

                                <h6 class="text-black">{{ $landingPage->meta_keywords }}</h6>

                            </div>
                        </div>
                        <div class="col-md-12 mx-auto">
                            <div class="form-group">
                                <span class="updated-at-floating-btn" title="@lang('admin/ui.last_updated_at')"><i
                                        class="ik ik-clock mr-1"></i>{{ $landingPage->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection
