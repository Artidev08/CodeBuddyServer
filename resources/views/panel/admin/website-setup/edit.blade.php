@extends('layouts.main')
@section('title', $websitePage->getPrefix().' ' .$label.' Edit')
@section('content')
@push('head')
    <link rel="stylesheet" href="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.css') }}">
    <style>
        .bootstrap-tagsinput {
            width: 100%;
        }
    </style>
@endpush
@php
    $breadcrumb_arr = [['name' => @$label, 'url' => route('panel.admin.website-pages.index'), 'class' => 'active']];
@endphp
<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-edit bg-blue"></i>
                    <div class="d-inline">
                        <h5>  @lang('admin/ui.edit_title')  {{ @$label ?? '--' }}</h5>
                        <span>  @lang('admin/ui.website_page_heading') </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @include('panel.admin.include.breadcrumb')
            </div>
        </div>
    </div>
    <form class="ajaxForm" action="{{ route('panel.admin.website-pages.update', @$websitePage->id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip=""
        regex="" validation="" value="update" />
        <x-input name="id" placeholder="Enter Name" type="hidden" tooltip=""
        regex="" validation="" value="{{ $websitePage->id }}" />
        <input type="hidden" name="request_with" value="update">
        <input type="hidden" name="id" value="{{ $websitePage->id }}">
        <div class="row">
            <div class="col-md-7">
                <div class="card mb-bottom">
                    <div class="card-header">
                        <h6 class="fw-600 mb-0">  @lang('admin/ui.page_content') </h6>
                    </div>
                    <div class="card-body px-0">
                        <div class="col-md-12">
                            <div class="form-group">
                                <x-label name="title" validation="common_title" tooltip="edit_website_page_title" />
                                        <x-input name="title" placeholder="Enter Title" type="text"
                                            tooltip="edit_website_page_title" regex="title" validation="common_title"
                                            value="{{ @$websitePage->title }}" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group {{ @$errors->has('slug') ? 'has-error' : '' }}">
                                <label for="slug" class="control-label">  @lang('admin/ui.slug') @if(@validation('website_page_slug')['pattern']['mandatory'])<span class="text-danger">*</span> @endif</label><a data-toggle="tooltip"
                                    href="javascript:void(0);" title="  @lang('admin/tooltip.edit_website_page_slug') "><i
                                        class="ik ik-help-circle text-muted ml-1"></i></a>
                                <input class="form-control w-100 w-md-auto" name="slug" type="text"
                                    pattern="[a-zA-Z]+.*"
                                    title="Please enter first letter alphabet and at least one alphabet character is required."
                                    title="Please enter first letter alphabet and at least one alphabet character is required."
                                    id="title" value="{{ @$websitePage->slug }}" placeholder="Enter Slug">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group ">
                                <label for="content" class="control-label">  @lang('admin/ui.page_content') <span
                                        class="text-danger">*</span></label><a data-toggle="tooltip"
                                    href="javascript:void(0);" title="  @lang('admin/tooltip.add_website_page_content')  "><i
                                        class="ik ik-help-circle text-muted ml-1"></i></a>
                                <div id="content-holder">
                                    <div class="init-ck-editor">
                                        {!! @$websitePage->content ?? '--' !!}
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card mb-bottom">
                    <div class="card-header">
                        <h6 class="fw-600 mb-0">  @lang('admin/ui.seo_field') </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between" style="margin-bottom: -15px">
                                    <div class="form-group">
                                            <x-label name="meta_title" for="name" validation="common_meta_title" tooltip="edit_website_page_meta_title" />
                                    </div>
                                    <div>
                                        <button id="auto_fill_title"
                                            class="p-0 btn btn-link btn-sm text-primary float-end fw-800"><i
                                                class="ik ik-corner-left-down"></i>  @lang('admin/ui.auto_fill')
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <x-input name="page_meta_title" id="page_meta_title" placeholder="Enter Title" type="text"
                                    tooltip="edit_website_page_meta_title" regex="meta" validation="common_meta_title"
                                    value="{{ @$websitePage->meta['title']  }}"  />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <x-label name="meta_keywords" validation="common_meta_keywords" tooltip="edit_website_page_keywords" />
                                    <x-input name="page_keywords" id="tags" placeholder="Enter Keyword" type="text"
                                        tooltip="edit_website_page_keywords" regex="meta" validation="common_meta_keywords"
                                        value="{{ @$websitePage->meta['keywords'] }}"  />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <x-label name="meta_description" validation="common_meta_description" tooltip="edit_website_page_meta_description" />
                                    <x-textarea regex="short_description" validation="common_meta_description" value="{{ @$websitePage->meta['description']  }}" name="page_meta_description"
                                    id="page_meta_description" placeholder="Enter Description " />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-5">

                <div class="card mb-bottom">
                    <div class="card-header d-flex justify-content-between">
                        <h6 class="fw-600 mb-0">  @lang('admin/ui.visibility') </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    @php
                                    $checkbox_arr = ["is_published"];
                                @endphp
                                <x-checkbox name="status"  class="js-switch switch-input"  value="1" type="checkbox" tooltip=""  :arr="@$checkbox_arr"/>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="logo" class="control-label">  @lang('admin/ui.banner')
                                            @lang('admin/ui.image') </label><a data-toggle="tooltip"
                                        href="javascript:void(0);" title="  @lang('admin/tooltip.edit_website_page_banner') "><i
                                            class="ik ik-help-circle text-muted ml-1"></i></a>
                                    <div class="input-images" data-input-name="page_meta_image"
                                        id="website_page_image" data-input-accept="{{ validation('blog_image')['dimension'] ?? '' }}"
                                        data-label="Drag & Drop product images here or click to browse"></div>
                                        <small class="text-danger fw-700">
                                            @lang('admin/ui.file_allowed') {{ str_replace('image/','',validation('blog_image')['pattern']['allowed_extensions']) }}

                                            @lang('admin/ui.file_dimensions') {{ validation('blog_image')['dimension'] ?? 'Any Filetypes' }}


                                        </small>
                                    @if (@$websitePage->getMedia('page_meta_image')->count() > 0)
                                        <img style="border-radius: 10px;width:100px;height:80px;" id="item_image_img"
                                            src="{{ @$websitePage->getFirstMediaUrl('page_meta_image') }}"
                                            class="mt-3" style="border-radius: 10px;width:100%;height:80px;" />
                                        <br><a
                                            href="{{ route('panel.admin.website-pages.destroy-media', @$websitePage->id) . '?media=page_meta_image' }}"
                                            class="btn btn-sm mt-2 btn-danger delete-item"
                                            style="color: aliceblue !important;">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-bottom">

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group {{ @$errors->has('is_permanent') ? 'has-error' : '' }}">
                                    @php
                                        $isPermanent_arr = ["Yes", "No"];
                                        $selectedOption = old('is_permanent',  $websitePage->is_permanent);
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
        <button type="submit"
            class="btn btn-primary floating-btn ajax-btn">  @lang('admin/ui.update') </button>
    </form>
