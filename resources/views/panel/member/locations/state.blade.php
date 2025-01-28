@extends('layouts.main')
@section('title', $country->getPrefix() .' State Show')
@section('content')
@php
    $breadcrumb_arr = [['name' => $country->name, 'url' => route('panel.admin.locations.country'), 'class' => ''], ['name'
    => 'State', 'url' => 'javascript:void(0);', 'class' => 'active']];
@endphp

@push('head')
 {{-- INITIALIZE SHIMMER & INIT LOAD --}}
 <script>
    window.onload = function() {
        $('#ajax-container').show();
        fetchData("{{ route('panel.admin.locations.state') }}");
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
                        <h5> @lang('admin/ui.state') </h5>
                        <span> @lang('admin/ui.list_of') @if (request()->get('country'))
                            {{ @$country->name ?? '--' }}
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
                    <h3> @lang('admin/ui.state') </h3>
                    <a href="javasript:void(0);" data-toggle="modal" data-target="#AddStateModal"
                        class="btn btn-icon btn-sm btn-outline-primary" title="Add State"><i class="fa fa-plus"
                            aria-hidden="true"></i></a>
                </div>
                <div id="ajax-container" style="display: none;">
                    @include('panel.admin.locations.loads.state')
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="AddStateModal" tabindex="-1" role="dialog" aria-labelledby="AddStateModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('panel.admin.locations.state.store') }}" method="post">
                @csrf
                <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip="" regex="" validation=""
                    value="state-create" />

                <x-input name="country_id" placeholder="Enter Name" type="hidden" tooltip="" regex="" validation=""
                    value="{{ request()->get('country') }}" />

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"> @lang('admin/ui.add')
                        @lang('admin/ui.state') </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <x-label name="name" validation="country_name" tooltip="state_name_visible_publicly" />
                        <x-input name="name" placeholder="Enter State Name" type="text"
                            tooltip="state_name_visible_publicly" regex="" validation="country_name"
                            value="{{ old('name') }}" />
                    </div>
                    <div class="form-group">
                        <x-label name="state" validation="country_name" tooltip="state_code_visible_publicly" />
                        <x-input name="iso2" placeholder="Enter State Code" type="text"
                            tooltip="state_name_visible_publicly" regex="" validation="country_name"
                            value="{{ old('iso2') }}" />
                    </div>

                    <div class="form-group">
                        <x-label name="code" validation="country_name" tooltip="flip_code_visible_publicly" />
                        <x-input name="fips_code" placeholder="Enter Fips Code" type="text"
                            tooltip="flip_code_visible_publicly" regex="" validation="country_name"
                            value="{{ old('fips_code') }}" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> @lang('admin/ui.close')
                    </button>
                    <button type="submit" class="btn btn-primary ajax-btn"> @lang('admin/ui.save_changes')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="EditStateModal" tabindex="-1" role="dialog" aria-labelledby="EditStateModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('panel.admin.locations.state.update') }}" method="post">
                @csrf
                <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip="" regex="" validation="" value="state-update" />
                <x-input name="country_id" placeholder="Enter Name" type="hidden" tooltip="" regex="" validation="" value="{{ request()->get('country') }}" />
                <x-input name="id" placeholder="Enter Name" type="hidden" tooltip="" regex="" validation=""
                    value="state_id" id="edit_state_id" />

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"><i
                        class="ik ik-edit mr-2"></i> @lang('admin/ui.edit')
                        @lang('admin/ui.state') </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for=""> @lang('admin/ui.state') @lang('admin/ui.name') <span class="text-danger">*</span></label>
                        <x-input name="name" placeholder="Enter State Name" type="text"
                            tooltip="flip_code_visible_publicly" regex="" validation="country_name"
                            value="{{ old('name') }}" id="edit_name" />
                    </div>
                    <div class="form-group">
                        <label for=""> @lang('admin/ui.state') @lang('admin/ui.code') <span class="text-danger">*</span></label>
                        <x-input name="iso2" placeholder="Enter State Code" type="text"
                            tooltip="flip_code_visible_publicly" regex="" validation="country_name"
                            value="{{ old('iso2') }}" id="edit_iso2"/>
                    </div>
                    <div class="form-group">
                        <label for=""> @lang('admin/ui.fips_code') </label>
                        <x-input name="fips_code" placeholder="Enter Fips Code" type="text"
                            tooltip="flip_code_visible_publicly" regex="" validation="country_name"
                            value="{{ old('fips_code') }}" id="edit_fips_code"/>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"> @lang('admin/ui.close')
                    </button>
                    <button type="submit" class="btn btn-primary ajax-btn"> @lang('admin/ui.save_changes')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('script')
{{-- START HTML TO EXCEL INIT --}}
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script>
        function html_table_to_excel(type) {
            var table_core = $("#stateTable").clone();
            var clonedTable = $("#stateTable").clone();
            clonedTable.find('[class*="no-export"]').remove();
            clonedTable.find('[class*="d-none"]').remove();
            $("#stateTable").html(clonedTable.html());
            var data = document.getElementById('stateTable');

            var file = XLSX.utils.table_to_book(data, {
                sheet: "sheet1"
            });
            XLSX.write(file, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            });
            XLSX.writeFile(file, 'UserFile.' + type);
            $("#stateTable").html(table_core.html());
        }

        $(document).on('click', '#export_button', function() {
            html_table_to_excel('xlsx');
        });
    </script>
    {{-- END HTML TO EXCEL INIT --}}

    {{-- START EDIT STATE INIT --}}
    <script>
        $(document).on('click', '.editState', function() {
            var record = $(this).data('row');
            console.log(record)
            $('#edit_state_id').val(record.id);
            $('#edit_name').val(record.name);
            $('#edit_iso2').val(record.iso2);
            $('#edit_fips_code').val(record.fips_code);
            $('#EditStateModal').modal('show');
        });
    </script>
    {{-- END EDIT STATE INIT --}}
     {{-- START CUSTOM JS INIT --}}
     <script>
        $('#reset').click(function() {
            fetchData("{{ route('panel.admin.locations.state') }}");
            window.history.pushState("", "", "{{ route('panel.admin.locations.state') }}");
            $('#TableForm').trigger("reset");
            $(document).find('.close.off-canvas').trigger('click');
        });
    </script>
    {{-- END CUSTOM JS INIT --}}
@endpush
