@extends('layouts.main')
@section('title', 'Notification')
@section('content')
    @php
        $breadcrumb_arr = [['name' => 'Notification', 'url' => 'javascript:void(0);', 'class' => 'active']];
    @endphp

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5> @lang('admin/ui.Notification') </h5>
                            <span> @lang('admin/ui.list_of_notification') </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb')
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3> @lang('admin/ui.Notification') </h3>
                    </div>

                    <div class="shimmer-content">
                        @include('panel.admin.notifications.include.shimmer')
                    </div>
                    <div class="card-body notification-content d-none">
                        <div id="ajax-container">
                            @include('panel.admin.notifications.load')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('panel.admin.notifications.include.filter')

@endsection

@push('script')
    {{-- START HTML TO EXCEL INIT --}}
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

    {{-- END HTML TO EXCEL INIT --}}
    <script>
        $(document).ready(function() {
            $('.notification-content').removeClass('d-none');
            $('.shimmer-content').addClass('d-none');

        });
    </script>

    {{-- START RESET BUTTON INIT --}}
    <script>
        $('#reset').click(function() {
            fetchData("{{ route('panel.admin.notifications.index') }}");
            window.history.pushState("", "", "{{ route('panel.admin.notifications.index') }}");
            $('#TableForm').trigger("reset");
            $(document).find('.close.off-canvas').trigger('click');
        });
    </script>
    {{-- END RESET BUTTON INIT --}}
@endpush
