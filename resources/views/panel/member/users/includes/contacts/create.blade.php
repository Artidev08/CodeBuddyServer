<style>
    .iti--inline-dropdown .iti__dropdown-content {
        z-index: 9 !important;
    }
</style>
<div class="modal fade" id="ContactModalCenter" role="dialog" aria-labelledby="contactModalCenterLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactModalCenterLabel"> @lang('admin/ui.addContact') </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('panel.admin.contacts.store') }}" method="post">
                    @csrf
                    <x-input name="request_with" placeholder="" type="hidden" tooltip="" regex=""
                        validation="" value="create" />
                    <x-input name="type" placeholder="" type="hidden" tooltip="" regex="" validation=""
                        value="{{ App\Models\User::class }}" />
                    <x-input name="type_id" placeholder="" type="hidden" tooltip="" regex="" validation=""
                        value="{{ @$user->id }}" />

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="form-row">
                                    <div class="form-group col-md-3" style="padding-left:22px !important">
                                            <x-label name="prefix" validation="common_name" tooltip="" />
                                            <select name="prefix" id="prefix" class="form-control">
                                                {{-- <option value="" disabled selected>@lang('admin/ui.select_prefix')</option> --}}
                                                @foreach (\App\Models\Contact::PREFIXES as $key => $prefix)
                                                    <option value="{{ $key }}">{{ $prefix }}</option>
                                                @endforeach
                                            </select>
                                    </div>
                                    <div class="col-md-5 {{ @$errors->has('first_name') ? 'has-error' : '' }}">
                                        <x-label name="first_name" validation="common_name" tooltip="" />
                                        <x-input name="first_name" id="first_name"
                                            placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.first_name') }}"
                                            type="text" tooltip="" regex="name" validation="common_name"
                                            value="" />
                                        <x-message name="first_name" :message="@$message" />
                                    </div>
                                    <div class="col-md-4 {{ @$errors->has('last_name') ? 'has-error' : '' }}">
                                        <x-label name="last_name" validation="common_name" tooltip="" />
                                        <x-input name="last_name" id="last_name"
                                            placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.last_name') }}"
                                            type="text" tooltip="" regex="name" validation="common_name"
                                            value=""  />
                                        <x-message name="last_name" :message="@$message" />
                                    </div>
                                </div>


                                <div class="form-group col-md-6 {{ @$errors->has('email') ? 'has-error' : '' }}">
                                    <x-label name="email" validation="common_email" tooltip="" />
                                    <x-input name="email" id="email"
                                        placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.email') }}"
                                        type="email" tooltip="" regex="email" validation="common_email"
                                        value="" />
                                    <x-message name="email" :message="@$message" />
                                </div>
                                <div class="form-group col-md-6 {{ @$errors->has('phone') ? 'has-error' : '' }}">
                                    <x-label name="contact_number" validation="common_phone_number" tooltip="" />
                                    {{-- <x-input name="phone"
                                        placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.phone_number') }}"
                                        id="phone" type="number" tooltip="" regex="phone_number"
                                        validation="common_phone_number" value="{{ old('phone') }}" />
                                    <x-message name="phone" :message="@$message" /> --}}
                                    <div class="input-group">
                                        <input type="hidden" id="contactCountryCodeInput" name="country_code"
                                            value="">
                                        <input style="width: 13.5rem !important;" type="tel" class="form-control"
                                            id="contactPhone" name="phone" value="{{ old('phone') }}">
                                    </div>

                                </div>

                            </div>

                        </div>
                        <div class="col-md-12 mx-auto">
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary"> @lang('admin/ui.create') </button>

                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
