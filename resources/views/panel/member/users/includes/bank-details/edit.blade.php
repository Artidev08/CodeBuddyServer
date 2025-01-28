<div class="modal fade" id="editBankDetailsModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> @lang('admin/ui.updateBankDetail') </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('panel.admin.payout-details.update') }}" method="post">

                    <x-input name="id" id="payoutdetailId" placeholder="" type="hidden" tooltip=""
                        regex="" validation="" value="" />
                    <x-input name="user_id" placeholder="" type="hidden" tooltip="" regex="" validation=""
                        value="" />
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mx-auto">
                            <div class="form-group {{ @$errors->has('type') ? 'has-error' : '' }}">
                               
                                <x-label name="accountName" validation="common_name" tooltip="" />
                                <x-input name="account_holder_name" id="editaccount_holder_name"
                                    placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.accountHolder') }}"
                                    type="text" tooltip="" regex="name" validation="common_name"
                                    value="" />
                                <x-message name="account_holder_name" :message="@$message" />
                            </div>
                            <div class="form-group {{ @$errors->has('bank_name') ? 'has-error' : '' }}">
                                <label for="bank_name" class="control-label"> @lang('admin/ui.bank') <span
                                        class="text-danger">*</span></label>
                                <select name="bank_name" id="editbank" class="form-select form-control"
                                    aria-label="Default select example" required>
                                    @foreach (App\Models\PayoutDetail::BANK_NAMES as $key => $option)
                                        <option value="{{ $key }}"
                                            @if ($option['label'] == 1) selected @endif>
                                            {{ $option['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                                

                            </div>
                            <div class="form-group {{ @$errors->has('account_no') ? 'has-error' : '' }}">
                                
                                    <x-label name="accountNo" validation="bank_account_number" tooltip="" />
                                <x-input name="account_no" id="editaccount_no" placeholder="{{ __('admin/ui.accountNo') }}"
                                    type="number" tooltip="" regex="account_number"
                                    validation="bank_account_number" value="" />
                            </div>

                            <div class="form-group {{ @$errors->has('ifsc_code') ? 'has-error' : '' }}">
                               
                                    <x-label name="ifscCode" validation="bank_ifsc_code" tooltip="" />
                                    <x-input name="ifsc_code" id="editifsc_code" placeholder="Enter ifsc code " type="text" tooltip=""
                                        regex="subject" validation="bank_ifsc_code" value="" />
                            </div>

                            <div class="form-group {{ @$errors->has('branch') ? 'has-error' : '' }}">
                               
                                    <x-label name="branch" validation="branch_name" tooltip="" />
                                <x-input name="branch" id="editbranch" placeholder="{{ __('admin/ui.branch') }}" type="text"
                                    tooltip="" regex="subject" validation="branch_name" value="" />
                            </div>

                            <div class="col-md-12">
                                @php
                                    $types = [__('admin/ui.current'), __('admin/ui.saving')];
                                @endphp
                            </div>
                                <label for=""> @lang('admin/ui.accountType') <span class="text-danger">*</span></label>
                            <div class="form-check">
                                <input name="type" value="Current" type="radio" class="form-check-input pb-1"
                                    id="editcurrent" required="">
                                <label class="form-check-label pl-2 mb-1 " for="current">@lang('admin/ui.current')</label>
                             </div>
                             <div class="form-check">
                                <input name="type" value="Saving" type="radio" class="form-check-input pb-1"
                                    id="editsaving" required="">
                                <label class="form-check-label pl-2 mb-1 " for="saving">@lang('admin/ui.saving')</label>
                             </div>
                             
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary"> @lang('admin/ui.update') </button>
                               
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
