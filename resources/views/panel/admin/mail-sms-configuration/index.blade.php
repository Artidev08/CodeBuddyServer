@extends('layouts.main')
@section('title', @$label)
@section('content')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5> @lang(@$label ?? '--') </h5>
                            <span style="font-size: 0.75rem;"> @lang('admin/ui.updated_website') </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 d-sm-flex d-lg-block">
                    <div>
                        <nav class="breadcrumb-container" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('panel.admin.dashboard.index') }}"><i class="ik ik-home"></i></a>
                                </li>
                         
                                <li class="breadcrumb-item active">
                                    <a href="" aria-current="page"> @lang(@$label) </a>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
                @include('panel.admin.setting.sitemodal', [
                    'title' => 'How to use',
                    'content' =>
                        'You need to create a unique code and call the unique code with paragraph content helper.',
                ])
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card card-484">
                    <div role="tabpanel">
                        <div class="card-header" style="border:none;">
                            <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                                @if (getSetting('mail_activation', @$setting))
                                    @if ($permissions->contains('access_email_setting'))
                                        <li class="nav-item"><a
                                                href="{{ route('panel.admin.mail-sms-configuration.index', ['name' => 'mail_config']) }}"
                                                class="nav-link @if (!request()->has('name') || (request()->has('name') && request()->get('name') == 'mail_config')) active @endif"
                                                aria-controls="mail" role="tab"> @lang('admin/ui.mail_config') </a>
                                        </li>
                                    @endif
                                @endif
                                @if (getSetting('sms_activation', @$setting))
                                @if ($permissions->contains('access_sms_setting'))
                                    <li class="nav-item"><a
                                            href="{{ route('panel.admin.mail-sms-configuration.index', ['name' => 'sms_config']) }}"
                                            class="nav-link  @if (request()->has('name') && request()->get('name') == 'sms_config') active @endif"
                                            aria-controls="sms" role="tab"> @lang('admin/ui.sms_config') </a>
                                    </li>
                                @endif
                                @endif
                                @if (getSetting('fcm_activation', @$setting))
                                @if ($permissions->contains('access_fcm_setting'))
                                    <li class="nav-item"><a
                                            href="{{ route('panel.admin.mail-sms-configuration.index', ['name' => 'fcm_config']) }}"
                                            class="nav-link @if (request()->has('name') && request()->get('name') == 'fcm_config') active @endif"
                                            aria-controls="notification" role="tab"
                                            aria-labelledby="pills-fcm_config-tab"> @lang('admin/ui.fcm_config') </a>
                                    </li>
                                @endif
                                @endif
                            </ul>
                        </div>
                        <div class="card-body pt-0">
                            <div class="tab-content">
                                @if (getSetting('mail_activation', @$setting))
                                    <div class="tab-pane fade @if (request()->has('name') && request()->get('name') == 'mail_config') show active @endif"
                                        id="current-month" role="tabpanel" aria-labelledby="pills-mail_config-tab">
                                        <div class="card-header p-0 justify-content-between">
                                            <h3> @lang('admin/ui.mail_config') </h3><a data-toggle="tooltip"
                                                href="javascript:void(0);" class="btn btn-outline-danger text-color-white openModal mb-2"
                                                data-type="Mail"><i class="ik ik-mail"></i>
                                                 @lang('admin/ui.test_mail') </a>
                                        </div>
                                        <form class="forms-sample ajaxForm"
                                            action="{{ route('panel.admin.mail-sms-configuration.mail.store') }}"
                                            method="post" enctype="multipart/form-data">
                                            @csrf
                                            {{-- <input type="hidden" name="group_name" value="mail_setting"> --}}

                                            <x-input name="group_name" placeholder="Enter Name" type="hidden"
                                                tooltip="" regex="" validation="" value='mail_setting' />
                                            <div class="card-body">
                                                <div class="form-group row">

                                                    
                                                    <x-label name="admin_email" class="col-sm-2 col-form-label"
                                                        for="exampleInputUsername2" validation="admin_email"
                                                        tooltip="mail_sms_configuration_admin_email" />
                                                    <div class="col-sm-5">
                                                        
                                                        <x-input name="admin_email" placeholder="Admin Email" type="text"
                                                            tooltip="mail_sms_configuration_admin_email" regex="admin_email"
                                                            validation="admin_email"
                                                            value="{{ getSetting('admin_email', @$setting) }}" />
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <span class="text-warning">This email used for sending important
                                                            updates
                                                            to system panel.admin.</span>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <x-label name="mail_from_name" class="col-sm-2 col-form-label"
                                                        for="exampleInputUsername2" validation="admin_email"
                                                        tooltip="mail_sms_configuration_mail_username" />
                                                   
                                                    <div class="col-sm-5">
                                                        <x-input name="mail_from_name" placeholder="Mail From Name"
                                                            type="text" tooltip="mail_sms_configuration_mail_username"
                                                            regex="admin_email" validation="admin_email"
                                                            value="{{ getSetting('mail_from_name', @$setting) }}" />
                                                    
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <span class="text-warning">This will be display name for your sent
                                                            email.</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <x-label name="mail_from_address" class="col-sm-2 col-form-label"
                                                        for="exampleInputEmail2" validation="admin_email"
                                                        tooltip="mail_sms_configuration_admin_email" />
                                                    
                                                    <div class="col-sm-5">
                                                        <x-input name="mail_from_address" placeholder="Mail From Address"
                                                            type="text" tooltip="mail_sms_configuration_admin_email"
                                                            regex="admin_email" validation="admin_email"
                                                            value="{{ getSetting('mail_from_address', @$setting) }}" />
                                                        
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <span class="text-warning">This email will be used for "Contact
                                                            Form"
                                                            correspondence.</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2"
                                                        class="col-sm-2 col-form-label"> @lang('admin/ui.mail_driver') <span
                                                            class="text-red">*</span><a data-toggle="tooltip"
                                                            href="javascript:void(0);" title="@lang('admin/tooltip.mail_sms_configuration_mail_driver')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a>
                                                    </label>

                                                    <div class="col-sm-5">
                                                        {{-- <input type="text" name="mail_mailer" class="form-control" value="{{ getSetting('mail_mailer',@$setting) }}" placeholder="Mail Driver"> --}}
                                                        <select name="mail_mailer" id="" required
                                                            class="form-control select2">
                                                            <option value="" aria-readonly="true">Select mail driver
                                                            </option>
                                                            <option @if (getSetting('mail_mailer', @$setting) == 'smtp') selected @endif
                                                                value="smtp">SMTP
                                                            </option>
                                                            <option @if (getSetting('mail_mailer', @$setting) == 'sendmail') selected @endif
                                                                value="sendmail">Sendmail
                                                            </option>
                                                            <option @if (getSetting('mail_mailer', @$setting) == 'mailgun') selected @endif
                                                                value="mailgun">Mailgun
                                                            </option>
                                                            <option @if (getSetting('mail_mailer', @$setting) == 'sparkpost') selected @endif
                                                                value="sparkpost">SparkPost
                                                            </option>
                                                            <option @if (getSetting('mail_mailer', @$setting) == 'ses') selected @endif
                                                                value="ses">Amazon SES
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <span class="text-warning">You can select any driver you want for
                                                            your
                                                            Mail setup. Ex. SMTP, Mailgun, Mandrill, SparkPost, Amazon SES
                                                            etc.
                                                            Add single driver only.</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2"
                                                        class="col-sm-2 col-form-label"> @lang('admin/ui.mail_host') <span
                                                            class="text-red">*</span><a data-toggle="tooltip"
                                                            href="javascript:void(0);" title="@lang('admin/tooltip.mail_sms_configuration_mail_host')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a>
                                                    </label>
                                                    <div class="col-sm-5">
                                                        <input type="text" pattern="[a-zA-Z]+.*"
                                                            title="Please enter first letter alphabet and at least one alphabet character is required."
                                                            title="Please enter first letter alphabet and at least one alphabet character is required."
                                                            name="mail_host" class="form-control"
                                                            value="{{ getSetting('mail_host', @$setting) }}"
                                                            placeholder="Mail Host" required>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <span class="text-warning">Standard configuration samples: Gmail:
                                                            smtp.gmail.com, </span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2"
                                                        class="col-sm-2 col-form-label"> @lang('admin/ui.mail_port') <span
                                                            class="text-red">*</span><a data-toggle="tooltip"
                                                            href="javascript:void(0);" title="@lang('admin/tooltip.mail_sms_configuration_mail_port')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a>
                                                    </label>
                                                    <div class="col-sm-5">
                                                        {{-- <input type="text" name="mail_port" class="form-control" value="{{ getSetting('mail_port',@$setting) }}" placeholder=" "> --}}
                                                        <select required name="mail_port" id=""
                                                            class="form-control select2">
                                                            <option value="" readonly>Select mail port</option>
                                                            <option @if (getSetting('mail_port', @$setting) == '587') selected @endif
                                                                value="587">587
                                                            </option>
                                                            <option @if (getSetting('mail_port', @$setting) == '465') selected @endif
                                                                value="465">465
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-5">

                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2"
                                                        class="col-sm-2 col-form-label"> @lang('admin/ui.mail_user_name') <span
                                                            class="text-red">*</span><a data-toggle="tooltip"
                                                            href="javascript:void(0);" title="@lang('admin/tooltip.mail_sms_configuration_mail_username')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a>
                                                    </label>
                                                    <div class="col-sm-5">
                                                        <input type="text" pattern="[a-zA-Z]+.*"
                                                            title="Please enter first letter alphabet and at least one alphabet character is required."
                                                            title="Please enter first letter alphabet and at least one alphabet character is required."
                                                            name="mail_username" class="form-control"
                                                            value="{{ getSetting('mail_username', @$setting) }}"
                                                            placeholder="Ex. myemail@email.com" required>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <span class="text-warning">Add your email id you want to configure
                                                            for
                                                            sending emails</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2"
                                                        class="col-sm-2 col-form-label"> @lang('admin/ui.mail_password') <span
                                                            class="text-red">*</span><a data-toggle="tooltip"
                                                            href="javascript:void(0);" title="@lang('admin/tooltip.mail_sms_configuration_mail_password')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a>
                                                    </label>
                                                    <div class="col-sm-5">
                                                        <input type="password" name="mail_password" class="form-control"
                                                            value="{{ getSetting('mail_password', @$setting) }}"
                                                            placeholder="Mail Password" required>
                                                    </div>
                                                    <div class="col-sm-5">
                                                        <span class="text-warning">Add your email password you want to
                                                            configure for sending emails</span>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2"
                                                        class="col-sm-2 col-form-label"> @lang('admin/ui.mail_encryption') <span
                                                            class="text-red">*</span><a data-toggle="tooltip"
                                                            href="javascript:void(0);" title="@lang('admin/tooltip.mail_sms_configuration_mail_encryption')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a>
                                                    </label>
                                                    <div class="col-sm-5">
                                                        {{-- <input type="text" name="mail_encryption" class="form-control" value="{{ getSetting('mail_encryption',@$setting) }}" placeholder="Mail Encryption"> --}}
                                                        <select required name="mail_encryption" id=""
                                                            class="form-control select2">
                                                            <option value="" aria-readonly="true">
                                                                 @lang('admin/ui.mail_encryption') </option>
                                                            <option @if (getSetting('mail_encryption', @$setting) == 'tls') selected @endif
                                                                value="tls">TLS
                                                            </option>
                                                            <option @if (getSetting('mail_encryption', @$setting) == 'ssl') selected @endif
                                                                value="ssl">SSL
                                                            </option>

                                                        </select>

                                                    </div>
                                                    <div class="col-sm-5">
                                                        <span class="text-warning">Use tls if your site uses HTTP protocol
                                                            and
                                                            ssl if you site uses HTTPS protocol</span>
                                                    </div>
                                                </div>
                                                <hr>
                                                <p class="help-text mb-0"><b>Important Note</b> : IF you are using
                                                    <b>GMAIL</b> for Mail configuration, make sure you have completed
                                                    following process before updating:
                                                </p>
                                                <ul class="text-color-white">
                                                    <li>Go to <a target="_blank" class="text-color-white"
                                                            href="https://myaccount.google.com/security">My Account</a>
                                                        from your Google Account you want to configure and Login
                                                    </li>
                                                    <li>Scroll down to <b>Less secure app access</b> and set it <b>ON</b>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="card-footer text-right">
                                                <button type="submit"
                                                    class="btn btn-primary mr-2"> @lang('admin/ui.save_update') </button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                                @if (getSetting('sms_activation', @$setting))
                                <div class="tab-pane fade @if (request()->has('name') && request()->get('name') == 'sms_config') show active @endif"
                                    id="current-month" role="tabpanel" aria-labelledby="pills-sms_config-tab">
                                    <div class="card-header p-0 justify-content-between">
                                        <h3> @lang('admin/ui.sms_configuration') </h3>
                                        <a href="javascript:void(0);" class="btn btn-outline-danger text-color-white openModal mb-2"
                                            data-type="Sms"><i class="ik ik-mail"></i>
                                             @lang('admin/ui.test_sms_config') </a>

                                    </div>
                                    <form class="forms-sample ajaxForm"
                                        action="{{ route('panel.admin.mail-sms-configuration.sms.store') }}"
                                        method="post" enctype="multipart/form-data">
                                        @csrf
                                        {{-- <input type="hidden" name="group_name" value="sms_endpoint_setting"> --}}
                                        <x-input name="group_name" placeholder="Enter Name" type="hidden"
                                            tooltip="" regex="" validation="" value="sms_endpoint_setting" />

                                        <div class="card-body">
                                            <div class="row">
                                                <label for="exampleInputUsername2"
                                                    class="col-sm-2 col-form-label"> @lang('admin/ui.sms_end_point') <span
                                                        class="text-red">*</span>
                                                    <a data-toggle="tooltip" href="javascript:void(0);"
                                                        title="@lang('admin/tooltip.mail_sms_configuration_sms_endpoint')"><i
                                                            class="ik ik-help-circle text-muted ml-1"></i></a>
                                                </label>
                                                <div class="col-sm-5">
                                                    <textarea type="text" name="sms_endpoint" class="form-control" cols="2" placeholder=" Enter SMS Endpoint"
                                                        required>{{ getSetting('sms_endpoint', @$setting) }}</textarea>
                                                </div>
                                                <div class="col-sm-5">
                                                    <span class="text-warning">You can purchase api from any sms provider
                                                        and set endpoint url here.
                                                        <br> Note: Url should contain valid api key, without any parameter.
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="submit"
                                                class="btn btn-primary mr-2"> @lang('admin/ui.save_update') </button>
                                        </div>
                                    </form>

                                    <div class=" pt-4 card-header p-0 justify-content-between">
                                        <h3>Whatsapp Configuration</h3>
                                        <a href="javascript:void(0);" class="btn btn-outline-danger text-color-white openModal mb-2"
                                            data-type="Sms"><i class="ik ik-mail"></i>
                                             @lang('admin/ui.test_sms_config')
                                        </a>

                                    </div>
                                    <form class="forms-sample ajaxForm" action="{{ route('panel.admin.setting.store') }}"
                                        method="post" enctype="multipart/form-data">
                                        @csrf
                                        {{-- <input type="hidden" name="group_name" value="whatsapp_endpoint_setting"> --}}
                                        <x-input name="group_name" placeholder="Enter Name" type="hidden"
                                            tooltip="" regex="" validation=""
                                            value="whatsapp_endpoint_setting" />

                                        <div class="card-body">
                                            <div class="row">
                                                <label for="exampleInputUsername2"
                                                    class="col-sm-2 col-form-label">Whatsapp Api<span
                                                        class="text-red">*</span>
                                                    <a data-toggle="tooltip" href="javascript:void(0);"
                                                        title="@lang('admin/tooltip.mail_sms_configuration_sms_endpoint')"><i
                                                            class="ik ik-help-circle text-muted ml-1"></i>
                                                    </a>
                                                </label>
                                                <div class="col-sm-5">
                                                    <textarea type="text" name="whatsapp_api_key" class="form-control" cols="2"
                                                        placeholder=" Enter SMS Endpoint" required>{{ getSetting('whatsapp_api_key', @$setting) }}</textarea>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="submit"
                                                class="btn btn-primary mr-2"> @lang('admin/ui.save_update') </button>
                                        </div>
                                    </form>
                                </div>
                                @endif
                                @if (getSetting('fcm_activation', @$setting))
                                <div class="tab-pane fade @if (request()->has('name') && request()->get('name') == 'fcm_config') show active @endif"
                                    id="current-month" role="tabpanel" aria-labelledby="pills-timeline-tab">
                                    <div class="card-header p-0 justify-content-between">
                                        <h3> @lang('admin/ui.fcm_configuration') </h3>
                                        <a href="javascript:void(0);" class="btn btn-outline-danger text-color-white openFCMModal mb-2"
                                            data-type="Mail"><i class="ik ik-mail"></i>
                                             @lang('admin/ui.test_fcm_config') </a>
                                    </div>
                                    <form class="forms-sample ajaxForm"
                                        action="{{ route('panel.admin.mail-sms-configuration.notification.store') }}"
                                        method="post" enctype="multipart/form-data">
                                        @csrf
                                        {{-- <input type="hidden" name="group_name" value="fcm_api_setting"> --}}
                                        <x-input name="group_name" placeholder="Enter Name" type="hidden"
                                            tooltip="" regex="" validation="" value="fcm_api_setting" />

                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label for="fcm_sender_id"
                                                    class="col-sm-2 col-form-label"> @lang('admin/ui.sender_id') <span
                                                        class="text-red">*</span>
                                                    <a data-toggle="tooltip" href="javascript:void(0);"
                                                        title="@lang('admin/tooltip.mail_sms_configuration_fcm_sender_id')"><i
                                                            class="ik ik-help-circle text-muted ml-1"></i></a>
                                                </label>
                                                <div class="col-sm-5">
                                                    <input type="text" name="fcm_sender_id" class="form-control"
                                                        value="{{ getSetting('fcm_sender_id', @$setting) }}"
                                                        placeholder="Sender ID" required>
                                                </div>
                                                <div class="col-sm-5">
                                                    <span class="text-warning">
                                                        Create Firebase Account. <br>
                                                        Create Project <br>
                                                        Click the Cloud Messaging tab next to the General tab.
                                                        Copy Sender ID, and the Server key.
                                                    </span>
                                                </div>
                                                <label for="fcm_server_key" class="col-sm-2 col-form-label"
                                                    style="margin-top: -25px;"> @lang('admin/ui.server_key') <span
                                                        class="text-red">*</span>
                                                    <a data-toggle="tooltip" href="javascript:void(0);"
                                                        title="@lang('admin/tooltip.mail_sms_configuration_fcm_server_key')"><i
                                                            class="ik ik-help-circle text-muted ml-1"></i></a>
                                                </label>
                                                <div class="col-sm-5">
                                                    <input type="text" name="fcm_server_key" class="form-control"
                                                        value="{{ getSetting('fcm_server_key', @$setting) }}"
                                                        style="margin-top: -25px;" placeholder="Server Key" required>
                                                </div>

                                            </div>

                                        </div>
                                        <div class="card-footer text-right">
                                            <button type="submit"
                                                class="btn btn-primary mr-2"> @lang('admin/ui.save_update') </button>
                                        </div>
                                    </form>
                                </div>
                                @endif
                            </div><!--tab content-->
                        </div>
                    </div><!--tab panel-->

                </div>
            </div>
        </div>
    </div>

    {{-- SEND MAIL MODAL --}}
    <div class="modal fade" id="OpenSendModal" tabindex="-1" role="dialog" aria-labelledby="demoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-600" id="demoModalLabel"> @lang('admin/ui.modal_title') </h5>
                    <img class="img-fluid" id="image"
                        src="{{ asset('/site/assets/img/zstarter-ReceivingEmail-01.png') }}" height="100px"
                        width="300px" alt="" style="margin-top: 3rem;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <form action="{{ route('panel.admin.mail-sms-configuration.test.send') }}" method="post"
                    class="ajaxForm">
                    @csrf
                    {{-- <input type="hidden" name="type" id="type"> --}}
                    <x-input name="type" id="type" placeholder="Enter Name" type="hidden" tooltip=""
                        regex="" validation="" value="" />
                    <div class="modal-body">
                        <div class="form-group mail">
                            <input type="email" name="email" id="" class="form-control"
                                placeholder="Enter your valid Email">
                        </div>
                        <div class="form-group sms">
                            <input type="number" name="phone" pattern="^[0-9]*$" min="0" id=""
                                class="form-control" placeholder="Enter your valid Contact Number">
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" class="close" aria-label="Close"
                            data-dismiss="modal"> @lang('admin/ui.close') </button>
                        <button type="submit" id="submitButton"
                            class="btn btn-primary"> @lang('admin/ui.send_test_email') </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('panel.admin.include.modal.broadcast')
@endsection
<!-- push external js -->
@push('script')
    {{-- START JS HELPERS INIT --}}
    <script>
        $('.openModal').click(function() {
            var type = $(this).data('type');
            if (type == 'Mail') {
                $('.sms').hide();
                $('.mail').show();
            } else {
                $('.sms').show();
                $('.mail').hide();
                $('#submitButton').text('Send Test Sms');
                $('#image').attr('src', '{{ asset('/site/assets/img/zstarter-Receivingsms-01.png') }}');

            }
            $('#type').val(type);
            $('#demoModalLabel').html('Send ' + type);
            $('#OpenSendModal').modal('show');
        });
        $('.openWhatsappModal').click(function() {
            var type = $(this).data('type');
        });
        $('.openFCMModal').click(function() {
            $('#addBrodcast').modal('show');
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
            var response = postData(method, route, 'json', data, null, null);
            if (typeof(response) != "undefined" && response !== null && response.status == "success") {

            }
        })
    </script>
    {{-- END AJAX FORM INIT --}}
@endpush
