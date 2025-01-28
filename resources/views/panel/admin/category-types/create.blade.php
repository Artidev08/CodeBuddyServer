@extends('layouts.main')
@section('title', @$label .' Add')
@section('content')

@php
    $breadcrumb_arr = [['name' => @$label, 'url' => 'javascript:void(0);', 'class' => ''], ['name' => 'Add', 'url' => 'javascript:void(0);', 'class' => 'active']];
@endphp

<div class="container-fluid container-fluid-height">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-grid bg-blue"></i>
                    <div class="d-inline">
                        <h5> @lang('admin/ui.create_new')  {{ @$label ?? '' }}</h5>
                        <span> @lang('admin/ui.add_new') {{ @$label ?? '' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @include('panel.admin.include.breadcrumb')
            </div>
        </div>
    </div>
    <div class="row">
        <!-- start message area-->
        @include('panel.admin.include.message')
        <!-- end message area-->
        <div class="col-md-6 mx-auto">
            <div class="card ">
                <div class="card-header">
                    <h3> @lang('admin/ui.add')  {{ @$label ?? '' }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('panel.admin.category-types.store') }}" method="post" class="ajaxForm">
                        @csrf
                        <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip=""
                        regex="" validation="" value="create" />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group {{ @$errors->has('name') ? 'has-error' : '' }}">
                                    <x-label name="display_name" validation="category_display_name" tooltip="add_category_types_name" />
                                    <x-input name="name" placeholder="Enter Display Name" type="text"
                                        tooltip="add_category_types_name" regex="name" validation="category_display_name"
                                        value="{{ old('display_name') }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ @$errors->has('code') ? 'has-error' : '' }}">
                                        <x-label name="code" validation="common_code" tooltip="add_category_types_code" />
                                        <x-input name="code" placeholder="Enter Code" type="text"
                                            tooltip="add_category_types_code" regex="code" validation="category_group_code"
                                            value="{{ old('code') }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ @$errors->has('allowed_level') ? 'has-error' : '' }}">
                                    <x-label name="allowed_level" validation="category_group_level" tooltip="" />
                                    <x-select name="allowed_level" value="{{ request('allowed_level') }}" label="allowed_level" class="select2" validation="category_group_level" id="allowed_level" :arr="[
                                        '1' => ['name' => '1 - One Level'],
                                        '2' => ['name' => '2 - Two Level'],
                                        '3' => ['name' => '3 - Three Level']
                                    ]"/>
                                </div>
                            </div>
                            <div class="col-md-6 mt-1">
                                <div class="form-group {{ @$errors->has('is_permanent') ? 'has-error' : '' }}">
                                    @php
                                    $isPermanent_arr = ["Yes","No"];
                                @endphp
                                <x-label name="is_permanent" validation="paragraph_type" tooltip="add_site_content_managements_permanent" />
                                <x-radio name="is_permanent" type="radio"  value="{{ old('is_permanent') ?? 1 }}" :arr="@$isPermanent_arr"/>
                                </div>

                            </div>
                            <div class="col-md-12">
                                <div class="form-group {{ @$errors->has('remark') ? 'has-error' : '' }}">
                                    <x-label name="remark" validation="" tooltip="add_category_types_remark" />
                                    <x-textarea regex="" validation="" value="{{ old('remark') }}" name="remark"
                                    id="remark" placeholder="Enter Remark" rows="2"/>
                                </div>
                            </div>
                        </div>
                        <button type="submit"
                            class="btn btn-primary floating-btn ajax-btn "> @lang('admin/ui.create') </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
    {{-- START JS HELPERS INIT --}}
    <script>
        $('#name').on('keyup', function() {
            const input = $(this).val();
            const output = input
                .split(' ')
                .map((word, i) => {
                    if (i === 0) return word.toLowerCase().replace(/\b\w/g, s => s.toUpperCase());
                    return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
                })
                .join('');
            $('#code').val(output);
        });
    </script>
    {{-- END JS HELPERS INIT --}}

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

    {{--START CUSTOME JS INIT --}}
    <script>
    </script>
    {{--END CUSTOME JS INIT --}}
@endpush
