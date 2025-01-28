{{--
* Project: Event
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
@php
    $occasion = '';
    $occasionBreadcrumb = '';
    if ($occasion_id != null) {
        $occasion = 'occasion - ';
        $occasionBreadcrumb = 'occasion / ';
    }
@endphp
@section('title', $occasion . ' Events')
@section('content')
    @php
        $breadcrumb_arr = [
            ['name' => $occasionBreadcrumb . 'Events', 'url' => 'javascript:void(0);', 'class' => 'active'],
            
        ];
        
    @endphp
    <!-- push external head elements to head -->
    @push('head')
        <style>
            #recent_searches {
                height: 30px;
            }

            .custom-badge {
                padding: 8px 8px;
                background-color: #80808052;
                border-radius: 10px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            }

            .custom-badge:hover {
                background-color: #80808087;
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
                            <h5>Events </h5>
                            <span>@lang('admin/ui.list_of') Events</span>
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
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3>Events @if (request()->get('trash') == 1)
                                Trashed
                            @endif
                        </h3>
                        <span class="font-weight-bold border-bottom trash-option   d-none ">Trash</span>
                        <div class="d-flex justicy-content-right">
                            @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'add_event'))
                           
                                @if ($occasion_id != null)
                                    <a href="{{ route('panel.member.events.create', ['occasion_id' => encrypt($occasion_id)]) }}"
                                        class="btn btn-sm btn-outline-primary mr-2" title="Add New Event">
                                        <i class="fa fa-plus" aria-hidden="true"></i> @lang('admin/ui.add')
                                    </a>
                                @else
                                    <a href="{{ route('panel.member.events.create') }}"
                                        class="btn btn-sm btn-outline-primary mr-2" title="Add New Event">
                                        <i class="fa fa-plus" aria-hidden="true"></i> @lang('admin/ui.add')
                                    </a>
                                @endif
                            @endif

                            @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'enable_disable_event') || isUserHasPermission($authUser->permissions['permissions'], 'delete_event'))
                                <div class="dropdown d-flex justicy-content-left">
                                    <button class="dropdown-toggle p-0 custom-dopdown bulk-btn btn btn-light" type="button"
                                        id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                            class="ik ik-more-vertical fa-lg pl-1"></i></button>
                                    <ul class="dropdown-menu dropdown-position multi-level" role="menu"
                                        aria-labelledby="dropdownMenu">
                                        @php
                                                $arr = [
                                                    0 => 'Mark as No',
                                                    1 => 'Mark as Yes',
                                                ];
                                                $arrVisibility = [
                                                    0 => 'Mark as Unpublish',
                                                    1 => 'Mark as Publish',
                                                ];
                                                $arrPrompt = [
                                                    0 => 'Mark as Disable',
                                                    1 => 'Mark as Enable',
                                                ];
                                        @endphp
                                        @foreach (getSelectValues($arr) as $key => $option)
                                            <a href="javascript:void(0)" class="dropdown-item action"
                                                data-action="is_predefined_date-{{ $key }}">{{ $option }}</a>
                                        @endforeach
                                        @foreach (getSelectValues($arrVisibility) as $key => $option)
                                            <a href="javascript:void(0)" class="dropdown-item action"
                                                data-action="is_published-{{ $key }}">{{ $option }}</a>
                                        @endforeach
                                        @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'enable_disable_event'))
                                        @foreach (getSelectValues($arrPrompt) as $key => $option)
                                        <a href="javascript:void(0)" class="dropdown-item action"
                                            data-action="is_ai_enabled-{{ $key }}">{{ $option }}</a>
                                            @endforeach
                                            @endif
                                        <hr>
                                        @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'delete_event'))
                                        <a href="javascript:void(0)" data-action="Move To Trash"
                                                class="dropdown-item action trash-option mt-0 pt-0 text-danger fw-700"><i
                                                    class="ik ik-trash mr-2"></i> @lang('admin/ui.bulk_delete')</a>
                                                    @endif

                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div id="ajax-container">
                        @include('panel.member.events.load')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('panel.member.events.includes.filter')
    <!-- push external js -->
    @push('script')
        @include('panel.member.include.more-action', [
            'actionUrl' => 'member/events',
            'routeClass' => 'events',
        ])

        <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    @endpush
@endsection
