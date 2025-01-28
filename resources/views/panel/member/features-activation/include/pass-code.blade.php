<style>
    #accessCodeInput::placeholder {
      font-size: 14px;
      color: #999;
      letter-spacing: 1px;
    }
</style>

<div class="modal fade" id="DelegateAccessModel" tabindex="-1" aria-labelledby="DelegateAccessModelLabel"
     aria-hidden="true">
    <div class="modal-dialog" style="margin-top: 5rem;">
        <div class="modal-content">
            <div class="modal-header bg-custom">
                <h5 class="modal-title" id="DelegateAccessModelLabel">{{ __('admin/ui.Access_Step') }}</h5>
                <button type="button" class="close text-dark" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="accessCodeForm" action="{{ route('panel.admin.setting.features-activation') }}" method="GET">
                    <h5>
                        <strong><span class="delegateUserName"></span>Accessing Feature Activation</strong>
                    </h5>
                    <div class="form-group">
                        <input type="hidden" value="" name="user_id" class="delegateUserId">
                        <label for="accessCodeInput">{{ __('admin/ui.six_digit') }}<span class="text-danger">*</span></label>
                        <input type="number" value="" class="form-control text-center" id="accessCodeInput" placeholder="Enter Your Passcode"
                               name="delegate_access" required style="font-size: 25px; letter-spacing: 10px; padding:5px;">
                        <div class="mt-2">
                            <button class="btn btn-primary d-block w-50 mx-auto text-center btn-sm mt-1"
                                    style="border: #65b530"
                                    type="submit">{{ __('admin/ui.access_activation') }}</button>
                        </div>
                        <div id="errorMessage" class="text-danger mt-2" style="display: none;">
                            Invalid passcode. Please try again.
                        </div>
                    </div>
                </form>
                <hr>
                <div>
                    <div class="text-muted text-center" style="">
                        <i class="ik ik-info text-success"></i>
                        {{ __('admin/ui.security_message') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
