 @extends('layouts.main')
 @section('title', $faq->getPrefix().' Faqs Edit')
 @section('content')
    
@php
    $breadcrumb_arr = [['name' => $label, 'url' => route('panel.admin.faqs.index'), 'class' => ''], ['name' => $faq->getPrefix(), 'url' => route('panel.admin.faqs.index'), 'class' => ''], ['name' => 'Edit', 'url' => route('panel.admin.faqs.index'), 'class' => 'active']];
@endphp

@push('head')
    <link rel="stylesheet" href="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.css') }}">
@endpush

<div class="container-fluid container-fluid-height">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-grid bg-blue"></i>
                    <div class="d-inline">
                        <h5> @lang('admin/ui.edit')  {{ @$label ?? '--' }}</h5>
                        <span> @lang('admin/ui.update_record')  {{ @$label ?? '--' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @include('panel.admin.include.breadcrumb')
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 mx-auto">
            <!-- start message area-->
            @include('panel.admin.include.message')
            <!-- end message area-->
            <div class="card ">
                <div class="card-header">
                    <h3> @lang('admin/ui.update')  {{ @$label ?? '--' }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('panel.admin.faqs.update', $faq->id) }}" method="post"
                        enctype="multipart/form-data"class="ajaxForm">
                        @csrf
                        <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip=""
                        regex="" validation="" value="update" />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                <x-label name="question" validation="faq_question" tooltip="edit_faq_title" />
                                <x-input name="title" placeholder="Enter Question" type="text"
                                    tooltip="edit_faq_title" regex="faq_question" validation="faq_question"
                                    value="{{ @$faq->title }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ @$errors->has('category_id') ? 'has-error' : '' }}">
                                <x-label name="category" validation="faq_category" tooltip="edit_faq_category" />
                            <x-select name="category_id" value="{{@$faq->category_id }}" label="Category"
                                optionName="name" valueName="id" class="select2" :arr="@$categories" validation="faq_category"
                                id="category_id" />
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group ">

                                    <x-label name="description" validation="faq_solution" tooltip="edit_faq_solution" />
                                    <div id="content-holder">

                                        <div id="toolbar-container"></div>
                                        <div id="txt_area">
                                            {!! @$faq->description !!}
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group d-flex {{ @$errors->has('is_published') ? 'has-error' : '' }}">
                                @php
                                $checkbox_arr = ["is_published"];
                            @endphp
                            <div class="mr-2">
                            <x-checkbox name="is_published "  class="js-switch switch-input"  value="1" type="checkbox" tooltip=""  :arr="@$checkbox_arr"/>
                                </div>
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
    <script src="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>
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

    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            const description = editor.getData();
            data.append('description', description);
            var redirectUrl = "{{ url('admin/faqs') }}";
            var response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
        });
    </script>
    {{-- END AJAX FORM INIT --}}
@endpush
