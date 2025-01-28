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
                'module' => 'Manage Order',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_order'],
                    ['label' => 'Add', 'name' => 'add_order'],
                    ['label' => 'Edit', 'name' => 'edit_order'],
                    ['label' => 'Delete', 'name' => 'delete_order'],
                ],
            ],
            [
                'module' => 'Manage Subscribers',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_subscribers'],
                    ['label' => 'Add', 'name' => 'add_subscribers'],
                    ['label' => 'Edit', 'name' => 'edit_subscribers'],
                    ['label' => 'Delete', 'name' => 'delete_subscribers'],
                ],
            ],
            [
                'module' => 'Manage Payouts',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_payout'],
                    ['label' => 'Add', 'name' => 'add_payout'],
                    ['label' => 'Edit', 'name' => 'edit_payout'],
                    ['label' => 'Delete', 'name' => 'delete_payout'],
                ],
            ],
            [
                'module' => 'Manage Items',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_item'],
                    ['label' => 'Add', 'name' => 'add_item'],
                    ['label' => 'Edit', 'name' => 'edit_item'],
                    ['label' => 'Delete', 'name' => 'delete_item'],
                    ['label' => 'Bulk Upload', 'name' => 'bulk_upload_item'],
                    ['label' => 'FeedBack', 'name' => 'view_feedback'],
                ],
            ],
            [
                'module' => 'Subscriptions Plans',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_subscriptions'],
                    ['label' => 'Add', 'name' => 'add_subscriptions'],
                    ['label' => 'Edit', 'name' => 'edit_subscriptions'],
                    ['label' => 'Delete', 'name' => 'delete_subscriptions'],
                ],
            ],
            [
                'module' => 'Reports',
                'childrens' => [
                    ['label' => ' Purchase Flow', 'name' => 'view_purchase'],
                    ['label' => 'Registeration Flow', 'name' => 'view_registeration'],
                ],
            ],
            [
                'module' => 'Administrator',
                'childrens' => [
                    ['label' => 'Admin Management', 'name' => 'view_admin'],
                    ['label' => 'Add Admin', 'name' => 'add_admin'],
                    ['label' => 'Edit Admin', 'name' => 'edit_admin'],
                    ['label' => 'Delete Admin', 'name' => 'delete_admin'],
                    ['label' => 'User Management', 'name' => 'view_user'],
                    ['label' => 'Add User', 'name' => 'add_user'],
                    ['label' => 'Edit User', 'name' => 'edit_user'],
                    ['label' => 'Delete User', 'name' => 'delete_user'],
                ],
            ],
            [
                'module' => 'Contact-Enquiry',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_enquiry'],
                    ['label' => 'Add', 'name' => 'add_website_enquiry'],
                    ['label' => 'Show', 'name' => 'show_website_enquiry'],
                    ['label' => 'Edit', 'name' => 'edit_website_enquiry'],
                    ['label' => 'Delete', 'name' => 'delete_website_enquiry'],
                    ['label' => 'Bulk Upload', 'name' => 'bulk_upload_enquiry'],
                ],
            ],
            [
                'module' => 'Newsletters',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_newsletter'],
                    ['label' => 'Add ', 'name' => 'add_newsletter'],
                    ['label' => 'Edit ', 'name' => 'edit_newsletter'],
                    ['label' => 'Delete ', 'name' => 'delete_newsletter'],
                    ['label' => 'Bulk Upload ', 'name' => 'bulk_upload_newsletter'],
                ],
            ],
            [
                'module' => 'Lead',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_Lead'],
                    ['label' => 'Add ', 'name' => 'add_Lead'],
                    ['label' => 'Show ', 'name' => 'show_Lead'],
                    ['label' => 'Edit ', 'name' => 'edit_Lead'],
                    ['label' => 'Delete ', 'name' => 'delete_Lead'],
                    ['label' => 'Bulk Upload ', 'name' => 'bulk_upload_Lead'],
                ],
            ],
            [
                'module' => 'Templates',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_template'],
                    ['label' => 'Add ', 'name' => 'add_template'],
                    ['label' => 'Show ', 'name' => 'show_template'],
                    ['label' => 'Edit ', 'name' => 'edit_template'],
                    ['label' => 'Delete ', 'name' => 'delete_template'],
                ],
            ],
            [
                'module' => 'Category Groups',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_category_type'],
                    ['label' => 'Add ', 'name' => 'add_category_type'],
                    ['label' => 'Edit ', 'name' => 'edit_category_type'],
                    ['label' => 'Sync ', 'name' => 'sync_category_type'],
                ],
            ],
            [
                'module' => 'Categories ',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_categories'],
                    ['label' => 'Add ', 'name' => 'add_categories'],
                    ['label' => 'Edit ', 'name' => 'edit_categories'],
                ],
            ],
            [
                'module' => 'Slider Type ',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_slider_type'],
                    ['label' => 'Add ', 'name' => 'add_slider_type'],
                    ['label' => 'Edit ', 'name' => 'edit_slider_type'],
                    ['label' => 'Sync ', 'name' => 'sync_slider_type'],
                ],
            ],
            [
                'module' => 'Slider ',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_slider'],
                    ['label' => 'Add ', 'name' => 'add_slider'],
                    ['label' => 'Edit ', 'name' => 'edit_slider'],
                    ['label' => 'Delete ', 'name' => 'delete_slider'],
                    ['label' => 'Bulk Upload ', 'name' => 'bulk_upload_slider'],
                ],
            ],
            [
                'module' => 'Paragraph Contents ',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_paragraph'],
                    ['label' => 'Add ', 'name' => 'add_paragraph'],
                    ['label' => 'Edit ', 'name' => 'edit_paragraph'],
                    ['label' => 'Delete ', 'name' => 'delete_paragraph'],
                    ['label' => 'Bulk Upload ', 'name' => 'bulk_upload_paragraph'],
                ],
            ],
            [
                'module' => 'FAQs ',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_faq'],
                    ['label' => 'Add ', 'name' => 'add_faq'],
                    ['label' => 'Edit ', 'name' => 'edit_faq'],
                    ['label' => 'Delete ', 'name' => 'delete_faq'],
                    ['label' => 'Bulk Upload ', 'name' => 'bulk_upload_faq'],
                ],
            ],
            [
                'module' => 'Locations ',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_location'],
                    ['label' => 'Add ', 'name' => 'add_location'],
                    ['label' => 'Edit ', 'name' => 'edit_location'],
                ],
            ],
            [
                'module' => 'State ',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_state'],
                    ['label' => 'Add ', 'name' => 'add_state'],
                    ['label' => 'Edit ', 'name' => 'edit_state'],
                ],
            ],
            [
                'module' => 'City ',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_city'],
                    ['label' => 'Add ', 'name' => 'add_city'],
                    ['label' => 'Edit ', 'name' => 'edit_city'],
                ],
            ],
            [
                'module' => 'Control Seo ',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_seo'],
                    ['label' => 'Add ', 'name' => 'add_seo'],
                    ['label' => 'Edit ', 'name' => 'edit_seo'],
                    ['label' => 'Delete ', 'name' => 'delete_seo'],
                ],
            ],
            [
                'module' => 'Website Pages ',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_pages'],
                    ['label' => 'Add ', 'name' => 'add_pages'],
                    ['label' => 'Edit ', 'name' => 'edit_pages'],
                ],
            ],
            [
                'module' => 'Resource',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_resource'],
                    ['label' => 'Add', 'name' => 'add_resource'],
                    ['label' => 'Edit', 'name' => 'edit_resource'],
                    ['label' => 'Delete', 'name' => 'delete_resource'],
                ],
            ],
            [
                'module' => 'Blog',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_blog'],
                    ['label' => 'Add', 'name' => 'add_blog'],
                    ['label' => 'Edit', 'name' => 'edit_blog'],
                    ['label' => 'Show', 'name' => 'show_blog'],
                    ['label' => 'Delete', 'name' => 'delete_blog'],
                ],
            ],
            [
                'module' => 'Support Ticket',
                'childrens' => [
                    ['label' => 'View', 'name' => 'view_support_ticket'],
                    ['label' => 'Add', 'name' => 'add_support_ticket'],
                    ['label' => 'Edit', 'name' => 'edit_support_ticket'],
                    ['label' => 'Show', 'name' => 'show_support_ticket'],
                    ['label' => 'Delete', 'name' => 'delete_support_ticket'],
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
                                                <input type="tel" class="form-control"
                                                id="phone" name="phone" value="{{ $user->fullPhone() }}" >
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
                                    <x-checkbox name="is_ai_enabled" class="js-switch switch-input" value="{{ $user->is_ai_enabled }}"
                                        type="checkbox" tooltip="" validation="" id="is_ai_enabled"
                                        :arr="@$ai_enabled_arr" />
                                    <x-label name="/" validation="" tooltip="is_ai_enabled"
                                        class="" />
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
                                        <label for="task" class="control-label">Prompt<span class="text-danger">*</span></label><a data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.add_prompt_task')"><i
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
                                        
                                        <textarea rows="12" class="form-control" name="ai_payload[prompt]" required id="ai_payload" placeholder="Enter Prompt">{{ $user->ai_payload['prompt'] ?? old('prompt[prompt]') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                @if (UserRole($user->id)->display_name != 'Member')
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h3 class="mb-1 mt-1"> @lang('Access Permissions:')</h3>
                                <div>

                                    <select class="form-control select2" name="userPermission">
                                        <option value="" readonly>Select Permission Group</option>
                                        @foreach ($userPermissions as $key => $userPermission)
                                            <option value="{{ $key }}"
                                                @if ($user->permissions != null && isset($user->permissions['key']) && $user->permissions['key'] == $key) selected @endif>
                                                {{ $userPermission['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
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
            var redirectUrl = "{{ url('admin/users') }}" + '?role=' + role;
            let response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
            console.log(response);
        })
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
    <script>
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
    </script>
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
