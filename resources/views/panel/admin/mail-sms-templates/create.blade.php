@extends('layouts.main')
@section('title', __('admin/ui.left_sidebar_mail_sms_templates') .' ' .__('admin/ui.add'))
@section('content')
@php
    $breadcrumb_arr = [
        ['name' => __('admin/ui.left_sidebar_mail_sms_templates'), 'url' => 'javascript:void(0);', 'class' => ''],
        ['name' => __('admin/ui.add'), 'url' => 'javascript:void(0);', 'class' => 'active'],
    ];
@endphp

@push('head')
@endpush

<div class="container-fluid container-fluid-height">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-grid bg-blue"></i>
                    <div class="d-inline">
                        <h5> @lang('admin/ui.create_new') {{ __('admin/ui.left_sidebar_mail_sms_templates') }}</h5>
                        <span> @lang('admin/ui.add_new') {{ __('admin/ui.left_sidebar_mail_sms_templates') }}</span>
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
    <form action="{{ route('panel.admin.templates.store') }}" method="post" class="ajaxForm">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3> @lang('admin/ui.add')  {{ __('admin/ui.left_sidebar_mail_sms_templates') }}</h3>
                    </div>
                    <div class="card-body">
                        
                        <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip=""
                            regex="" validation="" value="create" />
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group {{ @$errors->has('code') ? 'has-error' : '' }}">

                                    <x-label name="code" validation="common_code" tooltip="add_mail_sms_template_code" />
                                    <x-input name="code" placeholder="{{ __('admin/ui.enter') .' '. __('admin/ui.code') }}" type="text"
                                        tooltip="add_mail_sms_template_code" regex="template_code" validation="template_code"
                                        value="{{ old('code') }}" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group {{ @$errors->has('subject') ? 'has-error' : '' }}">
                                    <x-label name="subject" validation="template_subject" tooltip="add_mail_sms_template_subject" />
                                    <x-input name="subject" placeholder="{{ __('admin/ui.enter') .' '. __('admin/ui.subject') }}" type="text"
                                        tooltip="add_mail_sms_template_subject" regex="template_subject" validation="template_subject"
                                        value="{{ old('subject') }}" />

                                </div>
                                <span class="alert d-block mt-2 alert-warning text-color-white">
                                    <i class="ik ik-info text-muted ml-1"></i> @lang('admin/ui.template_caption')
                                </span>
                            </div>
                            <div class="form-group mx-3 text-right">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3>  @lang('admin/ui.subject') </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group {{ @$errors->has('type') ? 'has-error' : '' }}">

                                    <x-label name="type" validation="template_type" tooltip="add_mail_sms_template_type" />
                                    <x-select name="type"  value="{{ old('type') }}" valueName="" validation="template_type" id="mailType" class="select2 type" label="Type" optionName="name" :arr="App\Models\MailSmsTemplate::TYPES"/>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group {{ @$errors->has('purpose') ? 'has-error' : '' }}">
                                    <x-label name="purpose" validation="common_short_description" tooltip="add_mail_sms_template_purpose" />
                                    <x-textarea regex="short_description" validation="common_short_description" value="{{ old('purpose') }}" name="purpose"
                                    id="purpose" rows="2" placeholder="{{ __('admin/ui.enter') .' '. __('admin/ui.purpose') }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ @$errors->has('is_permanent') ? 'has-error' : '' }}">
                                    
                                    @php
                                            $isPermanent_arr = ['Yes', 'No'];
                                        @endphp
                                        <x-label name="is_permanent" validation="paragraph_type"
                                            tooltip="add_site_content_managements_permanent" />
                                        <x-radio name="is_permanent" type="radio" value="1" :arr="@$isPermanent_arr" />

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary floating-btn"> @lang('admin/ui.create') </button>
    </form>
</div>

@push('script')
<script src="{{ asset('admin/js/ajaxForm.js') }}"></script>
    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            var d_type = $('#mailType').val();
            var redirectUrl = "{{ url('admin/templates/') }}" + '?type' + `=` + d_type;
            var response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);

        })
    </script>
    {{-- END AJAX FORM INIT --}}
@endpush
@endsection
