<!-- Modal -->
<div class="modal fade" id="walletModal" tabindex="-1" role="dialog" aria-labelledby="walletModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> @lang('admin/ui.place_transaction') </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="ajaxForm" action="{{ route('panel.admin.wallet-logs.update') }}" method="POST">
                @csrf
                <x-input name="user_id" id="uuid" placeholder="Enter Name" type="hidden" tooltip=""
                    regex="" validation="" value="" />
                <x-input name="role" placeholder="Enter Name" type="hidden" tooltip="" regex=""
                    validation="" value="{{ request()->get('role') }}" />

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">

                                <p class="mb-0 pb-0 text-color-black"> @lang('admin/ui.wallet_subtitle') </p>

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-radio">
                                <div class="radio radiofill radio-success radio-inline">
                                    <label class="fw-700">
                                        <input type="radio" name="type" value="credit" class="transationType">
                                        <i class="helper"></i> @lang('admin/ui.credit_balance')
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-radio">
                                <div class="radio radiofill radio-danger radio-inline">
                                    <label class="fw-700">
                                        <input type="radio" name="type" value="debit"
                                            class="transationType"required>
                                        <i class="helper"></i> @lang('admin/ui.debit_balance')
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <input min="1" type="number" class="form-control amount"
                                placeholder="{{ __('admin/ui.amount_hint')}}" name="amount"required>
                        </div>
                    </div>
                    <div class="text-danger mt-2">
                        <i class="ik ik-info"></i>
                     @lang('admin/ui.rollback')
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"> @lang('admin/ui.confirm_transaction') </button>
                </div>
            </form>
        </div>
    </div>
</div>
