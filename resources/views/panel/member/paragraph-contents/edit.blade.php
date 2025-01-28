@extends('layouts.main')
@section('title', @$paragraphContent->getPrefix() . ' Paragraph Content Edit')
@section('content')
    @php
        $breadcrumb_arr = [
            ['name' => @$label, 'url' => 'javascript:void(0);', 'class' => ''],
            ['name' => @$paragraphContent->getPrefix(), 'url' => 'javascript:void(0);', 'class' => ''],
            ['name' => 'Edit', 'url' => 'javascript:void(0);', 'class' => 'active'],
        ];
    @endphp

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>Edit {{ @$label ?? '--' }} </h5>
                            <span> @lang('admin/ui.update_record') {{ @$label ?? '--' }} </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb')
                </div>
            </div>
        </div>
        <form action="{{ route('panel.admin.paragraph-contents.update', $paragraphContent->id) }}" method="post"
            enctype="multipart/form-data" class="ajaxForm">
            @csrf

            <x-input name="type" placeholder="Enter Name" type="hidden" tooltip="" regex="" validation=""
                value="{{ @$paragraphContent->type }}" id="type" />
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <!-- start message area-->
                    @include('panel.admin.include.message')
                    <!-- end message area-->
                    <div class="card ">
                        <div class="card-header d-flex justify-content-between">
                            <h3> @lang('admin/ui.update') {{ @$label }}</h3>
                        </div>
                        <div class="card-body">
                            <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip=""
                                regex="" validation="" value="update" />
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <x-label name="code" validation="paragraph_code"
                                            tooltip="edit_site_content_managements_code" />
                                        <x-input readonly name="code" placeholder="Enter Title" type="text"
                                            tooltip="edit_site_content_managements_code" regex="slider_code"
                                            validation="paragraph_code" value="{{ @$paragraphContent->code }}" />

                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="code" class="control-label"> @lang('admin/ui.value') @if (@validation('paragraph_value')['pattern']['mandatory'])
                                                <span class="text-danger">*</span>
                                            @endif
                                        </label><a data-toggle="tooltip" href="javascript:void(0);"
                                            title=" @lang('admin/tooltip.add_site_content_managements_value') }} "><i
                                                class="ik ik-help-circle text-muted ml-1"></i></a>
                                        @if (@$paragraphContent->type == 2)
                                            <div id="toolbar-container"></div>
                                            <div id="txt_area">
                                                {!! @$paragraphContent->value !!}
                                            </div>
                                        @else
                                            <div id="content">
                                                <textarea class="form-control ck-editor description"
                                                    minlength="{{ @validation('paragraph_value')['pattern']['minlength'] }}"
                                                    maxlength="{{ @validation('paragraph_value')['pattern']['maxlength'] }}"
                                                    title="{{ @validation('paragraph_value')['pattern']['message'] }}"
                                                    {{ @validation('paragraph_value')['pattern']['mandatory'] }} name="value" placeholder="Enter Value"
                                                    rows="5">{{ @$paragraphContent->value }}</textarea>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <x-label name="group" validation=""
                                            tooltip="edit_site_content_managements_group" />
                                        <x-select name="group" validation="" id="group" class="course-filter"
                                            value="{{ $paragraphContent->group }}" label="Group" optionName="label"
                                            valueName="label" :arr="\App\Models\ParagraphContent::GROUPS" />
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary floating-btn ajax-btn"> @lang('admin/ui.save_update') </button>
        </form>
    </div>
@endsection

@push('script')
    <script src="{{ asset('panel/admin/plugins/ckeditor5/ckeditor.js') }}"></script>
    {{-- START CKEDITOR INIT --}}
    <script>
        let editor;
        $(window).on('load', function() {
            var type = '{{ $paragraphContent->type }}';
            if (type == 2) {
                $('#txt_area').addClass('ck-editor');
                setTimeout(function() {
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
                }, 1000)
            } else {
                var content = $('#description').val();
                $('#content').html(
                    '<textarea required name="value" placeholder="Enter Value" class="form-control ck-editor description" rows="5">{{ $paragraphContent->value }}</textarea>'
                );
            }
        });
    </script>
    {{-- END CKEDITOR INIT --}}

    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            if (editor != undefined) {
                const value = editor.getData();
                data.append('value', value);
            }
            var redirectUrl = "{{ url('admin/paragraph-contents') }}";
            var response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
        });
    </script>
    {{-- END AJAX FORM INIT --}}
@endpush
