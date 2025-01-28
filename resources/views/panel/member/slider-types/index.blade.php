@extends('layouts.main')
@section('title', @$label)
@section('content')

    @php
        $breadcrumb_arr = [['name' => @$label, 'url' => 'javascript:void(0);', 'class' => 'active']];
    @endphp

    @push('head')
        {{-- INITIALIZE SHIMMER & INIT LOAD --}}
        <script>
            window.onload = function() {
                $('#ajax-container').show();
                fetchData("{{ route('panel.admin.slider-types.index') }}");
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
                            <h5>{{ @$label ?? '' }}</h5>

                            <span> @lang('admin/ui.list_of') {{ @$label ?? '' }}</span>

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
                        <h3>{{ @$label }}</h3>
                        <div class="d-flex">
                            @if ($permissions->contains('add_slider'))
                                <a href="{{ route('panel.admin.slider-types.create') }}"
                                    class="btn btn-sm btn-outline-primary mr-2" title="Add New Slider Type"><i
                                        class="fa fa-plus" aria-hidden="true"></i> @lang('admin/ui.add') </a>
                            @endif
                            @if (getSetting('slider_bulk_status_update', @$setting) || getSetting('slider_bulk_sync', @$setting))
                                <form action="{{ route('panel.admin.slider-types.bulk-action') }}" method="POST"
                                    id="bulkAction">
                                    @csrf
                                    <input type="hidden" name="ids" id="bulk_ids">
                                    <div>
                                        <button class="dropdown-toggle p-0 custom-dopdown bulk-btn btn btn-light "
                                            type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false"><i class="ik ik-more-vertical pl-1"></i></button>
                                        @if (getSetting('slider_bulk_status_update', @$setting))
                                            <ul class="dropdown-menu multi-level" role="menu"
                                                aria-labelledby="dropdownMenu">
                                                <a href="javascript:void(0)" class="dropdown-item bulk-action"
                                                    data-value="0" data-status="Unpublish" data-column="is_published"
                                                    data-message="You want to Mark As Unpublish these Slider Group?"
                                                    data-action="columnUpdate" data-callback="bulkColumnUpdateCallback">Mark
                                                    as
                                                    Unpublish
                                                </a>

                                                <a href="javascript:void(0)" class="dropdown-item bulk-action"
                                                    data-value="1" data-status="Publish" data-column="is_published"
                                                    data-message="You want to Mark As Publish these Slider Group?"
                                                    data-action="columnUpdate" data-callback="bulkColumnUpdateCallback">Mark
                                                    as
                                                    Publish
                                                </a>
                                        @endif
                                        @if (getSetting('slider_bulk_sync', @$setting))
                                            <hr class="m-1">
                                            <a href="#" class="dropdown-item bulk-action text-secondary fw-700"> Sync
                                                Slider
                                            </a>
                                        @endif
                                        </ul>
                                    </div>

                                </form>
                            @endif
                        </div>
                    </div>
                    <div id="ajax-container" style="display: none;">
                        @include('panel.admin.slider-types.load')
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

@endsection
@push('script')
    @include('panel.admin.include.bulk-script')
    {{-- START HTML TO EXCEL INIT --}}
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script>
        function html_table_to_excel(type) {
            var table_core = $("#sliderType").clone();
            var clonedTable = $("#sliderType").clone();
            clonedTable.find('[class*="no-export"]').remove();
            clonedTable.find('[class*="d-none"]').remove();
            $("#sliderType").html(clonedTable.html());
            var data = document.getElementById('sliderType');

            var file = XLSX.utils.table_to_book(data, {
                sheet: "sheet1"
            });
            XLSX.write(file, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            });
            XLSX.writeFile(file, 'leadFile.' + type);
            $("#sliderType").html(table_core.html());
        }

        $(document).on('click', '#export_button', function() {
            html_table_to_excel('xlsx');
        });
    </script>
    {{-- END HTML TO EXCEL INIT --}}
    {{-- START CUSTOM JS INIT --}}
    <script>
        $('#reset').click(function() {
            fetchData("{{ route('panel.admin.slider-types.index') }}");
            window.history.pushState("", "", "{{ route('panel.admin.slider-types.index') }}");
            $('#TableForm').trigger("reset");
            $(document).find('.close.off-canvas').trigger('click');
        });
    </script>
    {{-- END CUSTOM JS INIT --}}
@endpush
