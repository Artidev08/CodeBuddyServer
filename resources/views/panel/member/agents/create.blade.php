@extends('layouts.main')
@section('title', 'Agent')
@section('content')
    @php
        /**
         * Agent
         *
         * @category Hq.ai
         *
         * @ref zCURD
         * @author  Defenzelite <hq@defenzelite.com>
         * @license https://www.defenzelite.com Defenzelite Private Limited
         * @version <Hq.ai: 1.1.0>
         * @link    https://www.defenzelite.com
         */
        $breadcrumb_arr = [['name' => 'Add Agent', 'url' => route('panel.admin.agents.index'), 'class' => '']];
    @endphp
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.css') }}">

        <style>
            .error {
                color: red;
            }

            .card {
                margin-bottom: 15px
            }

            textarea.form-control {
                font-size: 20px;
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>Add Agent</h5>
                            <span>Create a record for Agent</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb')
                </div>
            </div>
        </div>
        <form class="row ajaxForm" action="{{ route('panel.admin.agents.store') }}" method="post"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="request_with" value="create">
            <div class="col-md-12">
                <!-- start message area-->
                @include('panel.admin.include.message')
                <!-- end message area-->
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3>Basic Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                    <label for="name" class="control-label ">Name<span
                                            class="text-danger">*</span></label>
                                    <a data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.add_agent_name')"><i
                                            class="ik ik-help-circle text-muted ml-1"></i></a>
                                    <input required class="form-control " name="name" type="text"
                                        pattern="[a-zA-Z]+.*"
                                        title="Please enter first letter alphabet and at least one alphabet character is required."
                                        title="Please enter first letter alphabet and at least one alphabet character is required."
                                        title="Please enter first letter alphabet and at least one alphabet character is required."
                                        id="name" value="{{ old('name', '') }}" placeholder="Enter Name">

                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="bio" class="control-label">Bio</label>
                                    <a data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.add_item_bio')"><i
                                            class="ik ik-help-circle text-muted ml-1"></i></a>
                                    <textarea class="form-control" name="bio" rows="2" id="bio" placeholder="Enter Bio">{{ old('bio') }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="model">Model <span class="text-danger">*</span></label>
                                    <a data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.add_item_department')"><i
                                            class="ik ik-help-circle text-muted ml-1"></i></a>
                                    <select required name="model_id" id="model_id" data-flag="0"
                                        class="form-control select2 model_id">
                                        @foreach (getCategoriesByCode('ModelCategories') as $model)
                                            <option value="{{ $model->id }}"
                                                {{ request('model_id') == $model->id ? 'Selected' : '' }}>
                                                {{ $model->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="gender">{{ __('Gender') }}</label>
                                    <a href="javascript:void(0);" title="@lang('panel/admin/tooltip.add_user_gender')"><i
                                            class="ik ik-help-circle text-muted ml-1"></i></a>
                                    <div class="form-radio">
                                        <div class="radio radio-inline">
                                            <label class="">
                                                <input type="radio" name="gender" value="Male" checked>
                                                <i class="helper"></i>{{ __('Male') }}
                                            </label>
                                        </div>
                                        <div class="radio radio-inline">
                                            <label class="">
                                                <input type="radio" name="gender" value="Female">
                                                <i class="helper"></i>{{ __('Female') }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group{{ $errors->has('item_image') ? 'has-error' : '' }}">
                                    <label for="item_image" class="control-label">Avatar</label>
                                    <a data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.add_item_banner')"><i
                                            class="ik ik-help-circle text-muted ml-1"></i></a>
                                    <input class="form-control" name="avatar" type="file" id="item_image"
                                        value="{{ old('avatar') }}" accept=".png, .jpg, .jpeg,.gif">
                                    <small class="text-danger">Recommended Image in Dimension 400*400</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Prompt Context</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="task" class="control-label">Task</label>
                                    <a data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.add_prompt_task')"><i
                                            class="ik ik-help-circle text-muted ml-1"></i></a>
                                    <textarea rows="2" class="form-control" name="prompt[task]" id="prompt[task]" placeholder="Enter Task">{{ old('prompt[task]') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="note" class="control-label">Note</label>
                                    <a data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.add_prompt_note')"><i
                                            class="ik ik-help-circle text-muted ml-1"></i></a>
                                    <textarea rows="2" class="form-control" name="prompt[note]" id="prompt[note]" placeholder="Enter Note">{{ old('prompt[note]') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="output_format" class="control-label">Output Format</label>
                                    <a data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.add_prompt_output_format')"><i
                                            class="ik ik-help-circle text-muted ml-1"></i></a>
                                    <textarea rows="2" class="form-control" name="prompt[output_format]" id="prompt[output_format]"
                                        placeholder="Enter Output Format">{{ old('prompt[output_format]') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <button id="submit" type="submit" class="btn btn-primary floating-btn ajax-btn">Create</button>
        </form>
    </div>
    @push('script')
        <script src="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>

        {{-- START AJAX FORM INIT --}}

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

            getStates(101);
            $('#country').on('change', function(e) {
                getStates($(this).val());
            })

            $('#state').on('change', function(e) {
                getCities($(this).val());
            })

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
            
            // STORE DATA USING AJAX
            $('.ajaxForm').on('submit', function(e) {
                e.preventDefault();
                var route = $(this).attr('action');
                var method = $(this).attr('method');
                var data = new FormData(this);
                var redirectUrl = "{{ url('admin/agents') }}";
                var response = postData(method, route, 'json', data, null, null, toast = 1, async = true, redirectUrl);
            });
        </script>
        {{-- END AJAX FORM INIT --}}

        {{-- START JS HELPERS INIT --}}
        <script></script>
        {{-- END JS HELPERS INIT --}}
    @endpush
@endsection
