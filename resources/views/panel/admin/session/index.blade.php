@extends('layouts.main')
@section('title', __('admin/ui.user_session'))
@section('content')

@push('head')
 {{-- INITIALIZE SHIMMER & INIT LOAD --}}
 <script>
    window.onload = function() {
        $('#ajax-container').show();
        fetchData("{{ route('panel.admin.users.sessions', $user->id) }}");
    };
</script>
{{-- END INITIALIZE SHIMMER & INIT LOAD --}}
@endpush

@php
    /**
     * User Subscription
     *
     * @category ZStarter
     *
     * @ref zCURD
     * @author  Defenzelite <hq@defenzelite.com>
     * @license https://www.defenzelite.com Defenzelite Private Limited
     * @version <zStarter: 1.1.0>
     * @link    https://www.defenzelite.com
     */
    @$breadcrumb_arr = [['name' => __('admin/ui.user'), 'url' => 'javascript:void(0);', 'class' => ''], ['name' => __('admin/ui.sessions'), 'url' => 'javascript:void(0);', 'class' => 'active']];
@endphp

<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-grid bg-blue"></i>
                    <div class="d-inline">
                        <h5>{{ $user->full_name ?? '' }}</h5>
                        <span>@lang('admin/ui.sessions_list')</span>
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
                <div class="card-header d-flex justify-content-between">
                    <h3>@lang('admin/ui.sessions')</h3>
                    <div class="d-flex justify-content-right">
                        <form action="{{ route('panel.admin.users.session.bulk-action') }}" method="POST"
                            id="bulkAction" class="">
                            @csrf
                            <input type="hidden" name="ids" id="bulk_ids">
                            <div>
                                <button class="dropdown-toggle p-0 custom-dopdown bulk-btn btn btn-light "
                                    type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false"><i class="ik ik-more-vertical pl-1"></i></button>
                                <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                    <button type="submit" class="dropdown-item bulk-action text-danger fw-700"
                                        data-value="" data-message="You want to delete these session?"
                                        data-action="delete" data-callback="bulkDeleteCallback"><i class="ik ik-globe">
                                        </i> @lang('admin/ui.bulk_logout')
                                    </button>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="ajax-container" style="display: none;">
                    @include('panel.admin.session.load')
                </div>
            </div>
        </div>
    </div>
</div>
@include('panel.admin.session.include.filter')

@endsection
@push('script')
    @include('panel.admin.include.bulk-script')
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    {{-- END HTML TO EXCEL FILE INIT --}}
    <script>
            function html_table_to_excel(type) {
        var table_core = $("#table").clone();
        var clonedTable = $("#table").clone();
        clonedTable.find('[class*="no-export"]').remove();
        clonedTable.find('[class*="d-none"]').remove();
        $("#table").html(clonedTable.html());
        var data = document.getElementById('table');

        var file = XLSX.utils.table_to_book(data, {
            sheet: "sheet1"
        });
        XLSX.write(file, {
            bookType: type,
            bookSST: true,
            type: 'base64'
        });
        XLSX.writeFile(file, 'Session.' + type);
        $("#table").html(table_core.html());
    }

    $(document).on('click', '#export_button', function() {
        html_table_to_excel('xlsx');
    });
    </script>
    {{-- END HTML TO EXCEL FILE INIT --}}


    {{-- START RESET BUTTON INIT --}}

    <script>
        $('#reset').click(function() {
            fetchData("{{ route('panel.admin.users.sessions', $user->id) }}");
            window.history.pushState("", "", "{{ route('panel.admin.users.sessions', $user->id) }}");
            $('#TableForm').trigger("reset");
            $(document).find('.close.off-canvas').trigger('click');
        });
    </script>
    {{-- END RESET BUTTON INIT --}}

    {{-- START GETUSERS INIT --}}
    <script>
        $(document).ready(function() {
            getUsers();
        })
    </script>
    {{-- END GETUSERS INIT --}}
@endpush
