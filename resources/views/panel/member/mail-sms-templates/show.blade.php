@extends('layouts.main')
@section('title', $mailSmsTemplate->getPrefix(). ' '. __('admin/ui.left_sidebar_mail_sms_templates'))
@section('content')
@php
    $breadcrumb_arr = [['name' => __('admin/ui.left_sidebar_mail_sms_templates'), 'url' => route('panel.member.templates.index'), 'class' => ''], ['name' => $mailSmsTemplate->getPrefix(), 'url' => route('panel.member.templates.index'), 'class' => ''], ['name' => __('admin/ui.show'), 'url' => route('panel.member.templates.index'), 'class' => 'active']];
@endphp

<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-grid bg-blue"></i>
                    <div class="d-inline">
                        <h5> @lang('admin/ui.text_template') </h5>
                        <span> @lang('admin/ui.text_sub_template') </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @include('panel.member.include.breadcrumb')
            </div>
        </div>
    </div>
    <div class="row">
        <!-- start message area-->
        @include('panel.member.include.message')
        <!-- end message area-->
        <div class="col-md-12 mx-auto">
            <div class="card ">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3> @lang('admin/ui.mail_template') </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th width="15%"> @lang('admin/ui.id') </th>
                                    <td>{{ @$mailSmsTemplate->getPrefix() }}</td>
                                </tr>
                                <tr>
                                    <th>  @lang('admin/ui.code')  </th>
                                    <td> {{ @$mailSmsTemplate->code ?? '--' }} </td>
                                </tr>
                                <tr>
                                    <th>  @lang('admin/ui.subject') </th>
                                    <td> {{ @$mailSmsTemplate->subject ?? '--' }} </td>
                                </tr>
                                </tr>
                                <tr>
                                    <th>  @lang('admin/ui.type') </th>
                                    <td> {{ @$mailSmsTemplate->type == 1 ? 'Mail' : (@$mailSmsTemplate->type == 2 ? 'Sms' : 'Whatsapp') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>  @lang('admin/ui.description') </th>
                                    <td> {!! nl2br(@$mailSmsTemplate->content) !!} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
