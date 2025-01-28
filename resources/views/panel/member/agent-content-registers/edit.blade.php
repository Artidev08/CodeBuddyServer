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
         * @author Defenzelite <hq@defenzelite.com>
         * @license https://www.defenzelite.com Defenzelite Private Limited
         * @version <Hq.ai: 1.1.0>
         * @link https://www.defenzelite.com
         */
        $breadcrumb_arr = [['name' => 'Edit Agent', 'url' => 'javascript:void(0);', 'class' => '']];
    @endphp
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.css') }}">
        <!-- themekit admin template asstes -->

        <style>
            .error {
                color: red;
            }

            textarea.form-control {
                font-size: 20px;
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-Agents-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>Edit Agent</h5>
                            <span>Update a record for Agent</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.member.include.breadcrumb')
                </div>
            </div>
        </div>
        <div class="row">
            <form class="row ajaxForm"action="{{ route('panel.member.agent-content-registers.update', $agent->id) }}" method="post"
                enctype="multipart/form-data" id="ItemForm">
                @csrf
                <div class="col-md-12">
                    <!-- start message area-->
                    @include('panel.member.include.message')
                    <!-- end message area-->
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3>Basic Detail</h3>
                            <img class="avatar"
                                src="{{ $agent && $agent->avatar ? $agent->avatar : asset('backend/default/default-avatar.png') }}"
                                style="object-fit: cover; width: 50px; height: 50px; border-radius: 50%;" alt="">
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                        <label for="name" class="control-label ">Name<span
                                                class="text-danger">*</span></label>
                                        <a data-toggle="tooltip" href="javascript:void(0);" title="@lang('admin/tooltip.add_agent_name')"><i
                                                class="ik ik-help-circle text-muted ml-1"></i></a>
                                        <input required class="form-control " value="{{ $agent->name }}" name="name"
                                            type="text" pattern="[a-zA-Z]+.*"
                                            title="Please enter first letter alphabet and at least one alphabet character is required."
                                            title="Please enter first letter alphabet and at least one alphabet character is required."
                                            title="Please enter first letter alphabet and at least one alphabet character is required."
                                            id="name" value="{{ old('name', '') }}" placeholder="Enter Name">

                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="bio" class="control-label">Bio</label>
                                        <a data-toggle="tooltip" href="javascript:void(0);" title="Bio"><i
                                                class="ik ik-help-circle text-muted ml-1"></i></a>
                                        <textarea rows="3" class="form-control" name="bio" id="bio" placeholder="Enter Bio">{{ $agent->bio }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="model">Model <span class="text-danger">*</span></label>
                                        <a data-toggle="tooltip" href="javascript:void(0);" title="Model"><i
                                                class="ik ik-help-circle text-muted ml-1"></i></a>
                                        <select required name="model_id" id="model_id" data-flag="0"
                                            class="form-control select2 model_id">
                                            @foreach (getCategoriesByCode('ModelCategories') as $model)
                                                <option value="{{ $model->id }}"
                                                    {{ $agent->model_id == $model->id ? 'Selected' : '' }}>
                                                    {{ $model->name ?? '' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="gender">{{ __('Gender') }}</label>
                                        <a href="javascript:void(0);" title="Gender"><i
                                                class="ik ik-help-circle text-muted ml-1"></i></a>
                                        <div class="form-radio">
                                            <div class="radio radio-inline">
                                                <label>
                                                    <input type="radio" name="gender" value="Male"
                                                        @if ($agent->gender == 'Male') checked @endif>
                                                    <i class="helper"></i>{{ __('Male') }}
                                                </label>
                                            </div>
                                            <div class="radio radio-inline">
                                                <label>
                                                    <input type="radio" name="gender" value="Female"
                                                        @if ($agent->gender == 'Female') checked @endif>
                                                    <i class="helper"></i>{{ __('Female') }}
                                                </label>
                                            </div>
                                        </div>
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
                            <h3>Prompt</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="task" class="control-label">Task</label>
                                        <a data-toggle="tooltip" href="javascript:void(0);" title="Task"><i
                                                class="ik ik-help-circle text-muted ml-1"></i></a>
                                        <textarea rows="3" class="form-control" name="prompt[task]" id="prompt[task]" placeholder="Enter Task">{{ @$agent->prompt['task'] }}</textarea>
                                    </div>
                                </div>
                                
                               
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="note" class="control-label">Note</label>
                                        <a data-toggle="tooltip" href="javascript:void(0);" title="Note"><i
                                                class="ik ik-help-circle text-muted ml-1"></i></a>
                                        <textarea rows="3" class="form-control" name="prompt[note]" id="prompt[note]" placeholder="Enter Note">{{ @$agent->prompt['note'] }}</textarea>
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="output_format" class="control-label">Output Format</label>
                                        <a data-toggle="tooltip" href="javascript:void(0);" title="Output Format"><i
                                                class="ik ik-help-circle text-muted ml-1"></i></a>
                                        <textarea rows="3" class="form-control" name="prompt[output_format]" id="prompt[output_format]"
                                            placeholder="Enter Output Format">{{ @$agent->prompt['output_format'] }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <button type="submit" class="btn btn-primary floating-btn ajax-btn">Save & Update</button>
            </form>
        </div>
    </div>

    <div class="modal fade" id="addCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add New Category</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="category-form">
                    <div class="modal-body">
                        <input type="hidden" name="category_type_code" value="ItemCategories">
                        <input type="hidden" name="category_type_id" value="6">
                        <input type="hidden" name="level" value="1">
                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" pattern="[a-zA-Z]+.*"
                                        title="Please enter first letter alphabet and at least one alphabet character is required."
                                        title="Please enter first letter alphabet and at least one alphabet character is required."
                                        name="name" class="form-control" placeholder="Name" value="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <label>Icon</label>
                                    <input type="file" name="icon" class="form-control" placeholder="Name"
                                        value="">
                                </div>
                            </div>
                        </div>
                        <div class="ajax-message mb-2"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-sm float-end">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- push external js -->
    @push('script')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js"></script>
        <script src="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>

        {{-- START AJAX FORM INIT --}}

        <script>
            $('.ajaxForm').on('submit', function(e) {
                e.preventDefault();
                var route = $(this).attr('action');
                var method = $(this).attr('method');
                var data = new FormData(this);
                var redirectUrl = "{{ url('admin/agents') }}";
                var response = postData(method, route, 'json', data, null, null, toast = 1, async = true, redirectUrl);
            })
        </script>
        {{-- END AJAX FORM INIT --}}
    @endpush
@endsection
