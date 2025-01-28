@extends('layouts.main')
@section('title', __('admin/ui.left_sidebar_dashboard'))
@section('content')

    @php
        $breadcrumb_arr = [
            ['name' => __('admin/ui.left_sidebar_dashboard'), 'url' => 'javascript:void(0);', 'class' => 'active'],
        ];
    @endphp

    @push('head')
        <style>
            .ticket-card {
                margin-bottom: 20px;
            }

            .bg-color {
                background-color: #fff;
            }

            /* blinking light */
            .blinking-light {
                width: 50px;
                height: 6px;
                /* background-color: #EB525D;
                border-radius: 50%;
                box-shadow: 0 0 10px rgba(255, 0, 0, 0.5); */
                animation: blink 3s infinite;
                margin-top: 7px;
            }

            @keyframes blink {

                0%,
                50%,
                100% {
                    opacity: 1;
                }

                25%,
                75% {
                    opacity: 0;
                }
            }

            .blink-light-effect {
                display: flex;
                gap: 9px;
            }
        </style>
    @endpush
@php
     $statistics_1 = [
        ['a' =>  route('panel.admin.contents.index',['occasion' => App\Models\Occasion::FESTIVE]), 'name' => 'Festive', 'text-color' => 'warning', 'count' => App\Models\Content::where('occasion_id', App\Models\Occasion::FESTIVE)->count(), 'icon' => "<i class= 'fa-solid fa-map-pin f-24 text-muted'></i>", 'col' => '3', 'color' => 'primary'],

        ['a' =>  route('panel.admin.contents.index',['occasion' => App\Models\Occasion::SPECIAL_DAYS]), 'name' => 'Special Days', 'text-color' => 'danger', 'count' => App\Models\Content::where('occasion_id', App\Models\Occasion::SPECIAL_DAYS)->count(), 'icon' => "<i class= 'fa-solid fa-ban f-24 text-muted'></i>", 'col' => '3', 'color' => 'primary'],

        //  ['a' =>'#', 'name' => 'AVG Rating', 'text-color' => 'warning', 'count' => round(App\Models\Review::avg('rate')) ?? 0, 'icon' => "<i class='fas fa-star f-12 text-muted'></i>", 'col' => '3', 'color' => 'primary'],
         ];

@endphp
      <div class="container-fluid">
            <div class="page-header">
                <div class="row align-items-end">
                    <div class="col-lg-8">
                        <div class="page-header-title">
                            <i class="ik ik-grid bg-blue"></i>
                            <div class="d-inline">
                                <h5>{{ getGreetingBasedOnTime() }}</h5>
                            </div>
                        </div>
                        <span>
                            @lang('admin/ui.namaste') <span
                                class="text-dark dashboard-fullname fw-700">{{ auth()->user()->full_name }}</span>
                        </span>
                    </div>
                    <div class="col-lg-4 d-sm-flex d-lg-block">
                        @include('panel.admin.include.breadcrumb')
                    </div>
                </div>
            </div>
            <div class="shimmer-content">
                @include('panel.admin.dashboard.includes.shimmer')
            </div>

            <div class="row dashboard-content d-none">
                <div class="col-lg-12 col-sm-12">
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="statistic-header">
                                <h5>@lang('admin/ui.content_category_management')</h5>
                            </div>
                        </div>
                    </div>
                    <div class="statistics-grid">
                        @foreach (\App\Models\ContentCategory::CATEGORIRES as $key => $contentCategory)
                            <a class=""
                                href="{{route('panel.admin.contents.index',['content_category' => $key+1])}}">
                                <div class="card m-0">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="state">
                                                <h3 class="text-secondary">
                                                    @php
                                                       $category = \App\Models\ContentCategory::where('id',$key+1)->first();
                                                    @endphp
                                                   {{ isset($category->getContent) ? $category->getContent->count() : 0 }}</h3>
                                                <h6 class="card-subtitle text-dark fw-700 mb-0">{{ @$contentCategory['label'] }}
                                                </h6>
                                            </div>
                                            <div class="col-auto icon-size">
                                                <i
                                                    class="{{@$contentCategory['icon'] }} text-muted f-12 btn btn-light btn-icon p-2"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-12 col-sm-12">
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="statistic-header">
                                <h5>@lang('admin/ui.occasion_management')</h5>
                            </div>
                        </div>
                    </div>
                    <div class="statistics-grid">
                        @foreach ($statistics_1 as $item_2)
                            <a class=""
                                href="{{ $item_2['a']}}">
                                <div class="card m-0">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="state">
                                                <h3 class="text-secondary">{{ $item_2['count'] }}</h3>
                                                <h6 class="card-subtitle text-dark fw-700 mb-0 d-flex">
                                                    {{ @$item_2['name'] }}
                                                    <div class="ml-2">
                                                        @if ($key == 0)
                                                            <div class="blinking-light"></div>
                                                        @endif
                                                    </div>
                                                </h6>
                                            </div>
                                            <div class="col-auto icon-size f-12 btn btn-light btn-icon p-1 m-1">
                                                {!! $item_2['icon'] !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                {{-- <div class="col-lg-12 col-sm-12">
                    <div class="row clearfix">
                        <div class="col-md-12">
                            <div class="statistic-header">
                                <h5>@lang('admin/ui.event_management')</h5>
                            </div>
                        </div>
                    </div>
                    <div class="statistics-grid">
                        @foreach (\App\Models\Event::latest()->take(5)->get() as  $event)
                            <a class="" href="{{route('panel.admin.contents.index',['event' => $event->id])}}">
                                <div class="card m-0 ">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div class="state col-md-2">
                                                <h3 class="text-secondary">{{ $event->getContent()->count() }}</h3>
                                                <h6 class="card-subtitle text-dark fw-700 mb-0 d-flex">
                                                    {{ @$event->name}}
                                                    <div class="" style="margin-left: 80px !important">
                                                            <div class="blinking-light">{{ formatNumber(@$event->view_count)}} </div><br>view
                                                        
                                                    </div>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div> --}}
            </div>
        </div>

    @endsection

    @push('script')
        {{-- START JS HELPERS INIT --}}
        <script>
            $(document).ready(function() {

                    $('.dashboard-content').removeClass('d-none');
                    $('.shimmer-content').addClass('d-none');
            
                $('#allUsers').select2();
                $('input[type=radio][name=role_name]').change(function(e) {
                    e.preventDefault();
                    var roleName = $(this).val();
                    $.ajax({
                        type: 'post',
                        url: "{{ url('panel/admin/broadcast/role/record') }}",
                        data: {
                            role_name: roleName
                        },
                        dataType: 'json',
                        success: function(response) {
                            $('#allUsers').html(response.data);
                        }
                    });
                });

                $('.role_name').on('change', function() {
                    $('.broadcast_section').show();
                });
            });
        </script>
        {{-- END JS HELPERS INIT --}}
    @endpush
