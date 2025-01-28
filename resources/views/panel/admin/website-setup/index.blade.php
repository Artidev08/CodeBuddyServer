@extends('layouts.main')
@section('title', @$label)
@section('content')
@php
    $breadcrumb_arr = [['name' => $label, 'url' => 'javascript:void(0);', 'class' => 'active']];
@endphp
@push('head')
{{-- INITIALIZE SHIMMER & INIT LOAD --}}
<script>
    window.onload = function() {
        $('#ajax-container').show();
        fetchData("{{ route('panel.admin.website-pages.index') }}");
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
                        <h5>{{ __(@$label ?? '--') }}</h5>
                        <span>  @lang('admin/ui.website_page_heading') </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">

                <div>
                    @include('panel.admin.include.breadcrumb')
                </div>
            </div>
            @include('panel.admin.modal.sitemodal', [
                'title' => 'How to use',
                'content' =>
                    'You need to create a unique code and call the unique code with paragraph content helper.',
            ])
        </div>
    </div>

    <div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between" style="margin-top: -8px;">
                        <h3 class="mb-0">  @lang('admin/ui.all_pages') </h3>
                        <div class="d-flex justify-content-right ">
                            <div class="d-flex justicy-content-right mt-2 ml-1">
                                @if ($permissions->contains('add_page'))
                                    <a href="{{ route('panel.admin.website-pages.create') }}"
                                        class="btn btn-sm btn-outline-primary mr-2" title="Add New Pages"><i
                                            class="fa fa-plus" aria-hidden="true"></i>   @lang('admin/ui.add')
                                    </a>
                                @endif
                            </div>
                            @if (getSetting('pages_activation_bulk_delete', @$setting))
                            <form action="{{ route('panel.admin.website-pages.bulk-action') }}" method="POST"
                                id="bulkAction" class="d-flex mr-2">
                                @csrf
                                <input type="hidden" name="ids" id="bulk_ids">
                                <div>

                                    <button style="background: display: block; margin-top: 9px !important;"
                                        class="dropdown-toggle p-0 custom-dopdown bulk-btn btn btn-light" type="button"
                                        id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false"><i class="ik ik-more-vertical fa-lg pl-1"></i></button>
                                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                        @if (getSetting('pages_activation_bulk_delete', @$setting))
                                        <button type="submit" class="dropdown-item bulk-action text-danger fw-700"
                                            data-value="" data-message="You want to delete these Website Pages?"
                                            data-action="delete" data-callback="bulkDeleteCallback"> <i
                                                class="ik ik-trash"> </i> Bulk Delete
                                        </button>

                                        <hr class="m-1">

                                        <a href="javascript:void(0)" class="dropdown-item bulk-action"
                                            data-value="0" data-status="Unpublish" data-column="is_published"
                                            data-message="You want to mark these Website Page as Unpublish?"
                                            data-action="columnUpdate" data-callback="bulkColumnUpdateCallback">Mark
                                            as
                                            Unpublish
                                        </a>

                                        <a href="javascript:void(0)" class="dropdown-item bulk-action"
                                            data-value="1" data-status="Publish" data-column="is_published"
                                            data-message="You want to mark these Website Page as Publish?"
                                            data-action="columnUpdate" data-callback="bulkColumnUpdateCallback">Mark
                                            as Publish
                                        </a>
                                        @endif

                                    </ul>
                                </div>
                            </form>
                            @endif
                        </div>
                    </div>
                    <form action="{{ route('panel.admin.website-pages.index') }}" method="GET" id="TableForm"
                        action="">
                        <div id="ajax-container" style="display: none;">
                            @include('panel.admin.website-setup.load')
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@if (getSetting('pages_activation_table_filter', @$setting))
    @include('panel.admin.website-setup.include.filter')
@endif
@endsection

@push('script')
    @include('panel.admin.include.bulk-script')
    {{-- START HTML TO EXCEL INIT --}}
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script>
        function tableHeadIconFixer(clonedTable) {
            clonedTable.find('i.icon-head').each(function() {
                var dataTitle = $(this).data('title');
                $(this).replaceWith(dataTitle);
            });
            return clonedTable;
        }

        function html_table_to_excel(type) {
            var table_core = $("#page_table").clone();
            var clonedTable = $("#page_table").clone();
            clonedTable.find('[class*="no-export"]').remove();
            clonedTable.find('[class*="d-none"]').remove();
            clonedTable = tableHeadIconFixer(clonedTable);
            $("#page_table").html(clonedTable.html());

            var data = document.getElementById('page_table');

            var file = XLSX.utils.table_to_book(data, {
                sheet: "sheet1"
            });
            XLSX.write(file, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            });
            XLSX.writeFile(file, 'PageFile.' + type);
            $("#page_table").html(table_core.html());
        }

        $(document).on('click', '#export_button', function() {
            html_table_to_excel('xlsx');
        });
    </script>
    {{-- END HTML TO EXCEL INIT --}}

     {{-- START RESET BUTTON INIT --}}
     <script>
        $('#reset').click(function() {
            fetchData("{{ route('panel.admin.website-pages.index') }}");
            window.history.pushState("", "", "{{ route('panel.admin.website-pages.index') }}");
            $('#TableForm').trigger("reset");
            $(document).find('.close.off-canvas').trigger('click');
            $('#status').select2('val', "");
            $('#status').trigger('change');
        });
    </script>
    {{-- END RESET BUTTON INIT --}}

    {{-- START SELECT 2 BUTTON INIT --}}
    <script>
        $('.select2').select2();
    </script>
    {{-- END SELECT 2 BUTTON INIT --}}
@endpush
