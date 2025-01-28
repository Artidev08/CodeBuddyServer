@extends('layouts.main')
@section('title', __('admin/ui.left_sidebar_mail_sms_templates') . ' ' . __('admin/ui.edit'))
@section('content')
    @php
        $breadcrumb_arr = [
            [
                'name' => __('admin/ui.left_sidebar_mail_sms_templates'),
                'url' => route('panel.admin.templates.index'),
                'class' => '',
            ],
            ['name' => $mailSmsTemplate->getPrefix(), 'url' => route('panel.admin.templates.index'), 'class' => ''],
            ['name' => __('admin/ui.edit'), 'url' => route('panel.admin.templates.index'), 'class' => 'active'],
        ];
    @endphp
    <!-- push external head elements to head -->
    @push('head')
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5> @lang('admin/ui.edit') {{ __('admin/ui.left_sidebar_mail_sms_templates') }}</h5>
                            <span> @lang('admin/ui.update_record') {{ __('admin/ui.left_sidebar_mail_sms_templates') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb')
                </div>
            </div>
        </div>
        <form action="{{ route('panel.admin.templates.update', $mailSmsTemplate->id) }}" method="post" class="ajaxForm">
            @csrf
            <div class="row">
                <!-- start message area-->
                @include('panel.admin.include.message')
                <!-- end message area-->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3> @lang('admin/ui.update') {{ __('admin/ui.left_sidebar_mail_sms_templates') }}</h3>
                        </div>
                        <div class="card-body">
                            {{-- <input type="hidden" name="request_with" value="update"> --}}
                            <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip=""
                                regex="" validation="" value="update" />
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group {{ @$errors->has('code') ? 'has-error' : '' }}">
                                        <x-label name="code" validation="template_code"
                                            tooltip="edit_mail_sms_template_code" />
                                        <x-input name="code"
                                            placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.code') }}"
                                            type="text" tooltip="edit_mail_sms_template_code" regex="template_code"
                                            validation="template_code"
                                            value="{{ isset($mailSmsTemplate->code) ? @$mailSmsTemplate->code : ' ' }}" />

                                    </div>
                                </div>
                              
                                <div class="col-md-12">
                                    <div class="form-group {{ @$errors->has('subject') ? 'has-error' : '' }}">
                                        <x-label name="subject" validation="template_subject"
                                            tooltip="edit_mail_sms_template_subject" />
                                        <x-input name="subject"
                                            placeholder="{{ __('admin/ui.enter') . ' ' .     __('admin/ui.subject') }}"
                                            type="text" tooltip="edit_mail_sms_template_subject" regex="template_subject"
                                            validation="template_subject"
                                            value="{{ isset($mailSmsTemplate->subject) ? @$mailSmsTemplate->subject : '' }}" />

                                    </div>
                                </div>
                           
                                @if (@$mailSmsTemplate->variables != null)
                                    <div class="col-md-12 alert alert-info">
                                        <label for="">You can put these variables under content:</label><br>
                                        @foreach (@$mailSmsTemplate->variables as $item)
                                            {{ @$item }}@if (!@$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                                <div class="col-12 mx-auto">
                                    <div class="form-group {{ @$errors->has('content') ? 'has-error' : '' }}">
                                        <x-label name="content" validation="template_content"
                                            tooltip="edit_mail_sms_template_content" />

                                        <div id="toolbar-container"></div>
                                        @if (@$mailSmsTemplate->type == 1)
                                            <div id="txt_area">
                                                {!! @$mailSmsTemplate->content !!}
                                            </div>
                                        @else
                                            <div id="mail-content">
                                                <x-textarea regex="common_short_description" validation="template_content"
                                                    value="{{ @$mailSmsTemplate->purpose }}" name="content"
                                                    id="toolbar-container"
                                                    placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.purpose') }}" />

                                                <textarea name="content" regex="common_short_description" validation="template_content"
                                                    class="form-control ck-editor description" rows="5">{{ @$mailSmsTemplate->content ?? '' }}</textarea>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3> @lang('admin/ui.subject') </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">   
                        
                        <div class="col-md-12">
                            <div class="form-group {{ @$errors->has('purpose') ? 'has-error' : '' }}">
                                <x-label name="purpose" validation="common_short_description"
                                    tooltip="add_mail_sms_template_purpose" />
                                <x-textarea regex="short_description" validation="common_short_description"
                                    value="{{ @$mailSmsTemplate->purpose }}" name="purpose" id="purpose"
                                    rows="2"
                                    placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.purpose') }}" />
                            </div>
                        </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary floating-btn"> @lang('admin/ui.save_update') </button>
        </form>
    </div>
    <!-- push external js -->
    @push('script')
        {{-- START DECOUPLEDEDITOR INIT --}}
        <script src="{{ asset('panel/admin/plugins/ckeditor5/ckeditor.js') }}"></script>
        <script>
            let editor;
            $(window).on('load', function() {
                var type = '{{ $mailSmsTemplate->type }}';
                if (type == 1) {
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
                } else {
                    var content = $('#description').val();
                    var templateContent = "{{ $mailSmsTemplate->content }}";

                    $('#mail-content').html(
                        '<textarea  class="form-control" name="sms_content" id="description" placeholder="Enter Content">{{ $mailSmsTemplate->content }}</textarea>'
                    );
                    $('#description').val(templateContent);
                }
            });
        </script>

        {{-- END DECOUPLEDEDITOR INIT --}}

        {{-- START AJAX FORM INIT --}}
        <script>
            $('.ajaxForm').on('submit', function(e) {
                e.preventDefault();
                if ("{{ $mailSmsTemplate->type }}" == 1) {
                    var tempDescription = editor.getData();
                } else {
                    var tempDescription = $('#description').val();
                }
                var route = $(this).attr('action');
                var method = $(this).attr('method');
                var data = new FormData(this);
                const description = tempDescription;
                data.append('content', description);
                {{-- var redirectUrl = "{{ url('admin/templates/') }}"; --}}
                var d_type = '{{ $mailSmsTemplate->type }}';
                var redirectUrl = "{{ url('admin/templates/') }}" + '?type' + `=` + d_type;
                var response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
            })
        </script>
        {{-- END AJAX FORM INIT --}}
    @endpush
@endsection
