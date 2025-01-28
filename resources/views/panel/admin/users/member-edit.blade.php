@extends('layouts.main')
@section('title', @$user->getPrefix() . ' ' . __('admin/ui.edit') . (isset($label) ? ' ' . $label : ''))
@section('content')

    @php
        @$breadcrumb_arr = [
            ['name' => $label, 'url' => route('panel.admin.users.index'), 'class' => '--'],
            ['name' => @$user->getPrefix(), 'url' => route('panel.admin.users.index'), 'class' => '--'],
            ['name' => __('admin/ui.edit'), 'url' => route('panel.admin.users.index'), 'class' => 'active'],
        ];
        $permissions = [
            [
                'module' => 'Manage Registers',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_registers'],
                    ['label' => 'Add', 'name' => 'add_register'],
                    ['label' => 'Edit', 'name' => 'edit_register'],
                    ['label' => 'Delete', 'name' => 'delete_register'],
                ],
            ],
            [
                'module' => 'Manage Contents',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_contents'],
                    ['label' => 'Add', 'name' => 'add_content'],
                    ['label' => 'Edit', 'name' => 'edit_content'],
                    ['label' => 'Delete', 'name' => 'delete_content'],
                ],
            ],
            [
                'module' => 'Content Category',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_content_categories'],
                    ['label' => 'Add', 'name' => 'add_content_category'],
                    ['label' => 'Edit', 'name' => 'edit_content_category'],
                    ['label' => 'Delete', 'name' => 'delete_content_category'],
                    ['label' => 'Enable Disable', 'name' => 'enable_disable_content_category'],
                ],
            ],
            [
                'module' => 'Occasion',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_occasions'],
                    ['label' => 'Add', 'name' => 'add_occasion'],
                    ['label' => 'Edit', 'name' => 'edit_occasion'],
                    ['label' => 'Delete', 'name' => 'delete_occasion'],
                    ['label' => 'Enable Disable', 'name' => 'enable_disable_occasion'],
                    ['label' => 'Show', 'name' => 'show_occasion'],
                ],
            ],
            [
                'module' => 'Event',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_events'],
                    ['label' => 'Add', 'name' => 'add_event'],
                    ['label' => 'Edit', 'name' => 'edit_event'],
                    ['label' => 'Delete', 'name' => 'delete_event'],
                    ['label' => 'Enable Disable', 'name' => 'enable_disable_event'],
                ],
            ],
            [
                'module' => 'Age Group',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_age_groups'],
                    ['label' => 'Add', 'name' => 'add_age_group'],
                    ['label' => 'Edit', 'name' => 'edit_age_group'],
                    ['label' => 'Delete', 'name' => 'delete_age_group'],
                    ['label' => 'Show', 'name' => 'show_age_group'],
                    ['label' => 'Enable Disable', 'name' => 'enable_disable_age_group'],
                ],
            ],
            [
                'module' => 'Badge',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_badges'],
                    ['label' => 'Add', 'name' => 'add_badge'],
                    ['label' => 'Edit', 'name' => 'edit_badge'],
                    ['label' => 'Delete', 'name' => 'delete_badge'],
                ],
            ],
            [
                'module' => 'Relation',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_relations'],
                    ['label' => 'Add', 'name' => 'add_relation'],
                    ['label' => 'Edit', 'name' => 'edit_relation'],
                    ['label' => 'Delete', 'name' => 'delete_relation'],
                    ['label' => 'Show', 'name' => 'show_relation'],
                    ['label' => 'Enable Disable', 'name' => 'enable_disable_relation'],

                ],
            ],
            [
                'module' => 'Gender Specificity',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_gender_specificities'],
                    ['label' => 'Add', 'name' => 'add_gender_specificity'],
                    ['label' => 'Edit', 'name' => 'edit_gender_specificity'],
                    ['label' => 'Delete', 'name' => 'delete_gender_specificity'],
                    ['label' => 'Enable Disable', 'name' => 'enable_disable_gender_specificity'],

                ],
            ],
            [
                'module' => 'Language',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_languages'],
                    ['label' => 'Add', 'name' => 'add_language'],
                    ['label' => 'Edit', 'name' => 'edit_language'],
                    ['label' => 'Delete', 'name' => 'delete_language'],
                    ['label' => 'Enable Disable', 'name' => 'enable_disable_language'],
                    ['label' => 'Show', 'name' => 'show_language'],

                ],
            ],
            [
                'module' => 'Media Type',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_media_types'],
                    ['label' => 'Add', 'name' => 'add_media_type'],
                    ['label' => 'Edit', 'name' => 'edit_media_type'],
                    ['label' => 'Delete', 'name' => 'delete_media_type'],
                ],
            ],
            [
                'module' => 'Sentiment',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_sentiments'],
                    ['label' => 'Add', 'name' => 'add_sentiment'],
                    ['label' => 'Edit', 'name' => 'edit_sentiment'],
                    ['label' => 'Delete', 'name' => 'delete_sentiment'],
                    ['label' => 'Enable Disable', 'name' => 'enable_disable_sentiment'],
                    ['label' => 'Show', 'name' => 'show_sentiment'],
                ],
            ],
            [
                'module' => 'Content Length',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_content_lengths'],
                    ['label' => 'Add', 'name' => 'add_content_length'],
                    ['label' => 'Edit', 'name' => 'edit_content_length'],
                    ['label' => 'Delete', 'name' => 'delete_content_length'],
                    ['label' => 'Enable Disable', 'name' => 'enable_disable_content_length'],
                    ['label' => 'Show', 'name' => 'show_content_length'],
                ],
            ],
            [
                'module' => 'Landing Page',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_landing_page'],
                    ['label' => 'Add', 'name' => 'add_landing_page'],
                    ['label' => 'Edit', 'name' => 'edit_landing_page'],
                    ['label' => 'Edit', 'name' => 'show_landing_page'],
                    ['label' => 'Delete', 'name' => 'delete_landing_page'],
                    ['label' => 'Bulk Action', 'name' => 'bulk_action_landing_page'],
                ],
            ],
        ];
    @endphp
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('admin/plugins/select2/dist/css/select2.min.css') }}">
        <link rel="stylesheet"
            href="{{ asset('admin/plugins/tempusdominus-bootstrap-4/build/css/tempusdominus-bootstrap-4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/plugins/jquery-minicolors/jquery.minicolors.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/plugins/datedropper/datedropper.min.css') }}">
        <link rel="stylesheet" href="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.css') }}">

        <style>
            .modal-loading-css {
                min-height: 274px;
                justify-content: center;
                display: flex;
                align-items: center;
            }

            .iti--inline-dropdown .iti__dropdown-content {
                z-index: 9 !important;
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-user-plus bg-blue"></i>
                        <div class="d-inline">
                            @if ($user->hasRole('admin'))
                                <h5> @lang('admin/ui.edit') {{ $label }}</h5>
                                <span> @lang('admin/ui.update_record') {{ $label }}</span>
                            @else
                                <h5> @lang('admin/ui.edit') @lang('admin/ui.user') </h5>
                                <span> @lang('admin/ui.admin_subheading') </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb')

                </div>
            </div>
        </div>
        <form class="forms-sample " method="POST" action="{{ route('panel.admin.users.update', $user->id) }}">
            <div class="row">
                <!-- start message area-->
                @include('panel.admin.include.message')
                <!-- end message area-->
                @csrf

                <x-input name="id" placeholder="" type="hidden" tooltip="" regex="" validation=""
                    value="{{ $user->id ?? '' }}" />
                <x-input name="request_with" placeholder="" type="hidden" tooltip="" regex="" validation=""
                    value="update" />

                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3> @lang('admin/ui.personal_details') </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <x-label name="first_name" validation="common_name" tooltip="add_user_first_name" />

                                        <x-input name="first_name"
                                            placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.first_name') }}"
                                            type="text" tooltip="add_user_first_name" regex="name"
                                            validation="common_name" value="{{ @$user->first_name }}" />
                                        <x-message name="first_name" :message="@$message" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <x-label name="last_name" validation="common_name" tooltip="add_user_last_name" />
                                        <x-input name="last_name"
                                            placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.last_name') }}"
                                            type="text" tooltip="add_user_last_name" regex="name"
                                            validation="common_name" value="{{ $user->last_name }}" />
                                        <x-message name="last_name" :message="@$message" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <x-label name="email" validation="common_email" tooltip="add_user_email" />
                                        <x-input name="email"
                                            placeholder="{{ __('admin/ui.enter') . ' ' . __('admin/ui.email') }}"
                                            type="email" tooltip="add_user_email" regex="email" validation="common_email"
                                            value="{{ $user->email }}" />
                                        <x-message name="email" :message="@$message" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <x-label name="contact_number" validation="common_phone_number"
                                            tooltip="add_user_phone" />
                                        {{-- <x-input name="phone"
                                            placeholder="{{ __('admin/ui.enter') . __('admin/ui.phone_number') }}"
                                            id="phone" type="number" tooltip="add_user_phone" regex="phone_number"
                                            validation="common_phone_number" value="{{ $user->phone }}" />
                                        <x-message name="phone" :message="@$message" /> --}}
                                        <div class="input-group">
                                            <input type="hidden" id="countryCodeInput" name="country_code" value="">
                                            <input type="tel" class="form-control" id="phone" name="phone"
                                                value="{{ $user->fullPhone() }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <x-label name="dob" validation="admin_dob" tooltip="add_user_dob" />
                                        <x-date regex="dob" max="{{ now()->format('Y-m-d') }}" validation="admin_dob"
                                            type="date" value="{{ $user->dob }}" name="dob" id="dob"
                                            placeholder="Select your date" />
                                        <x-message name="dob" :message="@$message" />
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    @php
                                        $gender_arr = ['Male', 'Female'];
                                        $selectedOption = old('gender', $user->gender);
                                    @endphp
                                    <div class="form-group">
                                        <x-label name="gender" validation="common_nam                                e"
                                            tooltip="add_user_gender" />
                                        <x-radio name="gender" type="radio" valueName="id"
                                            value="{{ $user->gender }}" :arr="@$gender_arr" :selected="$selectedOption" />
                                        <x-message name="gender" :message="@$message" />
                                    </div>
                                </div>
                                @php
                                    $ai_enabled_arr = ['is_ai_enabled'];
                                @endphp
                                <div class="col-md-12 mt-2">
                                    <div class="form-group {{ @$errors->has('is_ai_enabled') ? 'has-error' : '' }}">
                                        <x-checkbox name="is_ai_enabled" class="js-switch switch-input"
                                            value="{{ $user->is_ai_enabled }}" type="checkbox" tooltip=""
                                            validation="" id="is_ai_enabled" :arr="@$ai_enabled_arr" />
                                        <x-label name="/" validation="" tooltip="is_ai_enabled" class="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header">
                            <h3>AI Context</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="model">Model <span class="text-danger">*</span></label>
                                        <a data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.add_item_department')"><i
                                                class="ik ik-help-circle text-muted ml-1"></i></a>
                                        <select required name="ai_payload[model]" id="model" data-flag="0"
                                            class="form-control select2 model">
                                            @foreach (getCategoriesByCode('ModelCategories') as $model)
                                                <option value="{{ $model->name }}"
                                                    {{ @$user->ai_payload['model'] == $model->name ? 'Selected' : '' }}>
                                                    {{ $model->name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="task" class="control-label">Prompt<span
                                                class="text-danger">*</span></label><a data-toggle="tooltip"
                                            href="javascript:void(0);" title="@lang('admin/tooltip.add_prompt_task')"><i
                                                class="ik ik-help-circle text-muted ml-1"></i></a>
                                        <div class="alert alert-info">
                                            <div class="form-group">
                                                <label for="">You can put these variables under prompt:</label><br>
                                                @foreach (getCriteriaVariables() as $key => $item)
                                                    {{ @$key }}@if (!@$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>

                                        <textarea rows="12" class="form-control" name="ai_payload[prompt]" required id="ai_payload"
                                            placeholder="Enter Prompt">{{ $user->ai_payload['prompt'] ?? old('prompt[prompt]') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                @if (UserRole($user->id)->display_name != 'Admin')
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h3 class="mb-1 mt-1"> @lang('Access Permissions:')</h3>
                                {{-- <div>
                                    <select class="form-control select2" name="userPermission">
                                        <option value="" readonly>Select Permission Group</option>
                                        @foreach ($userPermissions as $key => $userPermission)
                                            <option value="{{ $key }}"
                                                @if ($user->permissions != null && isset($user->permissions['key']) && $user->permissions['key'] == $key) selected @endif>
                                                {{ $userPermission['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                            </div>
                            <div class="loading-icon d-none">
                                <div class="modal-loading-css">
                                    <div class="text-center">
                                        <strong><i class="ik ik-loader fa-spin"></i></strong>
                                        <h6>Loading...</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body allPermission">
                                <div class="mb-2">
                                    <label class="custom-control custom-checkbox text-muted mb-0" style="width:90px;">
                                        <input type="checkbox" class="custom-control-input" id="select_all"
                                            name="permissions[all_item]" @if (
                                                $user->permissions &&
                                                    isset($user->permissions['permissions']) &&
                                                    in_array('all_item', $user->permissions['permissions'])) checked @endif>
                                        <span class="custom-control-label fw-700 ">Select All</span>
                                    </label>
                                </div>
                                <div class="row">
                                    @foreach ($permissions as $permission)
                                        <div class="col-md-2 mb-3">
                                            <label class="fw-700 text-muted">{{ $permission['module'] }}</label>
                                            <div id="resCheked_{{ $permission['module'] }}">
                                                @foreach ($permission['childrens'] as $child)
                                                    <label class="custom-control custom-checkbox text-muted mb-0">
                                                        <input type="checkbox" class="custom-control-input checkbox-item"
                                                            name="permissions[{{ $child['name'] }}]"
                                                            value="{{ $child['name'] }}"
                                                            @if (
                                                                $user->permissions &&
                                                                    isset($user->permissions['permissions']) &&
                                                                    in_array($child['name'], $user->permissions['permissions'])) checked @endif>
                                                        <span
                                                            class="custom-control-label fw-700">{{ $child['label'] }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <input type="hidden" id="role" value="{{ request()->get('role') ?? '--' }}">
            <button type="submit" class="btn btn-primary floating-btn ajax-btn"> @lang('admin/ui.save_update')
            </button>
        </form>
    </div>

@endsection

@push('script')
    <script src="{{ asset('admin/js/get-role.js') }}"></script>
    <script src="{{ asset('admin/plugins/moment/moment.js') }}"></script>
    <script src="{{ asset('admin/plugins/tempusdominus-bootstrap-4/build/js/tempusdominus-bootstrap-4.min.js') }}">
    </script>
    <script src="{{ asset('admin/plugins/jquery-minicolors/jquery.minicolors.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/datedropper/datedropper.min.js') }}"></script>
    <script src="{{ asset('admin/js/form-picker.js') }}"></script>
    <script src="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>

    {{-- START SELECT 2 BUTTON INIT --}}
    <script src="{{ asset('admin/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script>
        $('select.select2').select2();
    </script>
    {{-- END SELECT 2 BUTTON INIT --}}

    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            let route = $(this).attr('action');
            let method = $(this).attr('method');
            let data = new FormData(this);
            var role = $('#role').val();
            var redirectUrl = "{{ url('member/users') }}" + '?role=' + role;
            let response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
            console.log(response);
        })


        $(document).ready(function() {
            $('.select2[name="userPermission"]').on('change', function() {
                $('.allPermission').addClass('d-none');
                $('.loading-icon').removeClass('d-none');
                var selectedPermission = $(this).val();
                $.ajax({

                    url: '{{ route('panel.admin.users.get.permission') }}',
                    method: 'GET',
                    data: {
                        roleId: selectedPermission
                    },
                    success: function(res) {
                        $('.loading-icon').addClass('d-none');
                        $('.allPermission').removeClass('d-none');
                        var permissions = res.permissions;
                        document.querySelectorAll('.checkbox-item').forEach(function(checkbox) {
                            if (permissions.includes(checkbox.value)) {
                                checkbox.checked = true;
                            } else {
                                checkbox.checked = false;
                            }
                        });

                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });


        $(document).ready(function() {
            // Handle the "Select All" checkbox
            $("#select_all").change(function() {
                if ($(this).is(":checked")) {
                    // Check all checkboxes
                    $(".checkbox-item").prop("checked", true);
                } else {
                    // Uncheck all checkboxes
                    $(".checkbox-item").prop("checked", false);
                }
            });

            // Handle individual checkbox changes
            $(".checkbox-item").change(function() {
                // Update your data storage logic here
                // For example, you can store the selected checkbox values in an array
                var selectedValues = [];
                $(".checkbox-item:checked").each(function() {
                    selectedValues.push($(this).val());
                });

                // Print the selected values (you can replace this with your actual logic)
                console.log(selectedValues);
            });
        });
    </script>
    {{-- END AJAX FORM INIT --}}

    {{-- COUNTRYCODE SELECTOR INIT --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.querySelector("#phone");
            const countryCodeInput = document.querySelector("#countryCodeInput");

            const iti = window.intlTelInput(input, {
                initialCountry: "auto",
                separateDialCode: true,
                utilsScript: "{{ asset('panel/admin/plugins/country-code/utils.js') }}",
            });
            window.iti = iti;

            const updateCountryCode = () => {
                const selectedCountryData = iti.getSelectedCountryData();
                countryCodeInput.value = selectedCountryData.dialCode;
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

    {{-- START JS HELPERS INIT --}}
    {{-- <script>
        $(document).ready(function() {
            // "Select All" functionality
            $('#select_all').on('change', function() {
                var isChecked = $(this).is(':checked');
                $('.checkbox-item').prop('checked', isChecked);
            });

            // Ensure "Select All" reflects the state of individual checkboxes
            $('.checkbox-item').on('change', function() {
                if ($('.checkbox-item:checked').length === $('.checkbox-item').length) {
                    $('#select_all').prop('checked', true);
                } else {
                    $('#select_all').prop('checked', false);
                }
            });

            // Fetch permissions based on user role
            $('.select2[name="userPermission"]').on('change', function() {
                var selectedPermission = $(this).val();
                $('.allPermission').addClas s('d-none');
                $('.loading-icon').removeClass('d-none');
                $.ajax({
                    url: '{{ route('panel.admin.users.get.permission') }}',
                    method: 'GET',
                    data: {
                        roleId: selectedPermission
                    },
                    success: function(res) {
                        $('.loading-icon').addClass('d-none');
                        $('.allPermission').removeClass('d-none');
                        var permissions = res.permissions;
                        document.querySelectorAll('.checkbox-item').forEach(function(checkbox) {
                            if (permissions.includes(checkbox.value)) {
                                checkbox.checked = true;
                            } else {
                                checkbox.checked = false;
                            }
                        });

                        // Update "Select All" checkbox based on the new state
                        if ($('.checkbox-item:checked').length === $('    .checkbox-item')
                            .length) {
                            $('#select_all').prop('checked', true);
                        } else {
                            $('#select_all').prop('checked', false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });
    </script> --}}
    <script>
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
            var country = "{{ @$user->country_id }}";
            var state = "{{ @$user->state_id }}";
            var city = "{{ @$user->city_id }}";
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
    </script>
    {{-- END JS HELPERS INIT --}}
@endpush
