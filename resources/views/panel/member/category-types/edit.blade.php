@extends('layouts.main')
@section('title', $categoryType->getPrefix() .' Category-Type Edit')
@section('content')
@php
    $breadcrumb_arr = [['name' => $label, 'url' => route('panel.admin.category-types.index'), 'class' => ''], ['name' => $categoryType->getPrefix(), 'url' => route('panel.admin.category-types.index'), 'class' => ''], ['name' => 'Edit', 'url' => route('panel.admin.category-types.index'), 'class' => 'active']];
@endphp

<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-grid bg-blue"></i>
                    <div class="d-inline">
                        <h5> @lang('admin/ui.edit') {{ @$label ?? '' }}</h5>
                        <span> @lang('admin/ui.update_a_record_for')  {{ @$label ?? '' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @include('panel.admin.include.breadcrumb')
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card ">
                <div class="card-header">
                    <div class="col-sm-12">
                        <div class="d-flex justify-content-between">
                            <h3 style="margin-left: -5%"> @lang('admin/ui.update')  {{ @$label ?? '' }}</h3>
                            <span>Allowed Level {{ @$categoryType->allowed_level ?? '' }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('panel.admin.category-types.update', $categoryType->id ?? '') }}"
                        method="post" class="ajaxForm">
                        @csrf

                        <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip=""
                        regex="" validation="" value="update" />
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group {{ @$errors->has('name') ? 'has-error' : '' }}">
                                    <x-label name="display_name" validation="category_display_name" tooltip="edit_category_types_name" />
                                    <x-input name="name" placeholder="Enter Display Name" type="text"
                                        tooltip="edit_category_types_name" regex="name" validation="category_display_name"
                                        value="{{ isset($categoryType->name) ? @$categoryType->name : ''}}" />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group {{ @$errors->has('remark') ? 'has-error' : '' }}">
                                    <x-label name="remark" validation="category_group_remark" tooltip="edit_category_types_remark" />
                                    <x-input readonly name="remark" placeholder="Enter remark here" type="textarea"
                                        tooltip="edit_category_types_remark" regex="remark" validation="category_display_name"
                                        value="{{  isset($categoryType->remark) ? @$categoryType->remark : ''}}" />
                                </div>
                            </div>
                        </div>
                        <button type="submit"
                            class="btn btn-primary floating-btn ajax-btn"> @lang('admin/ui.save_update') </button>
                    </form>
                </div>
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
            var redirectUrl = "{{ url('admin/category-types') }}";
            var response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);

        })
    </script>
    {{-- END AJAX FORM INIT --}}
    
    {{-- START CUSTOME JS INIT --}}
    <script>
        
    </script>
    {{-- END CUSTOME JS INIT --}}
@endpush
