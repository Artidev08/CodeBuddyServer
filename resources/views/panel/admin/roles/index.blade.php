@extends('layouts.main')
@section('title', @$label)
@section('content')
<!-- push external head elements to head -->
@push('head')
<style>
    .li-position {
        min-width: 7rem;
        width: 8rem;
        transform: translate3d(-48px, 19px, 0px) !important;
    }

    .role-scrollable {
        height: 300px;
        overflow: scroll;
        overflow-x: hidden;
        padding: 10px;
        gap: 1rem 4rem;
    }

    .admn-roles {
        padding: 1px 0 12px 15px;
        background-color: rgb(250 250 250);
    }
</style>
@endpush


<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-grid bg-blue"></i>
                    <div class="d-inline">
                        <h5>{{ __(@$label ?? '') }}</h5>
                        <span> @lang('admin/ui.define_roles_of_user') </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-sm-flex d-lg-block">
                <nav class="breadcrumb-container" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('/') }}"><i class="ik ik-home"></i></a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="javacript:void(0);">{{ __(@$label ?? '') }}</a>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <!-- start message area-->
        @include('panel.admin.include.message')
        <!-- end message area-->

        @if (auth()->user()->isAbleTo('add_role'))
        <div class="col-md-12">
            <div class="card mb-2">
                <form class="forms-sample" method="POST" action="{{ route('panel.admin.roles.store') }}">
                    <div class="card-header d-flex justify-content-between">

                        <h3 class="p-0 m-0"> @lang('admin/ui.add') {{ __(@$label ?? '') }} </h3>
                        <div class="form-group text-right p-0 m-0 ajax-btn">
                            <x-button>
                                @lang('admin/ui.permissions_title')
                            </x-button>
                        </div>
                    </div>
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <x-label name="role_name" validation="role_name" tooltip="add_role_name" />
                                    <x-input name="role" placeholder="Enter Name" type="text" tooltip="add_role_name"
                                        regex="role_name" validation="role_name" value="{{ old('role') }}" />
                                </div>
                                <div class="form-group">
                                    <x-label name="display_name" validation="role_display_name"
                                        tooltip="add_role_display_name" />
                                    <x-input name="display_name" placeholder="Display Name" type="text"
                                        tooltip="add_role_display_name" regex="role_display_name"
                                        validation="role_display_name" value="{{ old('display_name') }}" />
                                </div>

                                <div class="form-group">
                                    <x-label name="description" validation="common_description"
                                        tooltip="add_role_description" />
                                    <x-textarea regex="short_description" validation="common_description"
                                        value="{{ old('description') }}" name="description" id="description"
                                        placeholder="Enter Description" />
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6> @lang('admin/ui.assign_permissions') </h6>
                                    </div>
                                    <div>
                                        <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input allPermissionChecked">
                                            <span class="custom-control-label ">Select All</span>
                                        </label>
                                    </div>

                                </div>
                                <hr class="mb-0 mt-0">
                                <div class="row mb-0 role-scrollable">
                                    @foreach (@$groups as $group)
                                    <div class="col-sm-5 admn-roles">
                                        <div class="mt-3 mb-0">
                                            <label for="" class="fw-600 m-0 f-18">{{ __(@$group->group ?? '') }}</label>
                                        </div>
                                        @foreach (\App\Models\Permission::whereGroup($group->group)->get() as $key =>
                                        $permission)
                                        <label class="custom-control mb-0 custom-checkbox">
                                            <input type="checkbox" class="custom-control-input permission_checkbox"
                                                id="item_checkbox" name="permissions[]"
                                                value="{{ @$permission->name ?? '' }}">
                                            <span class="custom-control-label">
                                                {{ @$permission->name ?? '' }}
                                            </span>
                                        </label>
                                        @endforeach
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card p-3">
                <div class="card-header">
                    <h3>{{ __(@$label) }}</h3>
                </div>
                <div class="card-body">
                    @foreach (@$roles as $role)
                    <div class="d-flex">
                        @if (@$role->name != 'Super Admin')
                        <div class="dropdown">
                            <button style="background: transparent;border:none;" class="dropdown-toggle p-0"
                                type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false"><i class="ik ik-more-vertical pl-1"></i></button>
                            <ul class="dropdown-menu multi-level li-position" role="menu"
                                aria-labelledby="dropdownMenu">
                                @if (auth()->user()->isAbleTo('edit_role'))
                                <a
                                        href="{{ route('panel.admin.roles.edit', secureToken(@$role->id ?: '')) }}"
                                        title="Edit Role" class="btn btn-sm"><li class="dropdown-item p-0 fw-400"><i class="ik ik-edit">
                                        </i> Edit</li> </a>
                                @endif
                                <hr class="m-1 b-0">
                                @if (env('DEV_MODE') == 1)
                                <li class="dropdown-item p-0"><a
                                        href="{{ route('panel.admin.roles.destroy', @$role->id ?: '') }}"
                                        title="Delete Role"
                                        class="btn btn-sm text-danger delete-item text-danger fw-700"><i
                                            class="ik ik-trash mr-2"> </i>Delete</a>
                                </li>
                                @endif
                            </ul>
                        </div>
                        @endif
                        <h6 class="ml-2 mr-1">
                            {{ @$role->display_name ?? '' }} |
                        </h6>
                        <p class="text-muted">{{ @$role->description ?? '' }}</p>
                    </div>
                    @if (@$role->display_name == 'Super Admin')
                        <span class="badge badge-success m-1"> @lang('admin/ui.all_permissions') </span>
                    @else
                    @foreach (@$role->permissions()->get() as $item)
                        <span class="badge badge-dark m-1">{{ @$item->name ?? '--' }}</span>
                    @endforeach
                    @endif
                    <hr>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- push external js -->
@push('script')
{{-- START JS HELPERS INIT --}}
<script src="{{ asset('backend/plugins/select2/dist/js/select2.min.js') }}"></script>

<script>
    $(document).ready(function() {
        var searchable = [];
        var selectable = [];
    });
    $(document).on('click', '.allPermissionChecked', function() {
        if ($(this).prop("checked") == true) {
            $('.permission_checkbox').prop('checked', true);
        } else {
            $('.permission_checkbox').prop('checked', false);
        }
    });
</script>
{{-- END JS HELPERS INIT --}}
@endpush
@endsection