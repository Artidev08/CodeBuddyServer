@extends('layouts.main')
@section('title', @$label)
@section('content')
@php
    $breadcrumb_arr = [['name' => 'Slider Group', 'url' => route('panel.admin.slider-types.index'), 'class' => ''],
        ['name' => $label, 'url' => 'javascript:void(0);', 'class' => 'active']];
@endphp
@push('head')
{{-- INITIALIZE SHIMMER & INIT LOAD --}}
<script>
    window.onload = function() {
        $('#ajax-container').show();
        fetchData("{{ route('panel.admin.sliders.index') }}");
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
                        <h5>{{ @$label }}</h5>

                        <span> @lang('admin/ui.list_of')  @if (request()->get('sliderTypeId'))

                                of {{ @$sliderType->title ?? '--' }}
                            @endif
                        </span>
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
                    <h3>{{ @$sliderType->title ?? '--' }}</h3>
                    <div class="d-flex justicy-content-right">
                        <a href="{{ route('panel.admin.sliders.create', ['sliderTypeId' => request()->get('sliderTypeId')]) }}"
                            class="btn btn-sm btn-outline-primary mr-2" title="Add New Sliders"><i class="fa fa-plus"
                            aria-hidden="true"></i>  @lang('admin/ui.add')
                        </a>
                        <form action="{{ route('panel.admin.sliders.bulk-action') }}" method="POST">
                            @csrf
                            <input type="hidden" name="ids" id="bulk_ids">
                            <button style="background: transparent;border:none;"
                                    class="dropdown-toggle p-0 three-dots"
                                    type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false"><i class="ik ik-more-vertical pl-1"></i></button>
                            <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                <a href="javascript:void(0);" class="dropdown-item text-primary fw-700"
                                    data-toggle="modal" data-target="#BulkStoreAgentModal"><i
                                        class="ik ik-upload"></i>
                                    Bulk Upload</a>
                                <hr class="m-1">

                                <a href="javascript:void(0)" class="dropdown-item bulk-action" data-value="0"
                                    data-status="Unpublish" data-column="status"
                                    data-message="You want to mark Unpublish these items?" data-action="columnUpdate"
                                    data-callback="bulkColumnUpdateCallback">Mark as Unpublish
                                </a>

                                <a href="javascript:void(0)" class="dropdown-item bulk-action" data-value="1"
                                    data-status="Publish" data-column="status"
                                    data-message="You want to mark Publish these items?" data-action="columnUpdate"
                                    data-callback="bulkColumnUpdateCallback">Mark as Publish
                                </a>
                                <button type="submit" class="dropdown-item bulk-action text-danger fw-700"
                                        data-value="" data-message="You want to delete these items?"
                                        data-action="delete"
                                        data-callback="bulkDeleteCallback"><i class="ik ik-trash"></i> Bulk Delete
                                </button>
                            </ul>
                        </form>
                    </div>
                </div>
                <div id="ajax-container" style="display: none;">
                    @include('panel.admin.sliders.load')
                </div>
            </div>
        </div>
    </div>
</div>
@include('panel.admin.sliders.include.bulk-upload')

@endsection

@push('script')
    @include('panel.admin.include.bulk-script')
    {{-- START HTML TO EXCEL INIT --}}
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script>
        function html_table_to_excel(type) {
            var table_core = $("#sliderTable").clone();
            var clonedTable = $("#sliderTable").clone();
            clonedTable.find('[class*="no-export"]').remove();
            clonedTable.find('[class*="d-none"]').remove();
            $("#sliderTable").html(clonedTable.html());
            var data = document.getElementById('sliderTable');

            var file = XLSX.utils.table_to_book(data, {
                sheet: "sheet1"
            });
            XLSX.write(file, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            });
            XLSX.writeFile(file, 'SliderFile.' + type);
            $("#sliderTable").html(table_core.html());
        }

        $(document).on('click', '#export_button', function () {
            html_table_to_excel('xlsx');
        });
    </script>
    {{-- END HTML TO EXCEL INIT --}}
    {{-- START RESET BUTTON INIT --}}
    <script>
        $('#reset').click(function() {
            fetchData("{{ route('panel.admin.sliders.index') }}");
            window.history.pushState("", "", "{{ route('panel.admin.sliders.index') }}");
            $('#TableForm').trigger("reset");
            $(document).find('.close.off-canvas').trigger('click');
            $('#status').select2('val', "");
            $('#status').trigger('change');
        });
    </script>
    {{-- END RESET BUTTON INIT --}}
@endpush