</div>

@endsection

@push('script')
    <script src="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>
    <script src="{{ asset('panel/admin/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
    <script src="{{ asset('admin/js/ajaxForm.js') }}"></script>
    {{-- START AUTOFILE TITLE JS INIT --}}
    <script>
        $('#auto_fill_title').on('click', function(e) {
            e.preventDefault();
            var title_name = $('#title').val();
            $('#page_meta_title').val(title_name);
        })
    </script>
    {{-- END AUTOFILE TITLE JS INIT --}}

    {{-- START DECOUPLEDEDITOR INIT --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/decoupled-document/ckeditor.js"></script>
    <script>
        let editor;
        $(window).on('load', function() {
            $('#txt_area').addClass('ck-editor');
            DecoupledEditor
                .create(document.querySelector('.ck-editor'), {
                    ckfinder: {
                        uploadUrl: "{{ route('panel.admin.media.ckeditor.upload') . '?_token=' . csrf_token() }}",
                    }
                })
                .then(newEditor => {
                    editor = newEditor;
                    const toolbarContainer = document.querySelector('#toolbar-container');

                    toolbarContainer.appendChild(editor.ui.view.toolbar.element);
                })
                .catch(error => {
                    console.error(error);
                });
        });
    </script>
    {{-- END DECOUPLEDEDITOR INIT --}}

    {{-- START TAGINPUT INIT --}}
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
            ckEditors.forEach((editor, index) => {
                if(index == 0) {
                    data.append('content', editor.getData());
                }else{
                    data.append(`editor_content_${index}`, editor.getData());
                }
            });
            var redirectUrl = "{{ url('admin/website-pages') }}";
            var response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
        });
    </script>
    {{-- END AJAX FORM INIT --}}

    {{-- START JS HELPERS INIT --}}
    <script>
        function slugFunction() {
            var x = document.getElementById("slugInput").value;
            document.getElementById("slugOutput").innerHTML = "{{ url('/page/') }}/" + x;
        }

        function convertToSlug(Text) {
            return Text
                .toLowerCase()
                .replace(/ /g, '-')
                .replace(/[^\w-]+/g, '');
        }

        $('#title').on('keyup', function() {
            $('#slugInput').val(convertToSlug($('#title').val()));
            slugFunction();
        });
        $('#website_pages').DataTable({
            responsive: true
        });
    </script>
    {{-- END JS HELPERS INIT --}}
@endpush
