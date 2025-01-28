@extends('layouts.main')
@section('title', 'Permission')
@section('content')
@php
    @$breadcrumb_arr = [['name' => 'Permissions', 'url' => 'javascript:void(0);', 'class' => 'active']];
@endphp

@push('head')
 {{-- INITIALIZE SHIMMER & INIT LOAD --}}
 <script>
    window.onload = function() {
        $('#ajax-container').show();
        fetchData("{{ route('panel.admin.permissions.index') }}");
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
                        <h5> @lang('admin/ui.permissions') </h5>
                        <span> @lang('admin/ui.permission_subtitle') </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @include('panel.admin.include.breadcrumb')
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <!-- start message area-->
        @include('panel.admin.include.message')
        <!-- end message area-->
        @if (env('DEV_MODE') == 1)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3> @lang('admin/ui.AddPermission') </h3>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample" method="POST" action="{{ route('panel.admin.permissions.store') }}">
                            @csrf

                            <x-input name="create" placeholder="Enter Name" type="hidden" tooltip=""
                                regex="role_name" validation="role_name" value="request_with" />


                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <x-label name="permission" validation="permission_name"
                                            tooltip="add_permission_name" />
                                        <x-input name="permission" placeholder="Enter Name" type="text"
                                            tooltip="add_permission_name" regex="" validation="permission_name"
                                            value="{{ old('permission') }}" />


                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        @php
                                            $user_arr = App\Models\User::whereRoleIs('User')
                                                ->orderBy('first_name', 'ASC')
                                                ->get();

                                        @endphp
                                    <x-label name="assign_to_role" validation="permission_assign_role" tooltip="add_permission_roles" />
                                    <x-select name="roles[]" validation="permission_assign_role" id="roles" class="form-control select2" valueName="id"   value="{{ old('id', @$role->id) }}" label="Roles" option_name="display_name" :arr="@$roles" :isMultiple="1"/>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <x-label name="exampleInputEmail3" validation="group_name" tooltip="add_permission_group" />
                                        <x-input name="exampleInputEmail3" placeholder="Enter the permission group name" type="text"
                                            tooltip="add_permission_group" regex="name" validation="group_name"
                                            value="{{ old('group') }}" />

                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <button type="submit"
                                            class="btn btn-primary btn-rounded ajax-btn"> @lang('admin/ui.create_permission')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <div @if (env('DEV_MODE') != 1) class="col-md-12" @else class="col-md-8" @endif>
            <div class="card">
                <div id="ajax-container" style="display: none;">
                    @include('panel.admin.permissions.load')
                </div>
            </div>
        </div>
        {{-- @endif --}}
    </div>
    <div class="row">

    </div>
</div>

@endsection
@push('script')
    {{-- START HTML TO EXCEL BUTTON INIT --}}
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script>
        function html_table_to_excel(type) {
            var table_core = $("#permissions_table").clone();
            var clonedTable = $("#permissions_table").clone();
            clonedTable.find('[class*="no-export"]').remove();
            clonedTable.find('[class*="d-none"]').remove();
            $("#permissions_table").html(clonedTable.html());
            var data = document.getElementById('permissions_table');

            var file = XLSX.utils.table_to_book(data, {
                sheet: "sheet1"
            });
            XLSX.write(file, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            });
            XLSX.writeFile(file, 'PermissionFile.' + type);
            $("#permissions_table").html(table_core.html());
        }
    </script>
    {{-- END HTML TO EXCEL BUTTON INIT --}}

    {{-- START JS HELPERS INIT --}}
    <script>
        $(document).ready(function() {
            $(document).find('#roles').select2();
        })

        $(document).on('click', '#export_button', function() {
            html_table_to_excel('xlsx');
        });

        $(document).on('click', '.confirm-btn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var msg = $(this).data('msg') ?? "You won't be able to revert back!";
            $.confirm({
                draggable: true,
                title: 'Are You Sure!',
                content: msg,
                type: 'blue',
                typeAnimated: true,
                buttons: {
                    tryAgain: {
                        text: 'Confirm',
                        btnClass: 'btn-blue',
                        action: function() {
                            window.location.href = url;
                        }
                    },
                    close: function() {}
                }
            });
        });
    </script>
    {{-- END JS HELPERS INIT --}}
@endpush
