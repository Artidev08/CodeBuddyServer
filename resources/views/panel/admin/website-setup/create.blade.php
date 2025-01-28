@extends('layouts.main')
@section('title', @$label . ' Add')
@section('content')
    @push('head')
        <link rel="stylesheet" href="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">

        <style>
            .bootstrap-tagsinput {
                width: 100%;
            }
        </style>
    @endpush
    @php
        $breadcrumb_arr = [
            ['name' => @$label, 'url' => route('panel.admin.website-pages.index'), 'class' => ''],
            ['name' => 'Add' . ' ' . @$label, 'url' => route('panel.admin.website-pages.index'), 'class' => 'active'],
        ];
    @endphp
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-edit bg-blue"></i>
                        <div class="d-inline">
                            <h5> @lang('admin/ui.create') {{ @$label ?? '--' }}</h5>
                            <span> @lang('admin/ui.website_page_heading') </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb')
                </div>
            </div>
        </div>
        <form action="{{ route('panel.admin.website-pages.store') }}" method="POST" enctype="multipart/form-data"
            class="ajaxForm">
            @csrf

            <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip="" regex=""
                validation="" value="create" />
            <div class="row">
                <div class="col-md-7">
                    <div class="">
                        <div class="card mb-bottom">
                            <div class="col-md-12 mt-4 border-bottom">
                                <div class="d-flex justify-content-between" style="margin-top: -10px">
                                    <div class="form-group">
                                        <h6 class="fw-600 mb-0"> @lang('admin/ui.page_content') </h6>
                                    </div>
                                    @if (env('IS_DEV') == 1)
                                        <div>
                                            <button id="legal"
                                                class="p-0 btn btn-link btn-sm text-primary float-end fw-800"><i
                                                    class="fa-solid fa-print"></i> @lang('admin/ui.generator')
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <x-label name="title" validation="common_title"
                                                tooltip="add_website_page_title" />
                                            <x-input name="title" placeholder="Enter Title" type="text"
                                                tooltip="add_website_page_title" regex="title" validation="common_title"
                                                value="{{ old('title') }}" />
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group {{ @$errors->has('slug') ? 'has-error' : '' }}">
                                            <x-label name="slug" validation="website_page_slug"
                                                tooltip="add_website_page_slug" />

                                            <div class="input-group d-block d-md-flex">
                                                {{-- <input type="hidden" class="form-control w-100 w-md-auto" id="slugInput"
                                                    oninput="slugFunction()" placeholder="{{ 'Slug' }}"
                                                    name="slug"> --}}
                                                <x-input name="slug" id="slugInput" oninput="slugFunction()"
                                                    placeholder="{{ 'Slug' }}" type="hidden"
                                                    tooltip="add_website_page_title" regex="title"
                                                    validation="common_title" value="{{ old('title') }}" />

                                                <div class="input-group-prepend"><span class="input-group-text flex-grow-1"
                                                        style="overflow: auto" id="slugOutput">{{ url('page/') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label" for="name"> @lang('admin/ui.page_content')
                                                @if (@validation('website_page_content')['pattern']['mandatory'])
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </label><a data-toggle="tooltip" href="javascript:void(0);"
                                                title="  @lang('admin/tooltip.add_website_page_content')"><i
                                                    class="ik ik-help-circle text-muted ml-1"></i></a>
                                                    <div id="content-holder">
                                                        <div id="toolbar-container"></div>
                                                        <div id="txt_area">
                                                        </div>
                                                    </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-bottom">
                            <div class="card-header">
                                <h6 class="fw-600 mb-0"> @lang('admin/ui.seo_field') </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12" style="margin-bottom: -15px">
                                        <div class="d-flex justify-content-between">
                                            <div class="form-group">
                                                <x-label name="meta_title" for="page_meta_title" validation="common_meta_title"
                                                    tooltip="add_seo_tags_title" />

                                                {{-- <label class="control-label" for="page_meta_title">  @lang('admin/ui.meta') }}
                                                    @lang('admin/ui.title') }}</label><a data-toggle="tooltip"
                                                href="javascript:void(0);" title="  @lang('admin/tooltip.add_website_page_meta_title') }} "><i
                                                    class="ik ik-help-circle text-muted ml-1"></i></a> --}}
                                            </div>
                                            <div>
                                                <button id="auto_fill_title"
                                                    class="p-0 btn btn-link btn-sm text-primary float-end fw-800"><i
                                                        class="ik ik-corner-left-down"></i> @lang('admin/ui.auto_fill')
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <x-input name="page_meta_title" id="meta_titile" placeholder="Enter Title"
                                            type="text" tooltip="add_website_page_meta_title" regex="meta"
                                            validation="common_meta_title" value="{{ old('page_meta_title') }}" />

                                    </div>

                                    <div class="col-md-12 mt-2">
                                        <div class="form-group">
                                            <x-label name="meta_keywords" validation="common_meta_keywords"
                                                tooltip="add_website_page_keywords" />
                                            <x-input name="page_keywords" id="tags" placeholder="Enter Keyword"
                                                type="text" tooltip="add_website_page_keywords" regex="meta"
                                                validation="common_meta_keywords" value="{{ old('page_keywords') }}" />


                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <x-label name="meta_description" validation="common_meta_description"
                                                tooltip="add_website_page_meta_description" />
                                            <x-textarea regex="short_description" validation="common_meta_description"
                                                value="{{ old('page_meta_description') }}" name="page_meta_description"
                                                id="page_meta_description" placeholder="Enter Description " />


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">

                    <div class="card mb-bottom">
                        <div class="card-header d-flex justify-content-between">
                            <h6 class="fw-600 mb-0"> @lang('admin/ui.visibility') </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="form-group">
                                        @php
                                            $checkbox_arr = ['is_published'];
                                        @endphp
                                        <x-checkbox name="status" class="js-switch switch-input" value="1"
                                            type="checkbox" tooltip="" :arr="@$checkbox_arr" />
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{-- <label for="website_page_image" class="control-label"> @lang('admin/ui.banner')
                                            @lang('admin/ui.image')</label> --}}
                                        <x-label name="banner" validation="" tooltip="" />
                                        <a data-toggle="tooltip" href="javascript:void(0);" title="  @lang('admin/tooltip.add_website_page_banner') "><i
                                                class="ik ik-help-circle text-muted ml-1"></i></a>
                                        <div class="input-images" data-input-name="page_meta_image"
                                            id="website_page_image" data-input-accept="{{ validation('blog_image')['dimension'] ?? '' }}"
                                            data-label="Drag & Drop product images here or click to browse"></div>
                                            <small class="text-danger fw-700">
                                                @lang('admin/ui.file_allowed') {{ str_replace('image/','',validation('blog_image')['pattern']['allowed_extensions']) }}

                                                @lang('admin/ui.file_dimensions') {{ validation('blog_image')['dimension'] ?? 'Any Filetypes' }}


                                            </small>
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
            <button type="submit" class="btn btn-primary floating-btn ajax-btn"> @lang('admin/ui.create') </button>
        </form>
    </div>
