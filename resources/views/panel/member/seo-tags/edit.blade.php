@extends('layouts.main')
@section('title', $seoTag->getPrefix() .' SEO Tag Edit')
@section('content')
@php
    @$breadcrumb_arr = [['name' => @$label, 'url' => route('panel.admin.slider-types.index'), 'class' => ''], ['name' => @$seoTag->getPrefix(), 'url' => route('panel.admin.seo-tags.index'), 'class' => ''], ['name' => 'Edit', 'url' => route('panel.admin.seo-tags.index'), 'class' => 'active']];
@endphp

@push('head')
    <link rel="stylesheet" href="{{ asset('backend/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
    <style>
        .bootstrap-tagsinput {
            width: 100%;
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
                        <h5> @lang('admin/ui.edit_title')  {{ @$label ?? '--' }}</h5>
                        <span> @lang('admin/ui.update_record') {{ @$label ?? '--' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @include('panel.admin.include.breadcrumb')
            </div>
        </div>
    </div>
    <!-- start message area-->
    @include('panel.admin.include.message')
    <!-- end message area-->
    <form action="{{ route('panel.admin.seo-tags.update', $seoTag->id) }}" method="post" class="ajaxForm">
        @csrf

        <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip=""
        regex="" validation="" value="update" />
        <div class="row">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h3> @lang('admin/ui.seo_tag') </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <x-label name="meta_title" validation="common_meta_title" tooltip="edit_seo_tags_title" />
                                    <x-input name="title" placeholder="Enter Title" type="text"
                                        tooltip="edit_seo_tags_title" regex="meta" validation="common_meta_title"
                                        value="{{ @$seoTag->title ?? '' }}"  />

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group ">

                                        <x-label name="meta_keyword" validation="common_meta_title" tooltip="edit_seo_tags_keyword" />
                                        <x-input name="keyword" id="tags" placeholder="Enter Title" type="text"
                                            tooltip="edit_seo_tags_keyword" regex="meta" validation="common_meta_title"
                                            value="{{ @$seoTag->keyword ?? '' }}"  />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group ">

                                    <x-label name="meta_description" validation="" tooltip="add_seo_tags_description" />
                                    <x-textarea regex="" validation="" value="{{ @$seoTag->description ?? '' }}" name="description"
                                    id="description" placeholder="Enter Description " />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                <div class="card ">
                    <div class="card-header d-flex justify-content-between">
                        <h3> @lang('admin/ui.seo_tag')  @lang('admin/ui.code') </h3>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <x-label name="code" validation="common_code" tooltip="edit_seo_tags_code" />
                                    <x-input name="code" placeholder="Enter Code" readonly type="text"
                                        tooltip="edit_seo_tags_code" regex="code" validation="common_code"
                                        value="{{ @$seoTag->code ?? '' }}" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group ">
                                    <x-label name="remark" validation="" tooltip="edit_seo_tags_remark" />
                                    <x-textarea regex="" validation="" value="{{ @$seoTag->remark ?? ''  }}" name="remark"
                                    id="remark" placeholder="Enter Remark " />

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group {{ @$errors->has('is_permanent') ? 'has-error' : '' }}">
                                    @php
                                        $isPermanent_arr = ["Yes", "No"];
                                        $selectedOption = old('is_permanent',  $seoTag->is_permanent);
                                    @endphp
                                    <x-label name="is_permanent" validation="commonname" tooltip="add_website_page_permanent" />
                                    <x-radio name="is_permanent" type="radio" :arr="@$isPermanent_arr" :selected="$selectedOption"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button class="btn btn-primary floating-btn ajax-btn" type="submit"> @lang('admin/ui.update') </button>
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

