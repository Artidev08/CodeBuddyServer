@extends('layouts.main')
@section('title', @$label)

@php
    $breadcrumb_arr = [['name' => $label, 'url' => 'javascript:void(0);', 'class' => 'active']];
@endphp
@push('head')
{{-- INITIALIZE SHIMMER & INIT LOAD --}}
<script>
    window.onload = function() {
        $('#ajax-container').show();
        document.getElementById('reset').click();
    };
</script>
{{-- END INITIALIZE SHIMMER & INIT LOAD --}}
@endpush
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ @$label ?? '--' }}</h5>
                            <span>List of {{ @$label ?? '--' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb')
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mx-auto">
                <div class="card">

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="last-month" role="tabpanel"
                            aria-labelledby="seo_tags_tab">
                            <div class="card-body">
                                <div class="row gutters-10">
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="card-header d-flex justify-content-between mt-0"
                                                    style="padding-top: 0px!important; padding-bottom:5px;">
                                                    <h3 class="">{{ @$label }}</h3>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="d-flex justify-content-right float-end">
                                                            <a href="{{ route('panel.admin.seo-tags.create') }}"
                                                                class="btn btn-sm btn-outline-primary mr-2"
                                                                title="Add SEO Tag"><i class="fa fa-plus"
                                                                    aria-hidden="true"></i>
                                                                @lang('admin/ui.add') </a>
                                                        </div>
                                                        @if (getSetting('seo_tags_bulk_upload', @$setting) || getSetting('seo_tags_bulk_delete', @$setting))
                                                            <form action="{{ route('panel.admin.seo-tags.bulk-action') }}"
                                                                method="POST" id="bulkAction" class="d-flex">
                                                                @csrf
                                                                <input type="hidden" name="ids" id="bulk_ids">
                                                                <div>
                                                                    <button
                                                                        class="dropdown-toggle p-0 custom-dopdown bulk-btn btn btn-light"
                                                                        type="button" id="dropdownMenu1"
                                                                        data-toggle="dropdown" aria-haspopup="true"
                                                                        aria-expanded="false"><i
                                                                            class="ik ik-more-vertical fa-lg pl-1"></i></button>
                                                                    <ul class="dropdown-menu multi-level" role="menu"
                                                                        aria-labelledby="dropdownMenu">
                                                                        @if (getSetting('seo_tags_bulk_upload', @$setting))
                                                                            <a href="javascript:void(0);"
                                                                                class="dropdown-item text-primary fw-700"
                                                                                data-toggle="modal"
                                                                                data-target="#BulkStoreAgentModal"><i
                                                                                    class="ik ik-upload"></i> Bulk
                                                                                Upload</a>
                                                                        @endif
                                                                        @if (getSetting('seo_tags_bulk_delete', @$setting))
                                                                            <hr class="m-1">
                                                                            <button type="submit"
                                                                                class="dropdown-item bulk-action text-danger fw-700"
                                                                                data-value=""
                                                                                data-message="You want to delete these Control SEO?"
                                                                                data-action="delete"
                                                                                data-callback="bulkDeleteCallback"><i
                                                                                    class="ik ik-trash"> </i> Bulk
                                                                                Delete
                                                                            </button>
                                                                        @endif
                                                                    </ul>
                                                                </div>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div id="ajax-container" style="display: none;">
                                                    @include('panel.admin.seo-tags.load')
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade @if (request()->has('active') && request()->get('active') == 'setting') show active @endif" id="previous-month"
                            role="tabpanel" aria-labelledby="pills-setting-tab">
                            <div class="card-body">
                                <form action="{{ route('panel.admin.setting.store') }}" method="POST"
                                    enctype="multipart/form-data" class="ajaxForm">
                                    @csrf
                                    <x-input name="group_name" placeholder="Enter Name" type="hidden" tooltip=""
                                        regex="" validation="" value="{{ 'appearance_global_seo' }}" />
                                    <x-input name="active" placeholder="Enter Name" type="hidden" tooltip=""
                                        regex="" validation="" value="{{ 'setting' }}" />
                                    <x-input name="appearance_seo_group" placeholder="Enter Name" type="hidden"
                                        tooltip="" regex="" validation="" value="{{ 'seo_group' }}" />
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">{{ 'admin/ui.meta_title' }}
                                            <a href="javascript:void(0);" title="@lang('panel/admin/tooltip.global_seo_meta_title')"><i
                                                    class="ik ik-help-circle text-muted ml-1"></i></a>
                                        </label>
                                        <div class="col-md-8">
                                            <input type="text" pattern="[a-zA-Z]+.*"
                                                title="Please enter first letter alphabet and at least one alphabet character is required."class="form-control"
                                                placeholder="Title" name="seo_meta_title"
                                                value="{{ getSetting('seo_meta_title') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">{{ 'admin/ui.meta_description' }}
                                            <a href="javascript:void(0);" title="@lang('panel/admin/tooltip.global_seo_meta_description')"><i
                                                    class="ik ik-help-circle text-muted ml-1"></i></a>
                                        </label>
                                        <div class="col-md-8">
                                            <textarea class="resize-off form-control" placeholder="Description" name="seo_meta_description">{{ getSetting('seo_meta_description') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 col-from-label">{{ 'admin/ui.keywoard' }}
                                            <a href="javascript:void(0);" title="@lang('panel/admin/tooltip.global_seo_meta_keywords')"><i
                                                    class="ik ik-help-circle text-muted ml-1"></i></a>
                                        </label>
                                        <div class="col-md-8">
                                            <textarea class="resize-off form-control" placeholder="Keyword, Keyword" name="seo_meta_keywords">{{ getSetting('seo_meta_keywords') }}</textarea>
                                            <small class="text-muted">{{ 'Separate with coma' }}</small>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="logo" class="col-sm-3 col-form-label"> @lang('admin/ui.meta_image')
                                            <a href="javascript:void(0);" title="@lang('panel/admin/tooltip.global_seo_meta_image')"><i
                                                    class="ik ik-help-circle text-muted ml-1"></i></a>
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="file" name="seo_meta_image" class="file-upload-default">
                                            <div class="input-group col-xs-12">
                                                <input type="text" class="form-control file-upload-info" disabled
                                                    placeholder="Upload Logo">
                                                <span class="input-group-append">
                                                    <button class="file-upload-browse btn btn-success" type="button">
                                                        @lang('admin/ui.upload') </button>
                                                </span>
                                            </div>
                                            <div class="file-preview box"></div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-primary"> @lang('admin/ui.update') </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (getSetting('seo_tags_table_filter', @$setting))
        @include('panel.admin.seo-tags.include.filter')
    @endif
    @if (getSetting('seo_tags_bulk_upload', @$setting))
        @include('panel.admin.seo-tags.include.bulk-upload')
    @endif
@endsection

@push('script')
    @include('panel.admin.include.bulk-script')
    {{-- START HTML TO EXCEL INIT --}}
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script>
        function html_table_to_excel(type) {
            var table_core = $("#support-table").clone();
            var clonedTable = $("#support-table").clone();
            clonedTable.find('[class*="no-export"]').remove();
            clonedTable.find('[class*="d-none"]').remove();
            $("#support-table").html(clonedTable.html());
            var data = document.getElementById('support-table');

            var file = XLSX.utils.table_to_book(data, {
                sheet: "sheet1"
            });
            XLSX.write(file, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            });
            XLSX.writeFile(file, 'SeoTagFile.' + type);
            $("#support-table").html(table_core.html());
        }

        $(document).on('click', '#export_button', function() {
            html_table_to_excel('xlsx');
        });
    </script>
    {{-- END HTML TO EXCEL INIT --}}

    {{-- START RESET BUTTON INIT --}}
    <script>
        $('#reset').click(function() {
            fetchData("{{ route('panel.admin.seo-tags.index') }}");
            window.history.pushState("", "", "{{ route('panel.admin.seo-tags.index') }}");
            $('#TableForm').trigger("reset");
            $(document).find('.close.off-canvas').trigger('click');
            $('#status').select2('val', "");
            $('#status').trigger('change');
        });
    </script>
    {{-- END RESET BUTTONINIT --}}

@endpush
