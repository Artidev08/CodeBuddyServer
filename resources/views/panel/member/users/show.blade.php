@extends('layouts.main')
@section('title', @$user->getPrefix() . ' ' . __('admin/ui.user') . ' ' . __('admin/ui.profile'))
@section('content')

    @push('head')
        <link rel="stylesheet" href="{{ asset('panel/admin/plugins/select2/dist/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('panel/admin/plugins/mohithg-switchery/dist/switchery.min.css') }}">
        <style>
            .dt-button.dropdown-item.buttons-columnVisibility.active {
                background: #322d2d !important;
            }

            .center {
                position: absolute;
                left: 50%;
                transform: translate(-50%, -50%);
            }

            .footer-margin-l {
                margin-left: 16rem;
            }
            .footer-margin-r {
                margin-right: 1rem;
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-user bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ Str::limit(@$user->full_name, 20) }}</h5>
                            <span> @lang('admin/ui.user') @lang('admin/ui.profile') </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 d-sm-flex d-lg-block">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('panel.admin.dashboard.index') }}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('panel.admin.users.index') }}"> @lang('admin/ui.user') </a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page"> @lang('admin/ui.show') </li>
                            <li class="breadcrumb-item fw-600" aria-current="page"> @lang('admin/ui.profile') </li>
                        </ol>
                    </nav>

                </div>
            </div>
        </div>

        @include('panel.admin.include.message')

        <div class="row">
            <div class="col-lg-4 col-md-5">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="d-flex">
                                @if (getSetting('dac_activation') == 1)
                                    <div class="" style="position: relative">
                                        @if ($user->status == App\Models\User::STATUS_ACTIVE)
                                            <a href="{{ route('panel.admin.users.login-as', $user->id) }}"
                                                class="text-danger loginAsBtn" data-user_id="{{ $user->id }}"
                                                data-first_name="{{ $user->first_name }}">
                                                <span title="Login As User">
                                                    <i class="fa fa-right-to-bracket "></i>
                                                </span>
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    @if ($user->status == App\Models\User::STATUS_ACTIVE)
                                        <div class="" style="position: relative">
                                            <a href="{{ route('panel.admin.users.login-as', $user->id) }}"
                                                class="text-danger" data-user_id="{{ $user->id }}"
                                                data-first_name="{{ $user->first_name }}">
                                                <span title="Login As User">
                                                    <i class="fa fa-right-to-bracket "></i>
                                                </span>
                                            </a>
                                        </div>
                                    @endif
                                @endif
                                <div style="width: 150px; height: 150px; position: relative" class="mx-auto">
                                    <img src="{{ @$user && @$user->avatar ? @$user->avatar : asset('panel/admin/default/default-avatar.png') }}"
                                        class="rounded-circle" width="150"
                                        style="object-fit: cover; width: 150px; height: 150px" />
                                    <button class="btn btn-dark rounded-circle position-absolute"
                                        style="width: 30px; height: 30px; padding: 8px; line-height: 1; top: 0; right: 0"
                                        data-toggle="modal" data-target="#updateProfileImageModal"><i
                                            class="ik ik-camera"></i></button>
                                </div>
                                <div class="dropdown d-flex" style="margin-bottom: 130px;">
                                    <button style="background: transparent;border:none;" class="dropdown-toggle"
                                        type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false"><i class="ik ik-more-vertical"></i></button>
                                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                        @if (env('DEV_MODE') == 1)
                                            <a>
                                                <li class="dropdown-item text-dark fw-500">
                                                    @lang('admin/ui.send_credential')</li>
                                            </a>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <h5 class="mb-0 mt-3">
                                {{ Str::limit(@$user->full_name, 20 ?? '--') }}
                                @if (@$user->is_verified == 1)
                                    <strong class="mr-1"><i class="ik ik-check-circle"></i></strong>
                                @endif
                            </h5>
                            <span class="text-muted fw-600">@lang('admin/ui.role'):
                                {{ UserRole(@$user->id)->display_name ?? '--' }} | {{  @$user->getPrefix() ?? '' }}

                            </span>
                            @if (getSetting('wallet_activation') == 1)
                                <div class=" mt-2">
                                    <a class="btn btn-outline-light text-dark border"
                                        href="{{ route('panel.admin.wallet-logs.index', [secureToken($user->id)]) }}">
                                        <i class="fa fa-wallet pr-1"></i>@lang('admin/ui.wallet_statement')
                                        {{ format_price(@$user->wallet ?? '--') }}
                                    </a>
                                </div>
                            @endif
                            <a href="{{ route('panel.admin.users.edit', secureToken($user->id)) }}" class="btn btn-link">
                                <span title="Edit User"><i class="fa fa-edit"></i></span> @lang('admin/ui.edit')
                            </a>
                        </div>
                    </div>
                    <hr class="mb-0">
                    <div class="card-body">
                        <small class="text-muted d-block"> @lang('admin/ui.email_address') </small>
                        <div class="d-flex justify-content-between">
                            <h6 style="overflow-wrap: anywhere;"><span><i class="ik ik-mail mr-1"></i><a class="text-color-white" href="mailto:{{ @$user->email ?? '--' }}"
                                        id="copyemail">{{ @$user->email ?? '--' }}</a></span></h6>
                            <span class="text-copy" title="Copy" data-clipboard-target="#copyemail">
                                <i class="ik ik-copy"></i>
                            </span>
                        </div>
                        <small class="text-muted d-block pt-10"> @lang('admin/ui.phone_number') </small>
                        <div class="d-flex justify-content-between">
                            <h6><span><a class="text-color-white"
                                        href="tel:{{ @$user->country_code ?? '--' }} {{ @$user->phone ?? '--' }}"
                                        id="copyphone"><i class="ik ik-phone mr-1"></i>
                                        +{{ @$user->country_code ?? '--' }} {{ @$user->phone ?? '--' }}</a></span>
                            </h6>
                            <span class="text-copy" title="Copy" data-clipboard-target="#copyphone" tile>
                                <i class="ik ik-copy"></i>
                            </span>
                        </div>
                        @if ($user->userSubscription && $user->userSubscription->subscription)
                            <small class="text-muted d-block pt-10">@lang('admin/ui.user_subscription')</small>

                            <div class="d-flex justify-content-between">
                                <h6>
                                    <span>
                                        <a href="#" class="text-color-white">
                                            <!-- Replace '#' with actual subscription link if available -->
                                            <i class="fal fa-dollar-sign mr-1"></i>
                                            {{ @$user->userSubscription->subscription->name ?? 'N/A' }}
                                        </a><br>
                                        <span>
                                            <small
                                                class="text-muted d-block pt-10">{{ @$user->userSubscription->from_date ?? 'N/A' }}
                                                -
                                                {{ @$user->userSubscription->to_date ?? 'N/A' }}</small>

                                        </span>
                                    </span>
                                </h6>
                            </div>
                        @endif
                        <small class="text-muted d-block pt-10"> @lang('admin/ui.member_since')</small>
                        <h6>{{ @$user->formatted_created_at ?? '--' }}</h6>
                        <div>
                            @if (getSetting('dac_activation', @$setting) == 1)
                            <small class="text-muted d-block pt-10"> @lang('admin/ui.delegate_access_code') </small>

                            {{-- <div class="input-group mb-3">
                                <input id="password" type="password" autocomplete="off"
                                    class="form-control @error('password') is-invalid @enderror" minlength="4"
                                    name="password" value="{{ @$user->delegate_access }}" placeholder="Enter Password"
                                    required style="border: 0px">
                                <div class="input-group-append">
                                    <span class="input-group-text"
                                        style="background-color: white; margin--right:20px !important; border:0px;"><i
                                            class="ik ik-eye text-color-black" id="togglePassword"></i></span>
                                </div>
                            </div> --}}
                            <div class="input-group mb-3">
                                <input id="password" type="password" autocomplete="off"
                                    class="form-control @error('password') is-invalid @enderror" minlength="4"
                                    name="password" value="{{ @$user->delegate_access }}" placeholder="Enter Password"
                                    required style="border: 0px">
                                <div class="input-group-append">
                                    <span class="input-group-text"
                                        style="background-color: white; margin-right: 20px !important; border: 0px;">
                                        <i class="ik ik-eye text-color-black" id="togglePassword"></i>
                                    </span>
                                    <span class="input-group-text"
                                        style=" color:black; background-color: white;  margin-right: -10px !important; border: 0px; cursor: pointer;"
                                        title="Copy" data-clipboard-target="#password" id="copyPassword">
                                        <i class="ik ik-copy text-color-black"></i>
                                    </span>
                                </div>
                            </div>

                            @endif
                        </div>


                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-7">
                <div class="card">
                    <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                        @if (UserRole(@$user->id)->name != 'admin')
                            <li class="nav-item">
                                <a data-active="account-verfication"
                                    class="nav-link active-swicher @if ((request()->has('active') && request()->get('active') == 'account-verfication') || !request()->has('active')) active @endif"
                                    id="pills-note-tab" data-toggle="pill" href="#kyc-tab" role="tab"
                                    aria-controls="pills-note" aria-selected="false"> @lang('admin/ui.account_verification') </a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a data-active="password-tab"
                                class="nav-link active-swicher @if (
                                    (UserRole(@$user->id)->name == 'admin' && !request()->has('active')) ||
                                        (request()->has('active') && request()->get('active') == 'password-tab')) active @endif"
                                id="pills-password-tab" data-toggle="pill" href="#password-tab" role="tab"
                                aria-controls="pills-password" aria-selected="false"> @lang('admin/ui.change_password') </a>
                        </li>
                        <li class="nav-item">
                            <a data-active="lead-tab"
                                class="nav-link active-swicher @if (request()->has('active') && request()->get('active') == 'lead-tab') show active @endif"
                                data-url="{{ route('panel.admin.user-notes.index') }}" id="pills-lead-tab"
                                data-toggle="pill" href="#lead-tab" role="tab" aria-controls="pills-lead"
                                aria-selected="false"> @lang('admin/ui.notes') </a>
                        </li>
                        <li class="nav-item">
                            <a data-active="contact-tab"
                                class="nav-link active-swicher @if (request()->has('active') && request()->get('active') == 'contact-tab') show active @endif"
                                data-url="{{ route('panel.admin.contacts.index') }}" id="pills-contact-tab"
                                data-toggle="pill" href="#contact-tab" role="tab" aria-controls="pills-contact"
                                aria-selected="false"> @lang('admin/ui.contacts') </a>

                        </li>
                        @if (auth()->user()->isAbleTo('view_addresses'))
                            <li class="nav-item">
                                <a data-active="address-tab"
                                    class="nav-link active-swicher @if (request()->has('active') && request()->get('active') == 'address-tab') show active @endif"
                                    data-url="{{ route('panel.admin.addresses.index') }}" id="pills-address-tab"
                                    data-toggle="pill" href="#address-tab" role="tab" aria-controls="pills-address"
                                    aria-selected="false"> @lang('admin/ui.addresses') </a>

                            </li>
                        @endif
                        @if (auth()->user()->isAbleTo('view_banks'))
                            <li class="nav-item">
                                <a data-active="bank-details-tab"
                                    class="nav-link active-swicher @if (request()->has('active') && request()->get('active') == 'bank-details-tab') show active @endif"
                                    data-url="{{ route('panel.admin.payout-details.index') }}"
                                    id="pills-bank-details-tab" data-toggle="pill" href="#bank-details-tab"
                                    role="tab" aria-controls="pills-bank-details" aria-selected="false">
                                    @lang('admin/ui.bank') </a>
                            </li>
                        @endif
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        @if (UserRole(@$user->id)->name != 'admin')
                            <div class="tab-pane fade  @if ((request()->has('active') && request()->get('active') == 'account-verfication') || !request()->has('active')) show active @endif"
                                id="kyc-tab" role="tabadmin" aria-labelledby="pills-note-tab">
                                @include('panel.admin.users.includes.kyc')
                            </div>
                        @endif
                        <div class="tab-pane fade @if (
                            (UserRole(@$user->id)->name == 'admin' && !request()->has('active')) ||
                                (request()->has('active') && request()->get('active') == 'password-tab')) show active @endif" id="password-tab"
                            role="tabadmin" aria-labelledby="pills-setting-tab" class="ajax-password-">
                            @include('panel.admin.users.includes.change-password')
                        </div>

                        <div class="tab-pane fade @if (request()->has('active') && request()->get('active') == 'lead-tab') show active @endif" id="lead-tab"
                            role="tabadmin" aria-labelledby="pills-setting-tab">
                            <div class="card-header p-3 d-flex justify-content-between align-items-center">
                                <h3> @lang('admin/ui.notes') </h3>
                                <a href="javascript:void(0);" class="btn btn-sm btn-outline-primary mr-2"
                                    data-toggle="modal" data-target="#exampleModalCenter" title="Add New Note"> <i
                                        class="fa fa-plus " aria-hidden="true"></i> @lang('admin/ui.add')
                                </a>
                            </div>
                            <div class="card-body ajax-lead-tab" style="overflow: auto" id="">
                                @include('panel.admin.users.includes.notes.index')
                            </div>
                        </div>

                        <div class="tab-pane fade @if (request()->has('active') && request()->get('active') == 'contact-tab') show active @endif" id="contact-tab"
                            role="tabadmin" aria-labelledby="pills-setting-tab">
                            <div class="card-header p-3 d-flex justify-content-between align-items-center">
                                <h3> @lang('admin/ui.contacts') </h3>
                                @if (auth()->user()->isAbleTo('add_contact'))
                                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-primary"
                                        data-toggle="modal" data-target="#ContactModalCenter" title="Add New Contact"><i
                                            class="fa fa-plus" aria-hidden="true"></i> @lang('admin/ui.add')</a>
                                @endif
                            </div>
                            <div class="card-body ajax-contact-tab" id="">
                                @include('panel.admin.users.includes.contacts.index')
                            </div>
                        </div>
                        <div class="tab-pane fade @if (request()->has('active') && request()->get('active') == 'address-tab') show active @endif" id="address-tab"
                            role="tabadmin" aria-labelledby="pills-setting-tab">
                            <div class="card-header p-3 d-flex justify-content-between align-items-center">
                                <h3> @lang('admin/ui.addresses') </h3>
                                @if (auth()->user()->isAbleTo('add_address'))
                                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-primary"
                                        data-toggle="modal" data-target="#addressModalCenter" title="Add New Address"><i
                                            class="fa fa-plus" aria-hidden="true"></i>@lang('admin/ui.add')</a>
                                @endif
                            </div>
                            @if (auth()->user()->isAbleTo('control_address'))
                                <div class="card-body ajax-address-tab" id="">
                                    @include('panel.admin.users.includes.addresses.index')
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade @if (request()->has('active') && request()->get('active') == 'bank-details-tab') show active @endif"
                            id="bank-details-tab" role="tabadmin" aria-labelledby="pills-setting-tab">
                            <div class="card-header p-3 d-flex justify-content-between align-items-center">
                                <h3> @lang('admin/ui.bank_details') </h3>
                                @if (auth()->user()->isAbleTo('add_bank'))
                                    <a href="javascript:void(0);"
                                        class="btn btn-sm btn-outline-primary addPayoutDetailBtn" data-toggle="modal"
                                        data-target="#BankDetailsModalCenter" title="Add New Bank Detail"><i
                                            class="fa fa-plus" aria-hidden="true"></i>@lang('admin/ui.add')</a>
                                @endif
                            </div>
                            @if (auth()->user()->isAbleTo('control_bank'))
                                <div class="card-body ajax-bank-details-tab" id="">
                                    @include('panel.admin.users.includes.bank-details.index')
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- tab end --}}

            </div>

        </div>

    </div>
    @include('panel/admin/users/includes/profile-modal')
    @include('panel.admin.users.includes.modal.delegate-access')
    @include('panel.admin.users.includes.contacts.create')
    @include('panel.admin.users.includes.contacts.edit')
    @include('panel.admin.users.includes.notes.create')
    @include('panel.admin.users.includes.notes.edit')
    @include('panel.admin.users.includes.addresses.create')
    @include('panel.admin.users.includes.addresses.edit')
    @include('panel.admin.users.includes.bank-details.create')
    @include('panel.admin.users.includes.bank-details.edit')


