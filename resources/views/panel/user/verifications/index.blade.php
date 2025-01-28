@extends('layouts.user')

@section('meta_data')
@php
$meta_title = __('user/ui.verifications') . ' | ' . getSetting('app_name');
$meta_description = '' ?? getSetting('seo_meta_description');
$meta_keywords = '' ?? getSetting('seo_meta_keywords');
$meta_motto = '' ?? getSetting('site_motto');
$meta_abstract = '' ?? getSetting('site_motto');
$meta_author_name = '' ?? 'Defenzelite';
$meta_author_email = '' ?? 'support@defenzelite.com';
$meta_reply_to = '' ?? getSetting('app_email');
$meta_img = ' ';
$customer = 1;
$userPanel = true;
@endphp
@endsection

@push('style')
<style>
    /* Style Comes Here */
    .card-header {
        font-size: 18px;
        /* Slightly larger header text */
        font-weight: bold;
    }

    .card-title {
        font-size: 20px;
        /* Slightly larger title text */
        margin-top: 20px;
        /* Adds space above the title */
    }

    .card-title .fa-check-circle {
        color: #28a745;
        /* Green color for consistency with the card */
        margin-bottom: 10px;
        /* Adds space below the icon */
    }

    .card-body {
        padding: 20px;
        /* More padding inside the card body */
    }
     .status-header {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 20px;
    }
</style>
@endpush
@section('content')

