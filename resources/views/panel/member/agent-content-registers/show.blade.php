@extends('layouts.main')
@section('title', $item->getPrefix() . ' Agent Show')
@section('content')
    @push('head')
        <link rel="stylesheet" href="{{ asset('panel/admin/plugins/select2/dist/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('panel/admin/plugins/mohithg-switchery/dist/switchery.min.css') }}">
        <style>
            .dt-button.dropdown-item.buttons-columnVisibility.active {
                background: #322d2d !important;
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-user bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ Str::limit($item->name, 20) }}</h5>
                            <span>Agent Profile</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('panel.admin.dashboard.index') }}"><i
                                        class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('panel.admin.agent-content-registers.index') }}" >{{ __('Agent') }}</a>
                            </li>
                            <li class="breadcrumb-item active " aria-current="page">{{ __('Show') }}</li>
                        </ol>
                    </nav>

                </div>
            </div>
        </div>

        @include('panel.admin.include.message')

        <div class="row">
            <div class="col-lg-4 col-md-5">
                @include('panel.admin.agent-content-registers.include.profile')
            </div>
            <div class="col-lg-8 col-md-7">
                <div class="card">
                    <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a data-active="prompt_details"
                                class=" nav-link active-swicher @if (!request()->has('active')) active @endif"
                                id="pills-note-tab" data-toggle="pill" href="#prompt_details" role="tab"
                                aria-controls="pills-note" aria-selected="false">{{ __('Prompt Details') }}</a>
                        </li>
                        <li class="nav-item">
                            <a data-active="agent_details"
                                class=" nav-link active-swicher @if (request()->has('active')) active @endif"
                                id="pills-note-tab" data-toggle="pill" href="#agent_details" role="tab"
                                aria-controls="pills-note" aria-selected="false">{{ __('Basic Details') }}</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade  @if (request()->has('active')) show active @endif" id="agent_details"
                            role="tabadmin" aria-labelledby="pills-note-tab">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-6 text-muted" title="Date Of Joining"> DOJ
                                        <br>
                                        <h6 >{{ $item->formatted_created_at ?? '' }}</h6>
                                    </div>
                                    <div class="col-md-4 col-6 text-muted"> Department
                                        <br>
                                        <h6 >{{ $item->department->name ?? '' }}</h6>
                                    </div>
                                    <div class="col-md-4 col-6 text-muted"> Designation
                                        <br>
                                        <h6 >{{ $item->designation->name ?? '' }}</h6>
                                    </div>
                                    <div class="col-md-4 col-6 text-muted"> Country
                                        <br>
                                        <h6 >{{ $item->myCountry->name ?? '' }}</h6>
                                    </div>

                                    @if ($item->bio)
                                        <div class="col-md-12 col-12 text-muted"> Bio
                                            <br>
                                            <h6 class="text-black">{{ $item->bio ?? '' }}</h6>
                                        </div>
                                    @endif
                                    @if ($item->narrator)
                                        <div class="col-md-12 col-12 text-muted"> Narrator
                                            <br>
                                            <h6 class="text-black">{{ $item->narrator ?? '' }}</h6>
                                        </div>
                                    @endif
                                    @if ($item->introduction)
                                        <div class="col-md-12 col-12 text-muted"> Introduction
                                            <br>
                                            <h6 class="text-black">{{ $item->introduction ?? '' }}</h6>
                                        </div>
{{--
                                    @if($item->bio)
                                    <div class="col-md-12 col-12 text-muted"> Bio
                                        <br>
                                        <h6 >{{ $item->bio ?? '' }}</h6>
                                    </div>
                                    @endif
                                    @if($item->narrator)
                                    <div class="col-md-12 col-12 text-muted"> Narrator
                                        <br>
                                        <h6 >{{ $item->narrator ?? '' }}</h6>
                                    </div>
                                    @endif
                                    @if($item->introduction)
                                    <div class="col-md-12 col-12 text-muted"> Introduction
                                        <br>
                                        <h6 >{{ $item->introduction ?? '' }}</h6>
                                    </div>
--}}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade  @if (!request()->has('active')) show active @endif" id="prompt_details"
                            role="tabadmin" aria-labelledby="pills-note-tab">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 p-0">
                                        <div class="col-12 mt-2 d-flex justify-content-between">
                                            <h5>Prompt</h5>
                                            <a href="javascript:void(0)" onclick="copyPrompt()"
                                                class="text-copy btn btn-light mr-2" title="Copy Prompt" id="copyPrompt">
                                                <i class="ik ik-copy"> </i> Copy
                                            </a>
                                        </div>
                                        <hr class="m-1 mb-2">

                                        <div class="col-md-12 col-12 text-muted">
                                            <h6 class="text-black" id="prompt">{!! nl2br(generateAgentPrompt($item)) !!}</h6>
{{--
                                        <div class="col-md-12 col-12 text-muted"> 
                                            <h6  id="prompt">{!! nl2br(generateAgentPrompt($item)) !!}</h6>
--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('script')
        <script>
            function copyPrompt() {
                event.preventDefault();
                var prompt = $('#prompt').text();
                var textarea = document.createElement('textarea');
                textarea.value = prompt;
                document.body.appendChild(textarea);
                textarea.select();
                try {
                    document.execCommand('copy');
                    $('#copyPrompt').html('Copied');
                    setTimeout(() => {
                        $('#copyPrompt').html(`<i class="ik ik-copy"> </i> Copy`);
                    }, 500);
                } catch (err) {
                    console.error('Unable to copy text to clipboard', err);
                } finally {
                    // Remove the temporary textarea from the document
                    document.body.removeChild(textarea);
                }
            }
        </script>
    @endpush
@endsection
