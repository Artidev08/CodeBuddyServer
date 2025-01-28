<div class="modal fade" id="editContact" role="dialog" aria-labelledby="exampleModalCenterLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterLabel"> @lang('admin/ui.edit_contact') </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="editContactForm" method="post">
                    @csrf
                    <x-input name="request_with" placeholder="" type="hidden" tooltip="" regex=""
                        validation="" value="update" />
                    <x-input name="type" placeholder="" type="hidden" tooltip="" regex="" validation=""
                        value="{{ App\Models\User::class }}" />
                    <x-input name="type_id" id="edit_type_id" placeholder="" type="hidden" tooltip=""
                        regex="" validation="" value="" />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="form-group col-md-3">

                                    <label for="prefix"> @lang('admin/ui.prefix') <span class="text-red">*</span></label>

                                    @if (isset($contact))
                                        <select name="prefix" id="prefix" class="form-control">
                                            @foreach (\App\Models\Contact::PREFIXES as $key => $prefix)
                                                <option value="{{ $key }}"
                                                    {{ $contact->prefix == $key ? 'selected' : '' }}>
                                                    {{ $prefix }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select name="prefix" id="prefix" class="form-control">
                                            @foreach (\App\Models\Contact::PREFIXES as $key => $prefix)
                                                <option value="{{ $key }}">
                                                    {{ $prefix }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif


                                </div>
                                <div class="col-md-5">

                                    <x-label name="first_name" validation="common_name" tooltip="" />
                                    <x-input name="first_name" id="edit_first_name"
                                        placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.first_name') }}"
                                        type="text" tooltip="" regex="name" validation="common_name"
                                        value="{{ old('first_name') }}" />
                                    <x-message name="first_name" :message="@$message" />
                                </div>
                                <div class="col-md-4">

                                    <x-label name="last_name" validation="common_name" tooltip="" />
                                    <x-input name="last_name" id="edit_last_name"
                                        placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.last_name') }}"
                                        type="text" tooltip="" regex="name" validation="common_name"
                                        value="{{ old('last_name') }}" />
                                    <x-message name="last_name" :message="@$message" />
                                </div>

                                <div class="form-group col-md-6">

                                    <x-label name="email" validation="common_email" tooltip="" />
                                    <x-input name="email" id="edit_email"
                                        placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.email') }}"
                                        type="email" tooltip="" regex="email" validation="common_email"
                                        value="" />
                                    <x-message name="email" :message="@$message" />
                                </div>

                                <div class="form-group col-md-6">

                                    <x-label name="contact_number" validation="common_phone_number" tooltip="" />
                                    {{-- <x-input name="phone"
                                            placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.phone_number') }}"
                                            id="edit_phone" type="number" tooltip="" regex="phone_number"
                                            validation="common_phone_number" value="{{ old('phone') }}" />
                                        <x-message name="phone" :message="@$message" /> --}}
                                    <div class="input-group">
                                        <input type="hidden" id="editContactCountryCodeInput" name="country_code"
                                            value="">
                                        <input type="tel" style="width: 13.5rem !important;"class="form-control"
                                            id="editContactCountryCode" name="phone" value="">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mx-auto">
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