<div class="row">
    <div class="main-card" style="margin-top: -7px">
        @include('panel.user.include.message')
        <div class="status-header">
            @if($user_kyc && isset($user_kyc->status))
            @php
                $status = $user_kyc->status ?? \App\Models\UserKyc::STATUS_UNDER_APPROVAL;
                $statusLabel = \App\Models\UserKyc::STATUSES[$status]['label'];
                $statusColor = \App\Models\UserKyc::STATUSES[$status]['color'];
            @endphp
            <div class="alert alert-{{ $statusColor }}">
                <strong>Status: </strong> {{ $statusLabel }}
            </div>
        </div>
        @endif
        @php
        $sectionHeader = [
        'headline' => __('user/ui.account_verification'),
        'sub_headline' => __('user/ui.check_verification_status'),
        'btn_label' => 'Request Payout',
        'btn_link' => 'javascript:void(0);',
        'btn_visibility' => false,
        'btn_id' => 'payout-btn',
        'btn_alt' => $user_kyc != null && isset($user_kyc->status) ?
        \App\Models\UserKyc::STATUSES[@$user_kyc->status]['label'] : 'Not Submitted'
        ];
        $is_disabled = false;
        if($user_kyc != null && isset($user_kyc->status)){
        if($user_kyc->status == \App\Models\UserKyc::STATUS_UNDER_APPROVAL || $user_kyc->status ==
        \App\Models\UserKyc::STATUS_VERIFIED){
        $is_disabled = true;
        }
        }
        @endphp

        @include('panel.user.partials.section_header')

        @if (getSetting('eKyc_verification_activation') == 1)
        @if (auth()->user()->ekyc_status == 2)
        @php
        $kyc_data = json_decode(auth()->user()->ekyc_info);
        @endphp
        @if (!is_null($kyc_data) && $kyc_data->admin_remark != null)
        <div style="font-size: 16px;" class="alert alert-danger d-flex justify-content-between" role="alert">
            <span class="m-0 p-0" style="line-height: 40px;">
                {{ @$kyc_data->admin_remark ?? '' }}
            </span>
        </div>
        @endif
        @endif
        <div class="col-lg-8 ">

            @if($user_kyc && $user_kyc->status == \App\Models\UserKyc::STATUS_VERIFIED)
            <div class="card border-success mb-3">
                <div class="card-header bg-success text-white">
                @lang('user/ui.verification_status')
                </div>
                <div class="card-body text-success text-center">
                    <h5 class="card-title">
                        <i class="fas fa-check-circle fa-3x text-success"></i>
                        <br> @lang('user/ui.already_verified')
                    </h5>
                    <p class="card-text">@lang('user/ui.documents_verified_approved')</p>
                </div>
            </div>

            @else
            <form id="myForm" action="{{ route('panel.user.verify.store') }}" method="post"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="status" value="{{ $user_kyc->status ?? 0 }}">

                <div class="card">
                    <div class="card-body p-lg-5 p-md-4 p-2">
                        <div class="row">

                            <div class="col-lg-6 mb-3">
                                <div class="form-select-wrapper">
                                    <label for="address" class="form-label fs-14">@lang('user/ui.select_document')<span
                                        class="text-danger">*</span></label>
                                    <select id="documentTypeSelect" class="form-control select2" name="document_type"
                                        required @if (isset($user_kyc) && $user_kyc->status != 2) disabled @endif>
                                        <option value="" aria-readonly="true">@lang('user/ui.select_document_type')<span
                                                class="text-danger" style="color:red">*</span>
                                        </option>
                                        <option @if (isset($ekyc) && $ekyc['document_type']=='Pan Card' ) selected
                                            @endif value="Pan Card" readonly>@lang('user/ui.pan_card')
                                        </option>
                                        <option @if (isset($ekyc) && $ekyc['document_type']=='Aadhar Card' ) selected
                                            @endif value="Aadhar Card" readonly>@lang('user/ui.aadhar_card')
                                        </option>
                                    </select>
                                    <div class="invalid-feedback"> Please select a valid Document.</div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="address" class="form-label fs-14">@lang('user/ui.document_no') <span
                                    class="text-danger">*</span></label>
                                <div class="form-floating">
                                    <input id="documentNumberInput" name="document_number" type="text" maxlength="15"
                                        class="form-control" value="{{ @$ekyc['document_number'] ?? '' }}"
                                        placeholder="Document Number" required @if ($is_disabled) disabled @endif>
                                    <label for="branch">@lang('user/ui.document_no') <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="document_front_attachment" class="form-label fs-14">@lang('user/ui.document_front_photo') <span class="text-danger">*</span></label>
                                <div class="mb-3">
                                    <input class="form-control" name="document_front_attachment" @if($is_disabled)
                                        disabled @endif accept=".jpg,.png,.jpeg," size="" required type="file">
                                    <div class="invalid-feedback">Example invalid form file feedback</div>
                                </div>
                            </div>
                            <div class="col-lg-6 mb-3">
                                <label for="address" class="form-label fs-14">@lang('user/ui.document_back_photo') <span
                                        class="text-danger">*</span></label>
                                <div class="mb-3">
                                    <input class="form-control" name="document_back_attachment"
                                        accept=".jpg,.png,.jpeg," required type="file" @if($is_disabled) disabled
                                        @endif>
                                    <div class="invalid-feedback">Example invalid form file feedback</div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-icon position-relative">
                                    @if (isset($ekyc) && ($ekyc['document_back'] != null || $ekyc['document_front'] !=
                                    null))
                                    <div class="row">
                                        @if ($ekyc['document_front'] != null)
                                        <div class="col-6">
                                            <a href="{{ asset($ekyc['document_front']) }}" target="_blank">
                                                <span class="badge bg-info mt-2 p-2 "style="background-color: #f2f2f9 !important;"><i class="uil uil-eye pr-2" style="color: black;"></i>
                                                    </span>
                                            </a>
                                            <a href="#" data-toggle="modal" data-target="#filePreviewModal"
                                                class="open-modal badge bg-info p-2 front_photo"
                                                data-document-type="front" style="background-color: #f2f2f9 !important; color:black">@lang('user/ui.preview')
                                            </a>
                                        </div>
                                        @endif
                                        @if ($ekyc['document_back'] != null)
                                        <div class="col-6">
                                            <a href="{{ asset($ekyc['document_back']) }}" target="_blank">
                                                <span class="badge bg-info mt-2 p-2" style="background-color: #f2f2f9 !important;"><i class="uil uil-eye pr-2 "style="color: black;"></i>
                                                    </span>
                                            </a>
                                            <a href="#" data-toggle="modal" data-target="#filePreviewModal"
                                                class="open-modal badge bg-info p-2" data-document-type="back" style="background-color: #f2f2f9 !important; color:black">@lang('user/ui.preview')
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                    @else
                                    <span class="text-muted">@lang('user/ui.not_uploaded_yet')</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 text-right mt-2">
                                @if (!isset($user_kyc))
                                <div class="form-group d-lg-bolck d-md-block d-flex align-items-center">
                                    <input type="checkbox" class="align-self-baseline mt-lg-0 mt-1 me-lg-0 me-1"
                                        name="hereBy" required id="hereBy">
                                    <label for="hereBy">@lang('user/ui.certify_documents_correct')<span class="text-danger">*</span></label>
                                </div>
                                @else

                                @endif
                                <button type="submit" class="btn btn-primary float-end" id="submitButton"
                                    @if($is_disabled) disabled @endif>
                                @lang('user/ui.submit')
                                </button>
                                @if (($user_kyc && $user_kyc->status == \App\Models\UserKyc::STATUS_UNDER_APPROVAL))

                                @endif
                                <input type="hidden" id="formSubmitted" name="formSubmitted"
                                    value="{{ $formSubmitted ?? 0 }}">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            @endif
        </div>
        @endif
    </div>
