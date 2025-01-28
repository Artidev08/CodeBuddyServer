@extends('layouts.main')
@section('title', 'SEO Tag Add')
@section('content')

    @php
        @$breadcrumb_arr = [
            ['name' => @$label, 'url' => 'javascript:void(0);', 'class' => ''],
            ['name' => 'Add' . ' ' . @$label, 'url' => 'javascript:void(0);', 'class' => 'active'],
        ];
    @endphp

    @push('head')
        <link rel="stylesheet" href="{{ asset('panel/admin/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
        <style>
            .bootstrap-tagsinput {
                width: 100%;
            }
        </style>
    @endpush

    <div class="container-fluid container-fluid-height">
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
        <form action="{{ route('panel.admin.seo-tags.store') }}" method="post" class="ajaxForm">
            @csrf
            <div class="row">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header">
                            <h3>{{ @$label ?? '' }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <x-label name="meta_title" validation="common_meta_title"
                                            tooltip="add_seo_tags_title" />
                                        <x-input name="title" placeholder="Enter Title" type="text"
                                            tooltip="add_seo_tags_title" regex="meta" validation="common_meta_title"
                                            value="{{ old('title') }}" />
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <x-label name="meta_keywords" validation="common_meta_keywords"
                                            tooltip="add_seo_tags_keyword" />
                                        <x-input name="keyword" placeholder="Enter Keyword" type="text"
                                            tooltip="add_seo_tags_keyword" regex="meta" validation="common_meta_keywords"
                                            value="{{ old('keyword') }}" />

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <x-label name="meta_description" validation="common_meta_description"
                                            tooltip="add_seo_tags_description" />
                                        <x-textarea regex="short_description" validation="common_meta_description"
                                            value="{{ old('description') }}" name="description" id="description"
                                            placeholder="Enter Description " />

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip="" regex=""
                        validation="" value="create" />
                    <div class="card ">
                        <div class="card-header d-flex justify-content-between">
                            <h3>{{ @$label ?? '' }} @lang('admin/ui.code') </h3>

                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <x-label name="code" validation="common_code" tooltip="add_seo_tags_code" />
                                        <x-input name="code" placeholder="Enter Code" type="text"
                                            tooltip="add_seo_tags_code" regex="code" validation="common_code"
                                            value="{{ old('code') }}" />

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <x-label name="remark" validation="seo_remark" tooltip="add_seo_tags_remark" />
                                        <x-textarea regex="short_description" validation="common_meta_description"
                                            value="{{ old('remark') }}" name="remark" id="remark"
                                            placeholder="Enter Remark " />

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group {{ @$errors->has('is_permanent') ? 'has-error' : '' }}">
                                        @php
                                            $isPermanent_arr = ['Yes', 'No'];
                                            $selectedOption = old('is_permanent', 'Yes');
                                        @endphp
                                        <x-label name="is_permanent" validation="commonname"
                                            tooltip="add_website_page_permanent" />
                                        <x-radio name="is_permanent" type="radio" :arr="@$isPermanent_arr" :selected="$selectedOption" />
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button id="submit" type="submit" class="btn btn-primary floating-btn ajax-btn"> @lang('admin/ui.create') </button>
        </form>
    </div>
@endsection

@push('script')
    {{-- START TAGINPUT INIT --}}
    <script src="{{ asset('panel/admin/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script>
        $('#tags').tagsinput('items');
    </script>
    {{-- END TAGINPUT INIT --}}

    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            var redirectUrl = "{{ url('admin/seo-tags') }}";
            var response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);

        })
    </script>
    {{-- END AJAX FORM INIT --}}
@endpush
