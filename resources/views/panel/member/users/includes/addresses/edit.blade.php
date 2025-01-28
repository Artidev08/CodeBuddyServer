<style>
    .iti--inline-dropdown .iti__dropdown-content {
        z-index: 9 !important;
    }
</style>
<div class="modal fade" id="editAddressModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"> @lang('admin/ui.updateAddress') </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
            <form action="{{ route('panel.admin.addresses.update') }}" method="post">
                <x-input name="id" id="addressId" placeholder="" type="hidden" tooltip="" regex=""
                    validation="" value="" />
                <x-input name="user_id" id="user_id" placeholder="" type="hidden" tooltip="" regex=""
                    validation="" value="{{ @$user->id }}" />

                @csrf
                <div class="row">
                    <div class="col-md-12 row mx-auto">
                        <div class="col-md-12">
                            <div class="form-group {{ @$errors->has('name') ? 'has-error' : '' }}">

                                <x-label name="name" validation="common_name" tooltip="" />
                                <x-input name="name" id="editName"
                                    placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.name') }}"
                                    type="text" tooltip="" regex="name" validation="common_name"
                                    value="" />
                                <x-message name="name" :message="@$message" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group {{ @$errors->has('phone') ? 'has-error' : '' }}">

                                <x-label name="phone" validation="common_phone_number" tooltip="" />
                                {{-- <x-input name="phone" id="editPhone"
                                    placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.phone_number') }}"
                                    id="editPhone" type="number" tooltip="" regex="phone_number"
                                    validation="common_phone_number" value="{{ old('phone') }}" />
                                <x-message name="phone" :message="@$message" /> --}}
                                <div class="input-group">
                                    <input type="hidden" id="editAddressCountryCodeInput" name="country_code"
                                        value="">
                                    <input type="tel" style="width: 13.5rem !important;"class="form-control"
                                        id="editAddressCountryCode" name="phone" value="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group {{ @$errors->has('pincode') ? 'has-error' : '' }}">

                                <x-label name="pincode" validation="address_pin_code" tooltip="" />
                                <x-input name="pincode"
                                    placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.pincode') }}"
                                    id="pincode_id" type="number" tooltip="" regex="pin_code"
                                    validation="address_pin_code" value="{{ old('pin_code') }}" />
                                <x-message name="pin_code" :message="@$message" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group {{ @$errors->has('type') ? 'has-error' : '' }}">
                                @php
                                    $type = [__('home'), __('office')];
                                @endphp
                            </div>
                        </div>

                        <div class="col-md-12 row" style="margin-top: -15px">
                            <div class="col-6">
                                <x-label name="addrType" validation="common_name" tooltip="" />
                                <x-radio checked name="gender" id="homeEdit" id="officeEdit" type="radio"
                                    valueName="id" value="{{ $user->type }}" :arr="@$type" />
                                <x-message name="gender" :message="@$message" />
                            </div>

                        </div>
                        <div class="col-md-12">
                            <div class="form-group {{ @$errors->has('address_1') ? 'has-error' : '' }}">

                                <x-label name="primary_address" validation="common_address" tooltip="" />
                                <x-input name="address_1"
                                    placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.address') }}"
                                    id="editAddress" type="text" tooltip="" regex="address"
                                    validation="common_address" value="{{ old('address') }}" />
                                <x-message name="address_1" :message="@$message" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group {{ @$errors->has('address_2') ? 'has-error' : '' }}">

                                <x-label name="secondary_address" validation="common_address" tooltip="" />
                                <x-input name="address_2"
                                    placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.address') }}"
                                    id="editAddress_2" type="text" tooltip="" regex="address"
                                    validation="common_address" value="{{ old('address') }}" />
                                <x-message name="address_2" :message="@$message" />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group {{ @$errors->has('country_id') ? 'has-error' : '' }}">

                                <x-label name="country" validation="country_name" tooltip="" />
                                <x-select name="country_id" id="countryEdit" value="{{ old('country_id') }}"
                                    label="Country" optionName="name" valueName="id" class="select2"
                                    :arr="@\App\Models\Country::all()" validation="country_name" />
                                <div class="invalid-feedback">
                                    Please select a valid country.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group {{ @$errors->has('state_id') ? 'has-error' : '' }}">

                                <x-label name="state" validation="country_name" tooltip="" />
                                <x-select name="state_id" class="select2" value="{{ old('state_id') }}"
                                    label="State" optionName="name" valueName="id" class="select2"
                                    validation="country_name" id="stateEdit" />
                                <div class="invalid-feedback">
                                    Please provide a valid state.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group {{ @$errors->has('city_id') ? 'has-error' : '' }}">

                                <x-label name="city" validation="country_name" tooltip="" />
                                <x-select name="city_id" value="{{ old('city_id') }}" label="Country"
                                    optionName="name" valueName="id" class="select2" validation="country_name"
                                    id="cityEdit" />
                                <div class="invalid-feedback">
                                    Please provide a valid city.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary"> @lang('admin/ui.update') </button>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