@endsection

{{-- @include('panel.admin.website-setup.include.legal_modal') --}}
@push('script')
    <script src="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>

    {{-- START TAGINPUT INIT --}}
    <script src="{{ asset('panel/admin/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>

    {{-- START AUTO FILL LEGAL DATA WITH BTN --}}
    <script>
        $('#auto_fill_title').on('click', function(e) {
            e.preventDefault();
            var title_name = $('#title').val();
            $('#meta_titile').val(title_name);
        })

        $('#legal').on('click', function(e) {
            e.preventDefault();
            $('#legalModal').modal('show');
        });
    </script>
    {{-- END AUTO FILL LEGAL DATA WITH BTN --}}

    {{-- START TAGINPUT INIT --}}
    <script>
        $('#tags').tagsinput('items');
    </script>
    {{-- END TAGINPUT INIT --}}

    {{-- START DECOUPLEDEDITOR INIT --}}
    <script src="{{ asset('panel/admin/plugins/ckeditor5/ckeditor.js') }}"></script>
    {{-- START DECOUPLEDEDITOR INIT --}}
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
    <script>
        $('.documentGenerateForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            var response = postData(method, route, 'json', data, null, null, 1, null, 'not-reload');
            console.log(response);
            if (response.status == 'success') {
                var replacedContent = response.content;
                editor.setData(replacedContent);
                $('.close').click();
            }
        });
    </script>
    {{-- END DECOUPLEDEDITOR INIT --}}

    {{-- START AJAX FORM INIT --}}
    <script src="{{ asset('admin/js/ajaxForm.js') }}"></script>
    <script>
        // $('.ajaxForm').on('submit', function(e) {
        //     e.preventDefault();
        //     var route = $(this).attr('action');
        //     var method = $(this).attr('method');
        //     var data = new FormData(this);

        //     ckEditors.forEach((editor, index) => {
        //         if (index == 0) {
        //             data.append('content', editor.getData());
        //         } else {
        //             data.append(`editor_content_${index}`, editor.getData());
        //         }
        //     });
        //     var redirectUrl = "{{ url('/admin/website-pages/') }}";
        //     var response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);

        // });
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();

            // Ensure that the CKEditor content is captured correctly
            let descriptionContent = editor.getData(); // Assuming 'editor' is your CKEditor instance
            if (!descriptionContent.trim()) {
                alert("Content is required.");
                return; // Stop form submission if description is empty
            }

            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            data.append('content', descriptionContent); // Append description content

            var redirectUrl = "{{ url('/admin/website-pages/') }}";
            postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
        });
    </script>
    </script>
    {{-- END AJAX FORM INIT --}}

    {{-- START JS HELPERS INIT --}}
    <script>
        document.getElementById('website_page_image').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            $('#show-image').removeClass('d-none');
            document.getElementById('show-image').src = src
        }


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
    </script>
    {{-- END JS HELPERS INIT --}}
@endpush
