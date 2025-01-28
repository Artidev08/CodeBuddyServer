
<style>
  .iti--inline-dropdown .iti__dropdown-content {
        z-index: 9 !important;
    }
</style>
<div class="modal fade" id="addressModalCenter" role="dialog" aria-labelledby="addressModalCenterLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-address">
                <h5 class="modal-title" id="addressModalCenterLabel"> @lang('admin/ui.addAddress') </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('panel.admin.addresses.store') }}" method="post">
                    @csrf

                    <x-input name="user_id" placeholder="" type="hidden" tooltip="" regex="" validation=""
                        value="{{ @$user->id }}" />
                    <x-input name="request_with" placeholder="" type="hidden" tooltip="" regex=""
                        validation="" value="create" />
                    <div class="row">
                        <div class="col-12 mx-auto row">
                            <div class="col-md-12">
                                <div class="form-group {{ @$errors->has('name') ? 'has-error' : '' }}">

                                    <x-label name="person_name" validation="common_name" tooltip="" />
                                    <x-input name="name" id="name"
                                        placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.name') }}"
                                        type="text" tooltip="" regex="name" validation="common_name"
                                        value="" />
                                    <x-message name="name" :message="@$message" />
                                </div>
                            </div>

                            <div class="col-md-6 mx-auto">
                                <div class="form-group {{ @$errors->has('phone') ? 'has-error' : '' }}">

                                    <x-label name="phone" validation="" tooltip="" />
                                    {{-- <x-input name="phone"
                                        placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.phone_number') }}"
                                        id="address_phone" type="number" tooltip="" regex="phone_number"
                                        validation="common_phone_number" value="{{ old('phone') }}" />
                                    <x-message name="phone" :message="@$message" /> --}}
                                     <div class="input-group">
                                        <input type="hidden" id="addressCountryCodeInput" name="country_code" value="">
                                        <input style="width: 13.5rem !important;" type="tel" class="form-control"
                                            id="addressPhone" name="phone" value="{{ old('phone') }}">
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-6 mx-auto">
                                <div class="form-group {{ @$errors->has('pincode') ? 'has-error' : '' }}">
                                    <x-label name="pincode" validation="address_pin_code" tooltip="" />
                                    <x-input name="pincode"
                                        placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.pincode') }}"
                                        id="pincode" type="number" tooltip="" regex="pin_code"
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
                                    <x-radio checked name="gender" type="radio" valueName="id" value="office"
                                        :arr="@$type" />
                                    <x-message name="gender" :message="@$message" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group {{ @$errors->has('address_1') ? 'has-error' : '' }}">

                                    <x-label name="primary_address" validation="common_address" tooltip="" />
                                    <x-input name="address_1"
                                        placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.address') }}"
                                        id="address_1" type="text" tooltip="" regex="address"
                                        validation="common_address" value="{{ old('address') }}" />
                                    <x-message name="address_1" :message="@$message" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group {{ @$errors->has('address_2') ? 'has-error' : '' }}">

                                    <x-label name="secondary_address" validation="common_address" tooltip="" />
                                    <x-input name="address_2"
                                        placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.address') }}"
                                        id="address_2" type="text" tooltip="" regex="address"
                                        validation="common_address" value="{{ old('address') }}" />
                                    <x-message name="address_2" :message="@$message" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group {{ @$errors->has('country_id') ? 'has-error' : '' }}">

                                    <x-label name="country" validation="country_name" tooltip="" />
                                    <x-select name="country_id" value="{{ old('country_id') }}" label="Country"
                                        optionName="name" valueName="id" class="select2" :arr="@\App\Models\Country::all()"
                                        validation="country_name" id="country" />
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
                                        validation="country_name" id="state" />
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
                                        id="city" />
                                    <div class="invalid-feedback">
                                        Please provide a valid city.
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mt-2">
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-primary"> @lang('admin/ui.create') </button>

                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

