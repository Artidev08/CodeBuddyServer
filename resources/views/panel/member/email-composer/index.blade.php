@extends('layouts.main')
@section('title', 'Compose a new mail')
@push('head')
<link rel="stylesheet" href="{{ asset('plugins/select2/dist/css/select2.min.css') }}">
@endpush
@section('content')

@php
    $breadcrumb_arr = [
    ['name' => 'Email Compose', 'url' => 'javascript:void(0);', 'class' => 'active'],
    ];
@endphp

<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-mail bg-blue"></i>
                    <div class="d-inline">
                        <h5> @lang('admin/ui.compose_a_new_mail') </h5>
                        <span> @lang('admin/ui.compose_a_new_mail_and_send_to_any_user') </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @include('panel.admin.include.breadcrumb')
                @include('panel.admin.include.message')
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3> @lang('admin/ui.compose_a_new_mail') </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('panel.admin.compose-emails.send') }}" id="emailComposer" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex">
                                    <label for="">Want to select data via :</label>
                                    <label for="manual_input" class="mx-3"> <input type="radio" name="input_type"
                                            value="1" id="manual_input"> Manual Input</label>
                                    <label for="group_input" class="mx-3"> <input type="radio" name="input_type"
                                            value="2" id="group_input"> Group Input</label>
                                </div>
                            </div>
                            <div class="col-lg-6 group_input d-none">
                                <div class="form-group">
                                    <label for="role_selection">Send To</label>
                                    <select name="role_selection" id="role_selection" class="form-control">
                                        <option value="">--Select Role--</option>
                                        @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 manual_input d-none">
                                <div class="form-group">
                                    <label for="user_selection">Send To</label>
                                    <select name="user_selection" id="user_selection" class="form-control">
                                        <option value="">--Select User--</option>
                                        <option value="new">New Email ID</option>
                                        @foreach ($users as $user)
                                        <option value="{{ $user->email }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6" id="email" pattern="[a-zA-Z]+.*"
                                title="Please enter first letter alphabet and at least one alphabet character is required.">
                                <div class="form-group">
                                    <label for="email">To</label>
                                    <textarea type="email" pattern="[a-zA-Z]+.*"
                                        title="Please enter first letter alphabet and at least one alphabet character is required."
                                        class="form-control" name="email" id="email" placeholder="Email"></textarea>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mt-1">
                                <label for="cc">CC</label>
                                <input type="email" pattern="[a-zA-Z]+.*"
                                    title="Please enter first letter alphabet and at least one alphabet character is required."
                                    class="form-con pattern=" [a-zA-Z]+.*
                                    title="Please enter first letter alphabet and at least one alphabet character is required."
                                    placeholder="CC Email">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="bcc">BCC</label>
                                <input type="email pattern=" [a-zA-Z]+.*
                                    title="Please enter first letter alphabet and at least one alphabet character is required."
                                    class="form-control" name="bcc" id="bcc" placeholder="BCC Email">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input type="text" pattern="[a-zA-Z]+.*"
                                    title="Please enter first letter alphabet and at least one alphabet character is required."
                                    class="form-control" name="subject" id="subject" placeholder="Subject">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="attach">Template</label>
                                <select name="template_id" id="template_id" class="form-control">
                                    <option value="" readonly>Select Template</option>
                                    @foreach (App\Models\MailSmsTemplate::whereType(1)->get() as $template)
                                    <option value="{{ $template->id }}">
                                        {{ ucwords(str_replace('-', ' ', $template->code)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-10 col-lg-9 col-sm-9">
                            <div class="form-group">
                                <label for="attach">Attachments</label>
                                <input type="file" name="attachments[]" multiple id="">
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-sm-3">
                            <div class="form-group mt-4">
                                <button type="button" class="btn btn-primary" id="prepareMessage">Prepare
                                    Message</button>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <input type="hidden" class="messageText" name="message">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="message">Message</label>
                                    <div id="content-holder">
                                        <div id="toolbar-container"></div>
                                        <div id="txt_area">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <input type="hidden" class="bodyText" name="body">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="body">Footer</label>
                                    <div id="content-holder">
                                        <div id="toolbar-container-footer"></div>
                                        <div id="txt_area_footer">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit"> <i class="ik ik-send"></i>
                                    Send</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script src="{{ asset('plugins/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('plugins/js/datatables.js') }}"></script>
    <script src="{{ asset('panel/admin/plugins/ckeditor5/ckeditor.js') }}"></script>

    {{-- START CKEDITOR INIT --}}
    <script>
        let editor1, editor2;

            $(window).on('load', function() {
                $('#txt_area').addClass('ck-editor');
                DecoupledEditor
                    .create(document.querySelector('#txt_area'), {
                        ckfinder: {
                            uploadUrl: "{{ route('panel.admin.media.ckeditor.upload') . '?_token=' . csrf_token() }}",
                        }
                    })
                    .then(newEditor => {
                        editor1 = newEditor;
                        const toolbarContainer = document.querySelector('#toolbar-container');

                        toolbarContainer.appendChild(editor1.ui.view.toolbar.element);
                    })
                    .catch(error => {
                        console.error(error);
                    });

                $('#txt_area_footer').addClass('ck-editor');
                DecoupledEditor
                    .create(document.querySelector('#txt_area_footer'), {
                        ckfinder: {
                            uploadUrl: "{{ route('panel.admin.media.ckeditor.upload') . '?_token=' . csrf_token() }}",
                        }
                    })
                    .then(newEditor => {
                        editor2 = newEditor;
                        const toolbarContainer1 = document.querySelector('#toolbar-container-footer');

                        toolbarContainer1.appendChild(editor2.ui.view.toolbar.element);
                    })
                    .catch(error => {
                        console.error(error);
                    })
            });
            const editorData = document.querySelector('#txt_area').innerHTML;
            document.querySelector('.messageText').value = editorData;
    </script>
    {{-- END CKEDITOR INIT --}}

    {{--START CUSTOM JS INIT --}}
    <script>
        $('#emailComposer').on('submit', function() {
                const editorData = document.querySelector('#txt_area').innerHTML;
                document.querySelector('.messageText').value = editorData;
                const bodyData = document.querySelector('#txt_area_footer').innerHTML;
                document.querySelector('.bodyText').value = bodyData;
            });

            $('input[name="input_type"]').change(function() {
                $('#email').val('');
                if ($(this).val() == 1) {
                    $('.manual_input').removeClass('d-none');
                    $('.group_input').addClass('d-none');
                } else if ($(this).val() == 2) {
                    $('.group_input').removeClass('d-none');
                    $('.manual_input').addClass('d-none');
                }
            });

            $(document).ready(function() {
                $('#prepareMessage').on('click', function() {
                    var user_emails = $('#attach').val();
                    var template_id = $('#template_id').val();
                    var url = "";
                    if (user_emails) {
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {
                                user_emails: user_emails
                            },
                            dataType: "html",
                            success: function(resultData) {
                                console.log(resultData);
                                editor1.setData(resultData.message);
                            }
                        });
                    }
                    if (template_id) {
                        url = "{{ route('panel.admin.compose-emails.get-template') }}";
                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: {
                                template_id: template_id
                            },
                            dataType: "json",
                            success: function(resultData) {
                                console.log(resultData);
                                $('#subject').val(resultData.template.subject);
                                editor2.setData(resultData.body);
                            },
                            error: function(error) {
                                $.toast({
                                    heading: 'ERROR',
                                    text: error.error,
                                    showHideTransition: 'slide',
                                    icon: 'error',
                                    loaderBg: '#f2a654',
                                    position: 'top-right'
                                });
                            }
                        });
                    }

                });


                $('#email-container').hide();
                $(document).on('change', '#role_selection', function(e) {
                    var role = $(this).val();
                    $.ajax({
                        type: "get",
                        url: "",
                        data: {
                            role: role
                        },
                        success: function(data) {
                            $('#email').val('');
                            $('#email').val($.trim(data));
                            $('#email-container').fadeIn(250);
                        }
                    });

                });

                $(document).on('change', '#user_selection', function(e) {
                    var old_value = $('#email').val();
                    var email = e.target.value;
                    var emails;
                    if (old_value != '') {
                        emails = old_value + ',' + email;
                    } else {
                        emails = email;
                    }
                    if (email !== 'new') {
                        $('#email').val(emails);
                    } else {
                        $('#email').val('');
                    }

                    $('#email-container').fadeIn(250);
                    if (email == '') {
                        $('#email-container').fadeOut(250);
                    }
                });
            });
    </script>
    {{--END CUSTOM JS INIT --}}
@endpush
@endsection
