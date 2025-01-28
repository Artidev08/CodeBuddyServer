<div class="modal fade" id="bankDetailsModalCenter" tabindex="-1" role="dialog"
    aria-labelledby="bankDetailsModalCenterLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bankDetailsModalCenterLabel">@lang('admin/ui.add_bank_detail')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('panel.admin.payout-details.store') }}" method="post">
                    @csrf
                    {{-- <x-input name="user_id" placeholder="" type="hidden" tooltip="" regex="" validation=""
                        value="{{ @$user->id }}" /> --}}
                        <input type="hidden" id="user_id" name="user_id" value="{{ @$user->id }}">

                    <x-input name="request_with" placeholder="" type="hidden" tooltip="" regex=""
                        validation="" value="create" />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group {{ @$errors->has('type') ? 'has-error' : '' }}">
                               
                                <x-label name="accountName" validation="common_name" tooltip="" />
                                <x-input name="account_holder_name"
                                    placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.accountHolder') }}"
                                    type="text" tooltip="" regex="name" validation="common_name"
                                    value="" />
                                <x-message name="account_holder_name" :message="@$message" />
                            </div>
                        </div>
                        <div class="col-md-12 mx-auto">
                           
                            <div class="form-group {{ @$errors->has('bank_name') ? 'has-error' : '' }}">
                                <label for="bank_name" class="control-label">@lang('admin/ui.bank')
                                    @if (@validation('bank_name')['pattern']['mandatory'])
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <select class="form-select form-control" name="bank_name" id="bank_name">
                                    @foreach (@\App\Models\PayoutDetail::BANK_NAMES as $key => $bank_name)
                                        <option value="{{ @$key }}">{{ @$bank_name['label'] ?? '--' }}
                                        </option>
                                    @endforeach
                                </select>
                                
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group {{ @$errors->has('account_no') ? 'has-error' : '' }}">
                               
                                <x-label name="accountNo" validation="bank_account_number" tooltip="" />
                                <x-input name="account_no" id="numberInput" placeholder="{{ __('admin/ui.accountNo') }}"
                                    type="number" tooltip="" regex="account_number"
                                    validation="bank_account_number" value="" />
                            </div>
                            <div class="form-group {{ @$errors->has('ifsc_code') ? 'has-error' : '' }}">
                                
                                <x-label name="ifscCode" validation="bank_ifsc_code" tooltip="" />
                                <x-input name="ifsc_code" placeholder="Enter ifsc code " type="text" tooltip=""
                                    regex="subject" validation="bank_ifsc_code" value="" />
                            </div>
                            <div class="form-group {{ @$errors->has('branch') ? 'has-error' : '' }}">
                                
                                <x-label name="branch" validation="branch_name" tooltip="" />
                                <x-input name="branch" placeholder="{{ __('admin/ui.branch') }}" type="text"
                                    tooltip="" regex="subject" validation="branch_name" value="" />
                            </div>
                        </div>
                        <div class="col-md-12">
                           
                            @php
                                $types = [__('current'), __('saving')];
                            @endphp
                        </div>
                        <div class="col-md-12 row">
                            
                            <div class="col-6">
                            <x-label name="accountType" validation="" tooltip="" />
                            <x-radio checked name="type" type="radio" valueName="id"
                                value="current" :arr="@$types" />
                            <x-message name="type" :message="@$message" />

                        </div>
                        </div>
                        <div class="col-12 form-group text-right">
                            <button type="submit" class="btn btn-primary">@lang('admin/ui.create')</button>
                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('script')
    {{-- START NUMBER INPUT INIT --}}
    <script>
        const numberInput = document.getElementById('numberInput');
        const maxLength = 12;
        // Attach an event listener to the input event
        numberInput.addEventListener('input', function() {
            // Trim the input value to the maximum allowed length
            if (numberInput.value.length > maxLength) {
                numberInput.value = numberInput.value.slice(0, maxLength);
            }
        });
    </script>
    {{-- END NUMBER INPUT INIT --}}
@endpush
