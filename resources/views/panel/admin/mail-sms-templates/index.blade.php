@extends('layouts.main')
@section('title', __('admin/ui.left_sidebar_mail_sms_templates'))
@section('content')
@php
    @$breadcrumb_arr = [['name' =>__('admin/ui.left_sidebar_mail_sms_templates'), 'url' => 'javascript:void(0);', 'class' => 'active']];
@endphp

</style>
@push('head')
{{-- INITIALIZE SHIMMER & INIT LOAD --}}
<script>
    window.onload = function() {
        $('#ajax-container').show();
        // document.getElementById('reset').click();
        fetchData("{{ route('panel.admin.templates.index',['type' => request()->get('type')]) }}");
    };
</script>
{{-- END INITIALIZE SHIMMER & INIT LOAD --}}
@endpush
<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-grid bg-blue"></i>
                    <div class="d-inline">
                        <h5> @lang('admin/ui.left_sidebar_mail_sms_templates') </h5>
                        <span> @lang('admin/ui.list_of')  {{__('admin/ui.left_sidebar_mail_sms_templates')}}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @include('panel.admin.include.breadcrumb')
            </div>
        </div>
    </div>
    <div class="row">
        <!-- start message area-->
    @include('panel.admin.include.message')
    <!-- end message area-->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between mobile-d-block" style="margin-top: -10px">
                    <h3>{{__('admin/ui.left_sidebar_mail_sms_templates')}}</h3>

                    <div class="mr-4">
                        <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link @if (!request()->has('type') || request()->get('type') == 1) active @endif"
                                    href="{{ route('panel.admin.templates.index', ['type' => 1]) }}"> @lang('admin/ui.blog_mail_template') </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if (request()->has('type') && request()->get('type') == 2) active @endif"
                                    href="{{ route('panel.admin.templates.index', ['type' => 2]) }}"> @lang('admin/ui.blog_sms_template') </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if (request()->has('type') && request()->get('type') == 3) active @endif"
                                    href="{{ route('panel.admin.templates.index', ['type' => 3]) }}"
                                    role="tab"
                                    aria-controls="pills-timeline"
                                    aria-selected="true"> @lang('admin/ui.blog_whatsapp_template') </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link @if (request()->has('type') && request()->get('type') == 4) active @endif"
                                    href="{{ route('panel.admin.templates.index', ['type' => 4]) }}"
                                    role="tab"
                                    aria-controls="pills-timeline"
                                    aria-selected="true"> @lang('admin/ui.blog_prompt_template') </a>
                            </li>
                        </ul>
                    </div>

                    <div class="d-flex justify-content-between">

                        <form action="{{ route('panel.admin.templates.index') }}" class="d-flex ajaxForm"
                                method="GET" id="TableForm">

                            <div class="dropdown mt-6 mb-0">

                                @if ($permissions->contains('add_mail_template'))
                                    <a href="{{ route('panel.admin.templates.create') }}"
                                        class="btn btn-sm btn-outline-primary mr-2"
                                        title="Add New Mail SMS Templates"><i
                                            class="fa fa-plus" aria-hidden="true"></i>  @lang('admin/ui.add')
                                    </a>
                                @endif
                            </div>
                        </form>
                        @if (getSetting('templates_bulk_delete', @$setting))
                        <form action="{{ route('panel.admin.templates.bulk-action') }}" method="POST"
                                id="bulkAction">
                            @csrf
                            <input type="hidden" name="ids" id="bulk_ids">

                            <button class="dropdown-toggle p-0 custom-dopdown bulk-btn btn btn-light"
                                    type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false"><i class="ik ik-more-vertical pl-1"></i></button>
                            <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                @if (getSetting('templates_bulk_delete', @$setting))
                                <button type="submit" class="dropdown-item bulk-action text-danger fw-700"
                                        data-value="" data-message="You want to delete these Templates?"
                                        data-action="delete"
                                        data-callback="bulkDeleteCallback"><i class="ik ik-trash"> </i>
                                        @lang('admin/ui.bulk_delete')
                                </button>
                                    @endif
                                @if (env('IS_DEV') != 1)
                                    <a class="btn btn-icon btn-sm btn-outline-success ml-3" href="#"
                                        data-toggle="modal" data-target="#siteModal"
                                        style="width: 20px!important; height:20px!important; line-height: 20px !important;"><i
                                            class="fa fa-info"></i></a>
                                @endif
                            </ul>

                        </form>
                        @endif
                    </div>
                </div>
                <div id="ajax-container" style="display: none;">
                    @include('panel.admin.mail-sms-templates.load')
                </div>
            </div>
        </div>
        @include('panel.admin.modal.sitemodal', [
            'title' => 'How to use',
            'content' =>
                'You need to create a unique code and call the unique code with paragraph content helper.',
        ])
    </div>
</div>
@if (getSetting('templates_table_filter', @$setting))
    @include('panel.admin.mail-sms-templates.include.filter')
@endif


@push('script')
    @include('panel.admin.include.bulk-script')
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function (e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            var response = postData(method, route, 'json', data, null, null);
            if (typeof (response) != "undefined" && response !== null && response.status == "success") {

            }
        })
    </script>
    {{-- END AJAX FORM INIT --}}

    {{-- START HTML TO EXCEL INIT --}}
    <script>
        function tableHeadIconFixer(clonedTable) {
            clonedTable.find('i.icon-head').each(function () {
                var dataTitle = $(this).data('title');
                $(this).replaceWith(dataTitle);
            });
            return clonedTable;
        }

        function html_table_to_excel(type) {
            var table_core = $("#mailSmsTable").clone();
            var clonedTable = $("#mailSmsTable").clone();
            clonedTable.find('[class*="no-export"]').remove();
            clonedTable.find('[class*="d-none"]').remove();
            clonedTable = tableHeadIconFixer(clonedTable);
            $("#mailSmsTable").html(clonedTable.html());
            var data = document.getElementById('mailSmsTable');

            var file = XLSX.utils.table_to_book(data, {
                sheet: "sheet1"
            });
            XLSX.write(file, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            });
            XLSX.writeFile(file, 'leadFile.' + type);
            $("#mailSmsTable").html(table_core.html());
        }

        $(document).on('click', '#export_button', function () {
            html_table_to_excel('xlsx');
        });
    </script>
    {{-- END HTML TO EXCEL INIT --}}

    {{-- START RESET BUTTON INIT --}}
    <script>
        $('#reset').click(function () {
            fetchData("{{ route('panel.admin.templates.index') }}");
            window.history.pushState("", "", "{{ route('panel.admin.templates.index') }}");
            $('#TableForm').trigger("reset");
            $(document).find('.close.off-canvas').trigger('click');
            $('#status').select2('val', "");
            $('#status').trigger('change');
        });
    </script>
    {{-- END RESET BUTTON INIT --}}
@endpush
@endsection
