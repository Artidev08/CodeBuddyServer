@extends('layouts.main')
@section('title', @$label)
@section('content')
@php
    $breadcrumb_arr = [['name' => $label, 'url' => 'javascript:void(0);', 'class' => 'active']];
@endphp

@push('head')
    <link rel="stylesheet" href="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.css') }}">
     {{-- INITIALIZE SHIMMER & INIT LOAD --}}
     <script>
        window.onload = function() {
            $('#ajax-container').show();
            document.getElementById('reset').click();
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
                        <h5>{{ @$label }}</h5>
                        <span> @lang('admin/ui.list_of')  {{ @$label ?? '--' }} </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @include('panel.admin.include.breadcrumb')
            </div>
        </div>
    </div>
    <div class="row">
        @if ($permissions->contains('add_faq'))
            <div class="col-md-12 add-faqs-form d-none">
                <form action="{{ route('panel.admin.faqs.store') }}" method="post" class="ajaxForm"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h5> {{ @$label ?? '--' }}</h5>
                            <div>
                                <a href="javascript:void(0);" id="showFaqs" class="btn btn-light ml-2"> <i class="ik ik-grid"></i> Show List</a>
                            </div>
                        </div>

                        <div class="card-body">
                            <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip=""
                                regex="" validation="" value="create" />
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <x-label name="question" validation="faq_question" tooltip="add_faq_question" />
                                        <x-input name="title" placeholder="Enter Question" type="text"
                                            tooltip="add_category_types_code" regex="faq_question"
                                            validation="faq_question" value="{{ old('title') }}" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <div class="form-group {{ @$errors->has('category_id') ? 'has-error' : '' }}">
                                            <x-label name="category" validation="category_select" tooltip="edit_faq_category" />
                                            <x-select name="category_id" value="{{ old('category_id') }}"
                                                label="Category" tooltip="edit_faq_category" optionName="name" valueName="id" class="select2"
                                                :arr="@$categories" validation="category_select" id="category_id" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <label for="description"
                                            class="control-label"> @lang('admin/ui.description') <span
                                                class="text-danger">*</span></label>
                                        <a data-toggle="tooltip" href="javascript:void(0);"
                                            title=" @lang('admin/tooltip.add_faq_solution') "><i
                                                class="ik ik-help-circle text-muted ml-1"></i></a>
                                        <div id="content-holder">

                                            <div id="toolbar-container"></div>
                                            <div id="txt_area">

                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-6 text-left mb-2">
                                    <div
                                        class="form-group d-flex {{ @$errors->has('is_published') ? 'has-error' : '' }}">
                                        @php
                                            $checkbox_arr = ['is_published'];
                                        @endphp
                                         <div class="mr-2">
                                        <x-checkbox name="is_published" class="js-switch switch-input" value="1"
                                            type="checkbox" tooltip="" :arr="@$checkbox_arr" />
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit"
                        class="btn btn-primary floating-btn ajax-btn"> @lang('admin/ui.create') </button>
                </form>
            </div>
        @endif
        <div class="col-md-12 show-faqs-form">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3>{{ @$label ?? '--' }}</h3>
                    <div class="d-flex">
                        <a data-toggle="tooltip" href="javascript:void(0);" id="addFaqs"
                            class="btn btn-sm btn-outline-primary mr-2" title="Add New Faq"><i class="fa fa-plus"
                                aria-hidden="true"></i>
                            Add</a>
                        @if (getSetting('faq_activation_bulk_status_update', @$setting) ||
                                getSetting('faq_activation_bulk_delete', @$setting) ||
                                getSetting('faq_activation_bulk_upload', @$setting))
                            <form action="{{ route('panel.admin.faqs.bulk-action') }}" method="POST" id="bulkAction">
                                @csrf
                                <input type="hidden" name="ids" id="bulk_ids">
                                <button class="dropdown-toggle p-0 custom-dopdown bulk-btn btn btn-light" type="button"
                                    id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false"><i class="ik ik-more-vertical fa-lg pl-1"></i></button>
                                <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                    @if (getSetting('faq_activation_bulk_upload', @$setting))
                                        <a href="javascript:void(0);" class="dropdown-item text-primary fw-700"
                                            data-toggle="modal" data-target="#BulkStoreAgentModal"><i
                                                class="ik ik-upload"></i> Bulk Upload</a>
                                    @endif
                                    @if (getSetting('faq_activation_bulk_status_update', @$setting))
                                        <hr class="m-1">
                                        <a href="javascript:void(0)" class="dropdown-item bulk-action" data-value="0"
                                            data-status="Unpublish" data-column="is_published"
                                            data-message="You want to mark these Faqs as Unpublish?"
                                            data-action="columnUpdate" data-callback="bulkColumnUpdateCallback">Mark
                                            as Unpublish
                                        </a>

                                        <a href="javascript:void(0)" class="dropdown-item bulk-action" data-value="1"
                                            data-status="Publish" data-column="is_published"
                                            data-message="You want to mark these Faqs as Publish?"
                                            data-action="columnUpdate" data-callback="bulkColumnUpdateCallback">Mark
                                            as Publish
                                        </a>
                                    @endif
                                    @if (getSetting('faq_activation_bulk_delete', @$setting))
                                        <hr class="m-1">
                                        <button type="submit" class="dropdown-item bulk-action text-danger fw-700"
                                            data-value="" data-message="You want to delete these Faqs?"
                                            data-action="delete" data-callback="bulkDeleteCallback"><i
                                                class="ik ik-trash"> </i> Bulk
                                            Delete
                                        </button>
                                    @endif
                                </ul>
                            </form>
                        @endif
                    </div>
                </div>
                <form action="{{ route('panel.admin.faqs.index') }}" method="GET" id="TableForm">
                    <div id="ajax-container" style="display: none;">
                        @include('panel.admin.faqs.load')
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@if (getSetting('faq_activation_table_filter', @$setting))
@include('panel.admin.faqs.include.filter')
@endif
@if (getSetting('faq_activation_bulk_delete', @$setting))
@include('panel.admin.faqs.include.bulk-upload')
@endif
@endsection

@push('script')
    <script src="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>
    <script src="{{ asset('panel/admin/plugins/ckeditor5/ckeditor.js') }}"></script>
    @include('panel.admin.include.bulk-script')

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

    {{-- START HTML TO EXCEL INIT --}}
    <script>
        function html_table_to_excel(type) {
            var table_core = $("#faqTable").clone();
            var clonedTable = $("#faqTable").clone();
            clonedTable.find('[class*="no-export"]').remove();
            clonedTable.find('[class*="d-none"]').remove();
            $("#faqTable").html(clonedTable.html());
            var data = document.getElementById('faqTable');

            var file = XLSX.utils.table_to_book(data, {
                sheet: "sheet1"
            });
            XLSX.write(file, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            });
            XLSX.writeFile(file, 'leadFile.' + type);
            $("#faqTable").html(table_core.html());
        }

        $(document).on('click', '#export_button', function() {
            html_table_to_excel('xlsx');
        });
    </script>
    {{-- END HTML TO EXCEL INIT --}}

    {{-- //START STORE DATA USING AJAX --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            const description = editor.getData();
            if (description == '') {
                $.toast({
                    heading: 'ERROR',
                    text: "description is required",
                    showHideTransition: 'slide',
                    icon: 'error',
                    loaderBg: '#f2a654',
                    position: 'top-right'
                });
                return false;
            }
            data.append('description', description);
            var response = postData(method, route, 'json', data, "handleFaqCallback", null);
        });
        function handleFaqCallback(response) {
            var redirectUrl = "{{ url('admin/faqs') }}";
            if (typeof(response) != "undefined" && response !== null && response.status == "success") {
                window.location.href = redirectUrl;
            }

        }
    </script>
    {{-- //END STORE DATA USING AJAX --}}

    {{-- // START RESET BUTTON JS --}}
    <script>
        $('#reset').click(function() {
            fetchData("{{ route('panel.admin.faqs.index') }}");
            window.history.pushState("", "", "{{ route('panel.admin.faqs.index') }}");
            $('#TableForm').trigger("reset");
            $(document).find('.close.off-canvas').trigger('click');
           
        });
    </script>
    {{-- // END RESET BUTTON JS  --}}

    {{-- START CUSTOM JS INIT --}}
    <script>
        $('#addFaqs').click(function() {
            $('.add-faqs-form').removeClass('d-none');
            $('.show-faqs-form').addClass('d-none');
        });

        $('#showFaqs').click(function() {
            $('.add-faqs-form').addClass('d-none');
            $('.show-faqs-form').removeClass('d-none');
        });

        $(document).ready(function() {
            $('.select2').select2();
            getCategories();

            $('#category_id').on('change', function() {
                let userId = $(this).val();
                $.ajax({
                    url: "{{ route('panel.admin.faqs.getCategories') }}",
                    type: 'POST',
                    data: {
                        userId: userId
                    },
                    success: function(response) {
                        console.log(response);
                    }
                });
            });
        });
    </script>
    {{-- END CUSTOM JS INIT --}}

@endpush
