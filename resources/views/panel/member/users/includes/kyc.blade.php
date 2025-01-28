<div class="">
    @php
        @$kyc_record = null;
        if (@$user_kyc && isset($user_kyc->details) && @$user_kyc->details != null) {
            @$kyc_record = json_decode(@$user_kyc->details, true);
        }
    @endphp
    <div class="card-body">
        {{-- Status --}}
        @if (isset($user_kyc) && @$user_kyc->status == \App\Models\UserKyc::STATUS_VERIFIED)
            <div class="alert alert-success">
                @lang('admin/ui.user_verification_request_verified')
            </div>
        @elseif(isset($user_kyc) && @$user_kyc->status == \App\Models\UserKyc::STATUS_REJECTED)
            <div class="alert alert-danger">
                @lang('admin/ui.user_verification_request_rejected')
            </div>
        @elseif(isset($user_kyc) && @$user_kyc->status == \App\Models\UserKyc::STATUS_UNDER_APPROVAL)
            <div class="alert alert-warning">
                @lang('admin/ui.user_submitted_verification_request')
            </div>
        @else
            <div class="alert alert-info text-color-white">
                <i class="ik ik-alert-triangle"></i> @lang('admin/ui.document_not_submit')
            </div>
        @endif

        <form action="{{ route('panel.admin.users.update-kyc-status', $user->id) }}" method="POST"
            class="form-horizontal">
            @csrf
            <input id="status" type="hidden" name="status" value="">
            <input type="hidden" name="user_id" value="{{ @$user->id }}">
            <div class="row">
                <div class="col-md-6 col-6"><label> @lang('admin/ui.document') </label>
                    <br>
                    <h5 class="strong text-muted">{{ @$kyc_record['document_type'] ?? '--' }}</h5>
                </div>
                <div class="col-md-6 col-6"><label> @lang('admin/ui.document_no') </label>
                    <br>
                    <h5 class="strong text-muted">{{ Str::limit(@$kyc_record['document_number'] ?? '--', 25) }}</h5>
                </div>
                <div class="col-md-6 col-6"><label> @lang('admin/ui.front_side') </label>
                    <br>
                    @if (@$kyc_record != null && @$kyc_record['document_front'] != null)

                        <a href="{{ asset(@$kyc_record['document_front']) }}" data-toggle="modal"
                            data-target="#filePreviewModal" class="open-modal btn btn-outline-danger text-color-white">@lang('admin/ui.preview')
                        </a>
                    @else
                        <button disabled class="btn btn-secondary">@lang('admin/ui.not_submitted')</button>
                    @endif
                </div>
                <div class="col-md-6 col-6"><label> @lang('admin/ui.back_side') </label>
                    <br>
                    @if (@$kyc_record != null && @$kyc_record['document_back'] != null)
                        @if (@$kyc_record != null && @$kyc_record['document_back'] != null)

                            <a href="{{ asset(@$kyc_record['document_back']) }}" data-toggle="modal"
                                data-target="#filePreviewModal" class="open-modal btn btn-outline-danger text-color-white">@lang('admin/ui.preview')
                            </a>
                        @else
                            <button disabled class="btn btn-secondary">@lang('admin/ui.not_submitted')</button>
                        @endif
                    @else
                        <button disabled class="btn btn-secondary">@lang('admin/ui.not_submitted')</button>
                    @endif
                </div>

                <hr class="m-2">
                @if (auth()->user()->hasRole('admin'))
                    @if (isset($user_kyc) && @$user_kyc->status == \App\Models\UserKyc::STATUS_VERIFIED)
                        <div class="col-md-12 col-12 mt-5">
                            <label> @lang('admin/ui.note') </label>
                            <textarea class="form-control" name="remark" type="text">{{ @$Verification['admin_remark'] ?? '' }}</textarea>
                            <button type="submit" class="btn btn-danger mt-2 btn-lg reject">@lang('admin/ui.reject')</button>
                        </div>
                    @elseif(isset($user_kyc) && @$user_kyc->status == \App\Models\UserKyc::STATUS_REJECTED)
                        <div class="col-md-12 col-12 mt-5">
                            <button type="submit" class="btn btn-warning mt-2 btn-lg reset">@lang('admin/ui.reset')</button>
                        </div>
                    @elseif(isset($user_kyc) && @$user_kyc->status == \App\Models\UserKyc::STATUS_UNDER_APPROVAL)
                        <div class="col-md-12 col-12 mt-5"><label> @lang('admin/ui.rejection_reason') (@lang('admin/ui.if_any'))</label>
                            <textarea class="form-control" name="remark" type="text">{{ @$kyc_record['admin_remark'] ?? '' }}</textarea>
                            <button type="submit" class="btn btn-danger mt-2 btn-lg reject">@lang('admin/ui.reject')</button>
                            <button type="submit" class="btn btn-success accept ml-5 accept mt-2 btn-lg">@lang('admin/ui.accept')
                            </button>
                        </div>
                    @endif
                @endif
            </div>
        </form>
    </div>
</div>
<div class="">
    <form class="" action="{{ route('panel.admin.users.verified-status', $user->id) }}" method="get">
        @csrf
        <input type="hidden" name="request_with" value="create">
        <div class="card-body">
            <div class="row border-top">
                <div class="col-md-6  mt-4"><label>@lang('admin/ui.email'):</label>
                    <span
                        class="badge {{ @$user->email_verified_at == '' ? 'badge-pill badge-warning' : 'badge-pill text-leaf badge-primary' }}">
                        {{ @$user->email_verified_at == null ? __('admin/ui.not_verified') : __('admin/ui.verified') }}
                    </span>
                    <br>
                    <div class="form-group mt-2">
                        <input type="date" class="form-control" name="email_verified_at"
                            value="{{ \Carbon\Carbon::parse($user->email_verified_at)->format('Y-m-d') }}">
                    </div>
                </div>

                <div class="col-md-6 mt-4"><label>@lang('admin/ui.phone'):</label>
                    <span
                        class="badge {{ @$user->phone_verified_at == '' ? 'badge-pill badge-warning' : 'badge-pill text-leaf badge-primary' }}">
                        {{ @$user->phone_verified_at == '' ? __('admin/ui.not_verified') : __('admin/ui.verified') }}
                    </span>
                    <br>
                    <div class="form-group mt-2">
                        <input type="date" class="form-control" name="phone_verified_at"
                            value="{{ \Carbon\Carbon::parse($user->phone_verified_at)->format('Y-m-d') }}">
                    </div>
                </div>
            </div>
            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary"> @lang('admin/ui.create') </button>
            </div>
        </div>
    </form>
</div>
{{-- preview modal --}}
<div class="modal fade" id="filePreviewModal" tabindex="-1" role="dialog" aria-labelledby="filePreviewModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filePreviewModalLabel">@lang('admin/ui.file_preview')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Image container -->
                <div id="previewImageContainer">
                    <!-- Dynamic image will be added here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('admin/ui.close')</button>
            </div>
        </div>
    </div>
</div>
{{-- preview modal --}}
