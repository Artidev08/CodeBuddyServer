@extends('layouts.main')
@section('title', @$role->display_name . ' - ' . __('admin/ui.edit_roles'))
@section('content')

<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-award bg-blue"></i>
                    <div class="d-inline">
                        <h5>@lang('admin/ui.edit') {{ @$label }}</h5>
                        <span>@lang('Edit role & associate permissions') </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <nav class="breadcrumb-container" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ url('dashboard') }}"><i class="ik ik-home"></i></a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="#">{{ __(@$label) }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            {{ @$role->display_name ?? '--' }}
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
        <div class="col-md-12">
            <div class="card">
                <form class="forms-sample" method="POST" action="{{ route('panel.admin.roles.update', $role->id) }}">
                    <div class="card-header d-flex justify-content-between">
                        <h3>{{ __('admin/ui.edit') . ' ' . ($role->name ?? '') }}</h3>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-rounded ajax-btn"> @lang('admin/ui.update')
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @csrf

                        <x-input name="request_with" placeholder="Enter Role Name" type="hidden" tooltip=""
                            regex="role_name" validation="role_name" value="update" />


                        <x-input name="id" placeholder="Enter Name" type="hidden" tooltip="" regex="number"
                            validation="number" value="{{ @$role->id }}" />

                        <div class="row">

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <x-label name="role_name" validation="role_name" tooltip="add_role_name" />
                                    <x-input name="role" placeholder="Enter Name" type="text" tooltip="add_role_name"
                                        regex="role_name" validation="role_name" value="{{ @$role->name }}" />

                                </div>
                                <div class="form-group">
                                    <x-label name="display_name" validation="role_display_name"
                                        tooltip="add_role_display_name" />
                                    <x-input name="display_name" placeholder="Display Name" type="text"
                                        tooltip="add_role_display_name" regex="role_display_name"
                                        validation="role_display_name" value="{{ @$role->display_name }}" />
                                </div>
                                <div class="form-group">


                                    <x-label name="description" validation="role_description"
                                        tooltip="add_role_description" />
                                    <x-textarea regex="name" validation="common_description"
                                        value="{{ @$role->description ?? '--' }}" name="description" id="description"
                                        placeholder="Enter Description" />
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="d-flex justify-content-between">
                                    <h6>@lang('admin/ui.assign_permissions') </h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="all_item"
                                            name="privileges[all_item]" @if (@$role_permission !=null) checked @endif>
                                        <label class="pt-1 form-check-label" for="all_item">
                                            {{-- Select All Permissions --}}
                                        </label>
                                    </div>
                                </div>

                                <hr class="mb-0">
                                <div class="row mb-3">
                                    @foreach (@$allPermissions as $permission)
                                    <div class="col-sm-4">
                                        <div class="mt-3 mb-0">
                                            <label for="" class="fw-700 m-0">{{ __(@$permission->group) }}</label>
                                        </div>
                                        @foreach (explode(',', @$permission->permission_ids) as $key => $permission_id)
                                        <label class="custom-control custom-checkbox mb-0">
                                            <!-- check permission exist -->
                                            <input type="checkbox" class="custom-control-input bulk-group"
                                                id="item_checkbox" name="permissions[]" value="{{ @$permission_id }}"
                                                @if (in_array(@$permission_id, @$role_permission)) checked @endif>
                                            <span class="custom-control-label">
                                                <!-- clean unescaped data is to avoid potential XSS risk -->
                                                {{ explode(',', @$permission->permission_names)[@$key] }}
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
    </div>
</div>

@endsection

@push('script')

    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
                e.preventDefault();
                var route = $(this).attr('action');
                var method = $(this).attr('method');
                var data = new FormData(this);
                var response = postData(method, route, 'json', data, null, null);
                var redirectUrl = "{{ url('panel/admin/users') }}" + '?role=' + response.role;
                if (typeof(response) != "undefined" && response !== null && response.status == "success") {
                    window.location.href = redirectUrl;
                }
            })
            $('#all_item').on('change', function() {
                if (this.checked) {
                    $('.bulk-group').prop('checked', true);
                } else {
                    $('.bulk-group').prop('checked', false);
                }
            });
    </script>
    {{-- END AJAX FORM INIT --}}
@endpush