@endsection

@push('script')
    <script src="{{ asset('panel/admin/plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>
    <script src="{{ asset('panel/admin/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    <script src="https://cdn.jsdelivr.net/clipboard.js/1.5.12/clipboard.min.js"></script>
    <script src="{{ asset('panel/admin/plugins/datedropper/croppie.min.js') }}"></script>

    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            var response = postData(method, route, 'json', data, null, null);
            if (typeof(response) != "undefined" && response !== null && response.status == "success") {}
        });
    </script>
    {{-- END AJAX FORM INIT --}}

    <script>
        $(document).on('click', '.edit-contact', function() {
                var contact = $(this).data('contact');
                var phone = $(this).data('phone');
                $('#edit_type_id').val(contact.type_id);
                $('#edit_first_name').val(contact.first_name);
                $('#edit_last_name').val(contact.last_name);
                $('#edit_job_title').val(contact.job_title);
                $('#edit_job_title').val(contact.job_title);
                $('#edit_email').val(contact.email);
                $('#edit_prefix').val(contact.prefix);
                $('#editContactCountryCode').val(phone);

                var url = "{{ url('/admin/contacts/update') }}" + '/' + contact.id;
                $('#editContactForm').attr('action', url);
                $('#editContact').modal('show');

        });
    </script>

