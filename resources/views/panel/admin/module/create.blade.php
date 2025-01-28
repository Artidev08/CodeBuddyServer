@extends('layouts.main')
@section('title', 'New Module Add')
@section('content')
    @php
        $breadcrumb_arr = [['name' => ' ', 'url' => 'javascript:void(0);', 'class' => '']];
    @endphp

    @push('head')
        <style>
            .btn:focus {
                box-shadow: none;
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
                            <h5> @lang('admin/tooltip.Create new module') </h5>
                            <span> @lang('admin/tooltip.Add a new record for Crud') </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb')
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-12">
                <form action="">
                    <div class="card ">
                        <div class="card-header">
                            <h3> @lang('admin/tooltip.Settings') </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 col-md-4">
                                    <div class="form-group">
                                        <label for="">Module Name <span class="text-danger">*</span></label>
                                        <input type="text" pattern="[a-zA-Z]+.*"
                                            title="Please enter first letter alphabet and at least one alphabet character is required."
                                            class="form-control" name="module_name">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <label for="">Model Name<span class="text-danger">*</span></label>
                                        <input type="text" pattern="[a-zA-Z]+.*"
                                            title="Please enter first letter alphabet and at least one alphabet character is required."
                                            class="form-control" name="model_name">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <label for="">Menu Title and Icon<span class="text-danger">*</span></label>
                                        <input type="text" pattern="[a-zA-Z]+.*"
                                            title="Please enter first letter alphabet and at least one alphabet character is required."class="form-control"
                                            name="menu_icon">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <label for="">Parent Menu<span class="text-danger">*</span></label>
                                        <input type="text" pattern="[a-zA-Z]+.*"
                                            title="Please enter first letter alphabet and at least one alphabet character is required."
                                            class="form-control" name="parent_menu">
                                        <small class="text-muted">For admin panel menu only</small>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <label for="">Roles<span class="text-danger">*</span></label>
                                        <select name="role" class="form-control select2" id="">
                                            <option value="0" aria-readonly="true">Select roles</option>
                                            @foreach ($roles as $role)
                                                <option value="0">{{ $role }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <label class="form-check-label">
                                        <input name="" type="checkbox" checked=""><span
                                            class="checkbox-label">Admin CRUD</span>
                                    </label>
                                    <label class="form-check-label">
                                        <input name="" type="checkbox" checked=""><span
                                            class="checkbox-label">User CRUD</span>
                                    </label>
                                </div>
                                <div class="col-12 col-md-12 my-2">
                                    <label class="form-check-label">
                                        <input name="" type="checkbox" checked=""><span
                                            class="checkbox-label">Soft Deletes</span>
                                    </label>
                                    <label class="form-check-label">
                                        <input name="" type="checkbox" checked=""><span
                                            class="checkbox-label">Generate API CRUD</span>
                                    </label>
                                </div>
                                <div class="col-12 col-md-12">
                                    <label class="form-check-label">
                                        <input name="" type="checkbox" checked=""><span
                                            class="checkbox-label">Create form</span>
                                    </label>
                                    <label class="form-check-label">
                                        <input name="" type="checkbox" checked=""><span
                                            class="checkbox-label">Edit form</span>
                                    </label>
                                    <label class="form-check-label">
                                        <input name="" type="checkbox" checked=""><span
                                            class="checkbox-label">Show page</span>
                                    </label>
                                    <label class="form-check-label">
                                        <input name="" type="checkbox" checked=""><span
                                            class="checkbox-label">Delete action</span>
                                    </label>
                                    <label class="form-check-label">
                                        <input name="" type="checkbox" checked=""><span
                                            class="checkbox-label">Multi-delete action</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3> @lang('admin/tooltip.Fields')</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="">&nbsp;</th>
                                            <th>Field Type</th>
                                            <th>Database Column</th>
                                            <th>Visual Title</th>
                                            <th>In List</th>
                                            <th>In Create</th>
                                            <th>In Edit</th>
                                            <th>In Show</th>
                                            <th>Is Sortable</th>
                                            <th>Required</th>
                                            <th>Edit</th>
                                            <th>Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td>auto_increment</td>
                                            <td>id</td>
                                            <td>ID</td>
                                            <td><i class="changeIcon fa fa-check text-success fa-2x"
                                                    data-field-status="1"></i></td>
                                            <td></td>
                                            <td></td>
                                            <td><button class="btn bg-white fw-600 changeIcon" type="button"
                                                    data-field-status="1"><i
                                                        class="fa fa-check text-success fa-2x"></i></button></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>datetime</td>
                                            <td>created_at</td>
                                            <td>Created at</td>
                                            <td><button class="btn bg-white fw-700 changeIcon" data-field-status="0"
                                                    type="button"><i class="fa fa-times text-danger fa-2x"></i></button>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td><button class="btn bg-white fw-600 changeIcon" data-field-status="0"
                                                    type="button"><i class="fa fa-times text-danger fa-2x"></i></button>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>auto_increment</td>
                                            <td>id</td>
                                            <td>ID</td>
                                            <td><button class="btn bg-white fw-700 changeIcon" type="button"><i
                                                        class="fa fa-times text-danger fa-2x"></i></button></td>
                                            <td></td>
                                            <td></td>
                                            <td><button class="btn bg-white fw-600 changeIcon" data-field-status="0"
                                                    type="button"><i class="fa fa-times text-danger fa-2x"></i></button>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>auto_increment</td>
                                            <td>id</td>
                                            <td>ID</td>
                                            <td><button class="btn bg-white fw-700 changeIcon" data-field-status="0"
                                                    type="button"><i class="fa fa-times text-danger fa-2x"></i></button>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td><button class="btn bg-white fw-600 changeIcon" type="button"><i
                                                        class="fa fa-times text-danger fa-2x"></i></button></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button data-toggle="modal" data-target="#createModule" class="btn btn-info btn-block"
                                type="button"><i class="fa fa-plus"></i>Add new field</button>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3> @lang('admin/tooltip.Table') </h3>
                        </div>
                        <div class="card-body">

                        </div>
                    </div>
                    <div class="">
                        <a class="btn btn-default float-right" href="">Cancel</a>
                        <button class="btn btn-primary float-right" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('panel.admin.include.modal.module-fields')
    <!-- push external js -->
    @push('script')
        <script>
            $(document).on('click', '.changeIcon', function() {
                $(this).parent()
                    .toggleClass('on')
                    .toggleClass('off');
            });
        </script>
    @endpush
@endsection