</div>

{{--preview modal--}}
<div class="modal fade" id="filePreviewModal" tabindex="-1" role="dialog" aria-labelledby="filePreviewModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title">@lang('user/ui.file_preview')</h5>
                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal">
                    <i class="uil uil-times fs-4 text-dark" style="display: inline !important;"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="previewImageContainer">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="close-modal">
                    @lang('user/ui.close')
                </button>
            </div>
        </div>
    </div>
</div>
{{--preview modal--}}
@include('panel.user.modal.ekyc')
@endsection

@push('script')

<link href="{{ asset('panel/user/assets/plugins/fontawesome-6.5.1/all.min.js') }}">


{{-- START PREVIEW MODAL INIT --}}
<script>
    $(document).ready(function () {
            $('.open-modal').on('click', function () {
                var documentType = $(this).data('document-type');
                var documentSrc;

                if (documentType === 'front') {
                    documentSrc = `{{ asset(@$ekyc['document_front']) }}`;
                } else if (documentType === 'back') {
                    documentSrc = `{{ asset(@$ekyc['document_back']) }}`;
                }

                $('#previewImageContainer').html(`<img src="${documentSrc}" class="img-fluid" alt="File Preview">`);
                $('#filePreviewModal').modal('show');
            });

            $('#filePreviewModal').modal({
                show: false
            });
        });
</script>

<script>
    document.getElementById('documentTypeSelect').addEventListener('change', function() {
        var selectedValue = this.value;
        var documentNumberInput = document.getElementById('documentNumberInput');

        if (selectedValue === 'Pan Card') {
            documentNumberInput.pattern = '[A-Z]{5}[0-9]{4}[A-Z]{1}'; // Pattern for PAN Card
            documentNumberInput.placeholder = 'Enter PAN Card Number (e.g., ABCDE1234F)';
        } else if (selectedValue === 'Aadhar Card') {
            documentNumberInput.pattern = '\\d{12}'; // Pattern for Aadhar Card (12 digits)
            documentNumberInput.placeholder = 'Enter Aadhar Card Number (12 digits)';
        } else {
            documentNumberInput.pattern = ''; // No pattern
            documentNumberInput.placeholder = 'Enter Document Number';
        }
    });
</script>

{{-- END PREVIEW MODAL INIT --}}
@endpush