<script>
    $(function() {
        new ClipboardJS('#copyPassword');

        $('#copyPassword').on('click', function() {

        });
    });
</script>


    <script>
        $(document).on('click', '.editAddress', function() {
            var address = $(this).data('id');
            var details = address.details;
            var phone = $(this).data('phone');
            // alert(details.phone);
            if (details.type == 0) {
                $('.homeInput').attr("checked", "checked");
            } else {
                $('.officeInput').attr("checked", "checked");
            }

            $('#editName').val(details.name);
            $('#id').val(address.id);
            $('#addressId').val(address.id);
            $('#user_id').val(address.user_id);
            $('#type').val(address.type);
            $('#editAddressCountryCode').val(details.phone);
            $('#editAddress').val(details.address_1);
            $('#editAddress_2').val(details.address_2);
            $('#pincode_id').val(details.pincode_id);
            $('#countryEdit').val(details.country_id).change();
            getStateAsync(details.country_id).then(function(data) {
                $('#stateEdit').val(details.state_id).change();
                $('#stateEdit').trigger('change');
            });
            getCityAsync(details.state_id).then(function(data) {
                $('#cityEdit').val(details.city).change();
                $('#cityEdit').trigger('change');
            });
            $('#editAddressModal').modal('show');
        });
    </script>
    {{-- START JS HELPERS INIT --}}
    <script>
        $(document).ready(function() {
            var table = $('.data_table').DataTable({
                responsive: true,
                fixedColumns: true,
                fixedHeader: true,
                scrollX: false,
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
                dom: "<'row'<'col-sm-2'l><'col-sm-7 text-center'B><'col-sm-3'f>>tipr",
                buttons: [{
                    extend: 'excel',
                    className: 'btn-sm btn-success',
                    header: true,
                    footer: true,
                    exportOptions: {
                        columns: ':visible',
                    }
                }, ]

            });
        });

        document.getElementById('avatar').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            $('#avatar_file').removeClass('d-none');
            document.getElementById('avatar_file').src = src
        }

        function updateCoords(im, obj) {
            $('#x').val(obj.x1);
            $('#y').val(obj.y1);
            $('#w').val(obj.width);
            $('#h').val(obj.height);
        }

        function getStates(countryId = 101) {
            $.ajax({
                url: '{{ route('world.get-states') }}',
                method: 'GET',
                data: {
                    country_id: countryId
                },
                success: function(res) {
                    $('#state').html(res).css('width', '100%').select2();
                }
            })
        }

        function getCities(stateId = 101) {
            $.ajax({
                url: '{{ route('world.get-cities') }}',
                method: 'GET',
                data: {
                    state_id: stateId
                },
                success: function(res) {
                    $('#city').html(res).css('width', '100%').select2();
                }
            })
        }

        // Country, City, State Code
        $('#state, #country, #city').css('width', '100%').select2();

        getStates(101);
        $('#country').on('change', function(e) {
            getStates($(this).val());
        })

        $('#state').on('change', function(e) {
            getCities($(this).val());
        })

        // this functionality work in edit page
        function getStateAsync(countryId) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '{{ route('world.get-states') }}',
                    method: 'GET',
                    data: {
                        country_id: countryId
                    },
                    success: function(data) {
                        $('#state').html(data);
                        $('#stateEdit').html(data);
                        $('.state').html(data);
                        resolve(data)
                    },
                    error: function(error) {
                        reject(error)
                    },
                })
            })
        }

        function getCityAsync(stateId) {
            if (stateId != "") {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: '{{ route('world.get-cities') }}',
                        method: 'GET',
                        data: {
                            state_id: stateId
                        },
                        success: function(data) {
                            $('#city').html(data);
                            $('#cityEdit').html(data);
                            $('.city').html(data);
                            resolve(data)
                        },
                        error: function(error) {
                            reject(error)
                        },
                    })
                })
            }
        }

        $(document).ready(function() {
            $('.accept').on('click', function() {
                $('#status').val(1)
            });
            $('.reject').on('click', function() {
                $('#status').val(2)
            });
            $('.reset').on('click', function() {
                $('#status').val(0)
            });
            var country = "{{ $user->country_id }}";
            var state = "{{ $user->state_id }}";
            var city = "{{ $user->city_id }}";

            if (state) {
                getStateAsync(country).then(function(data) {
                    $('#state').val(state).change();
                    $('#state').trigger('change');
                });
            }
            if (city) {
                $('#state').on('change', function() {
                    if (state == $(this).val()) {
                        getCityAsync(state).then(function(data) {
                            $('#city').val(city).change();
                            $('#city').trigger('change');
                        });
                    }
                });
            }
        });
        $(function() {
            new Clipboard('.text-copy');
        });
        $(document).on('click', '.edit-note', function() {
                var data = $(this).data('item');
                $('#note-type_id').val(data.type_id);
                $('#note-title').val(data.title);
                $('#note-description').val(data.description);
                // alert(data.category_id);
                $('#category_id_edit').val(data.category_id).trigger('change');
                var url = "{{ url('/admin/user-notes/update') }}" + '/' + data.id;
                $('#editNoteForm').attr('action', url);
                $('#editModalCenter').modal('show');

        });



        $(document).on('click', '.addPayoutDetailBtn', function() {
            $('#bankDetailsModalCenter').modal('show');
        });
        $(document).on('click', '.editPayoutDetailBtn', function() {
            let record = $(this).data('row');
            console.log(record);
            let payload = $(this).data('payload');
            console.log(payload);
            if (record.type == "Saving")
                $('#editsaving').prop('checked', true);
            else
                $('#editcurrent').prop('checked', true);

            $('#payoutdetailId').val(record.id);
            $('#editAcountHolderName').val(payload.account_holder_name);
            $('#editAccountNo').val(payload.account_no);
            $('#editIfscCode').val(payload.ifsc_code);
            $('#editBranch').val(payload.branch);
            $('#editbank option[value="' + payload.bank_name + '"]').prop('selected', true);
            $('#editBankDetailsModal').modal('show');
        });

        $('.active-swicher').on('click', function() {
            var active = $(this).attr('data-active');
            var url = $(this).attr('data-url');
            updateURL('search', '');
            updateURL('active', active);
            fetchData(url, active);
        });
    </script>
    {{-- END JS HELPERS INIT --}}

    {{-- START DELEGATE ACCESS BUTTON INIT --}}
    <script>
        $(document).on('click', '.loginAsBtn', function(e) {
            e.preventDefault();
            let user_id = $(this).data('user_id');
            let first_name = $(this).data('first_name');
            $('.delegateUserId').val(user_id);
            $('.delegateUserName').html(first_name);
            $('#DelegateAccessModel').modal('show');
        });
    </script>
    {{-- END DELEGATE ACCESS BUTTON INIT --}}

    {{-- START DELEGATE ACCESS CODE HIDE SHOW INIT --}}
    <script>
        $('#togglePassword').click(function() {
            var input = $('#password');
            var icon = $(this);

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                icon.removeClass('ik-eye').addClass('ik-eye-off');
            } else {
                input.attr('type', 'password');
                icon.removeClass('ik-eye-off').addClass('ik-eye');
            }
        });
    </script>
    {{-- END DELEGATE ACCESS CODE HIDE SHOW INIT --}}

    {{-- START PROFILE IMAGE CROPPER --}}
    <script>
        const avatar = document.getElementById('avatar');
        const imagePreview = document.getElementById('imagePreview');
        const croppedImageDataInput = document.getElementById('croppedImageData');
        const croppieContainer = document.querySelector('.demo');

        let croppieInstance = null;

        // When the input field for selecting an image changes
        avatar.onchange = evt => {
            const [file] = avatar.files;
            if (file) {
                imagePreview.src = URL.createObjectURL(file);
                croppieInstance = new Croppie(croppieContainer, {
                    enableExif: true,
                    viewport: {
                        width: 200,
                        height: 200,
                        type: 'circle'
                    },
                    boundary: {
                        width: 300,
                        height: 300
                    }
                });

                // Bind the selected image to Croppie
                croppieInstance.bind({
                    url: URL.createObjectURL(file),
                });
            }
        };

        // Capture cropped image data when the form is submitted
        document.querySelector('#updateProfileImageModal').onsubmit = () => {
            if (croppieInstance) {
                croppieInstance.result('base64').then(function(result) {
                    // Set the cropped image data to the hidden input
                    croppedImageDataInput.value = result;
                });
            }
        };
    </script>
    {{-- END PROFILE IMAGE CROPPER --}}

    {{-- START PREVIEW MODAL INIT --}}
    <script>
        $(document).ready(function() {
            $('.open-modal').on('click', function() {
                var documentSrc = $(this).attr('href');

                $('#previewImageContainer').html(
                    `<img src="${documentSrc}" class="img-fluid" alt="File Preview">`);
            });

            $('#filePreviewModal').modal({
                show: false
            });
        });
    </script>
    {{-- END PREVIEW MODAL INIT --}}

    {{-- COUNTRYCODE SELECTOR INIT --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.querySelector("#contactPhone");
            const contactCountryCodeInput = document.querySelector("#contactCountryCodeInput");

            const iti = window.intlTelInput(input, {
                initialCountry: "auto",
                separateDialCode: true,
                geoIpLookup: callback => {
                    fetch("https://ipapi.co/json")
                        .then(res => res.json())
                        .then(data => callback(data.country_code))
                        .catch(() => callback("us"));
                },
                utilsScript: "{{ asset('panel/admin/plugins/country-code/utils.js') }}",

            });
            window.iti = iti;

            const updateCountryCode = () => {
                const selectedCountryData = iti.getSelectedCountryData();
                contactCountryCodeInput.value = selectedCountryData.dialCode;
            };

            input.addEventListener("countrychange", updateCountryCode);
            input.addEventListener("keyup", updateCountryCode);
            input.addEventListener("change", updateCountryCode);

            setTimeout(() => {
                const event = new Event('countrychange');
                input.dispatchEvent(event);
            }, 300);
        });
    </script>
    {{-- END COUNTRYCODE SELECTOR INIT --}}

    {{-- Edit Contact COUNTRYCODE SELECTOR INIT --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.querySelector("#editContactCountryCode");
            const editContactCountryCodeInput = document.querySelector("#editContactCountryCodeInput");

            const iti = window.intlTelInput(input, {
                initialCountry: "auto",
                separateDialCode: true,
                geoIpLookup: callback => {
                    fetch("https://ipapi.co/json")
                        .then(res => res.json())
                        .then(data => callback(data.country_code))
                        .catch(() => callback("us"));
                },
                utilsScript: "{{ asset('panel/admin/plugins/country-code/utils.js') }}",

            });
            window.iti = iti;

            const updateCountryCode = () => {
                const selectedCountryData = iti.getSelectedCountryData();
                editContactCountryCodeInput.value = selectedCountryData.dialCode;
            };

            input.addEventListener("countrychange", updateCountryCode);
            input.addEventListener("keyup", updateCountryCode);
            input.addEventListener("change", updateCountryCode);

            setTimeout(() => {
                const event = new Event('countrychange');
                input.dispatchEvent(event);
            }, 300);
        });
    </script>
    {{-- END COUNTRYCODE SELECTOR INIT --}}


    {{-- Address COUNTRYCODE SELECTOR INIT --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.querySelector("#addressPhone");
            const addressCountryCodeInput = document.querySelector("#addressCountryCodeInput");

            const iti = window.intlTelInput(input, {
                initialCountry: "auto",
                separateDialCode: true,
                geoIpLookup: callback => {
                    fetch("https://ipapi.co/json")
                        .then(res => res.json())
                        .then(data => callback(data.country_code))
                        .catch(() => callback("us"));
                },
                utilsScript: "{{ asset('panel/admin/plugins/country-code/utils.js') }}",

            });
            window.iti = iti;

            const updateCountryCode = () => {
                const selectedCountryData = iti.getSelectedCountryData();
                addressCountryCodeInput.value = selectedCountryData.dialCode;
            };

            input.addEventListener("countrychange", updateCountryCode);
            input.addEventListener("keyup", updateCountryCode);
            input.addEventListener("change", updateCountryCode);

            setTimeout(() => {
                const event = new Event('countrychange');
                input.dispatchEvent(event);
            }, 300);
        });
    </script>
    {{-- END COUNTRYCODE SELECTOR INIT --}}

    {{-- Edit Address COUNTRYCODE SELECTOR INIT --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.querySelector("#editAddressCountryCode");
            const editAddressCountryCodeInput = document.querySelector("#editAddressCountryCodeInput");

            const iti = window.intlTelInput(input, {
                initialCountry: "auto",
                separateDialCode: true,
                geoIpLookup: callback => {
                    fetch("https://ipapi.co/json")
                        .then(res => res.json())
                        .then(data => callback(data.country_code))
                        .catch(() => callback("us"));
                },
                utilsScript: "{{ asset('panel/admin/plugins/country-code/utils.js') }}",

            });
            window.iti = iti;

            const updateCountryCode = () => {
                const selectedCountryData = iti.getSelectedCountryData();
                editAddressCountryCodeInput.value = selectedCountryData.dialCode;
            };

            input.addEventListener("countrychange", updateCountryCode);
            input.addEventListener("keyup", updateCountryCode);
            input.addEventListener("change", updateCountryCode);

            setTimeout(() => {
                const event = new Event('countrychange');
                input.dispatchEvent(event);
            }, 300);
        });
    </script>
    {{-- END COUNTRYCODE SELECTOR INIT --}}
@endpush
