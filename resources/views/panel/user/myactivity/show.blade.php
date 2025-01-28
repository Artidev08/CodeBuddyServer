@extends('layouts.user')

@section('meta_data')
    @php
        $meta_title = $userlog->getPrefix() . ' ' . __('user/ui.activity_show') . ' | ' . getSetting('app_name');
        $meta_description = 'Details of the user activity log entry.';
        $meta_keywords = 'user activity, log details, activity details';
        $meta_motto = getSetting('site_motto');
        $meta_author_name = 'Defenzelite';
        $meta_author_email = 'support@defenzelite.com';
        $meta_reply_to = getSetting('app_email');
        $meta_img = ' ';
    @endphp
@endsection

@push('head')
    <style>
        .error {
            color: red;
        }

        .table thead {
            background-color: #f8f9fa;
        }

        .table thead th {
            border-bottom: 0px;
        }

        p {
            margin-bottom: 0px;
        }

        .badge {
            font-size: 0.85rem; /* Slightly larger text */
            padding: 0.35rem 0.7rem;
            border-radius: 0.25rem;
            color: #fff; /* White text color for all badges */
            display: inline-block;
            text-align: center;
        }

        .badge-warning {
            background-color: #ffc107; /* Bright yellow */
            color: #212529; /* Dark text for contrast */
        }

        .badge-success {
            background-color: #28a745; /* Green */
        }

        .badge-danger {
            background-color: #dc3545; /* Red */
        }

        .badge-info {
            background-color: #17a2b8; /* Blue */
        }

        .badge-secondary {
            background-color: #6c757d; /* Gray */
        }

        .badge-light {
            background-color: #f8f9fa; /* Light gray */
            color: #212529; /* Dark text for contrast */
        }

        .badge-dark {
            background-color: #343a40; /* Dark gray */
        }

        /* General override to ensure contrast */
        .badge-secondary,
        .badge-light {
            color: #212529; /* Dark text color for contrast */
        }

        .card-header {
            background: linear-gradient(to right, #4e73df, #224abe);
            color: white;
        }

        .btn-primary {
            background-color: #4e73df;
            border-color: #4e73df;
        }

        .btn-primary:hover {
            background-color: #224abe;
            border-color: #224abe;
        }

        .card {
            border: 1px solid #e3e6f0;
        }

        .separator {
            border-top: 1px solid #e3e6f0;
            margin: 20px 0;
        }

        .text-right {
            text-align: right;
        }
    </style>
@endpush

@section('content')
    <div class="mt-3">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-activity bg-blue"></i>
                        <div class="d-inline">
                            <h4>{{ @$userlog->getPrefix() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10">
                <!-- User Activity Log Information Card -->
                <div class="card mb-2">
                    <div class="card-header d-flex justify-content-between">
                        <h3 class="mb-0">@lang('user/ui.activity_details'): </h3>
                    </div>
                    <div class="card-body" style="margin-top: -20px !important;">
                        <h6 class="fw-700">@lang('user/ui.log_details')</h6>
                        <table class="table mb-0">
                            <tbody>
                                <tr>
                                    <td class="p-2">@lang('user/ui.id'):</td>
                                    <td class="text-right p-2">{{ @$userlog->getPrefix() }}</td>
                                </tr>
                                <tr>
                                    <td class="p-2">@lang('user/ui.activity'):</td>
                                    <td class="text-right p-2">{{ @App\Models\User::ACTIVITES[$userlog->activity]['label'] ?? '--' }}</td>
                                </tr>
                                <tr>
                                    <td class="p-2">@lang('user/ui.incident'):</td>
                                    <td class="text-right p-2">{{ $userlog->name }}</td>
                                </tr>
                                <tr>
                                    <td class="p-2">@lang('user/ui.ip_address'):</td>
                                    <td class="text-right p-2">{{ $userlog->ip_address }}</td>
                                </tr>
                                <tr>
                                    <td class="p-2">@lang('user/ui.version'):</td>
                                    <td class="text-right p-2">{{ $userlog->version }}</td>
                                </tr>
                                <tr>
                                    <td class="p-2">@lang('user/ui.platform'):</td>
                                    <td class="text-right p-2">{{ $userlog->platform }}</td>
                                </tr>
                                <tr>
                                    <td class="p-2">@lang('user/ui.occur_at'):</td>
                                    <td class="text-right p-2">{{ \Carbon\Carbon::parse($userlog->created_at)->format('d-m-Y H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- <!-- Back to list button aligned to the right -->
        <div class="text-right" style="margin-left:800px">
            <a href="{{ route('panel.user.my-activity.index') }}" class="btn btn-primary">
                <i class="bi bi-arrow-left"></i> @lang('user/ui.back_to_list')
            </a>
        </div> --}}
    </div>
@endsection
