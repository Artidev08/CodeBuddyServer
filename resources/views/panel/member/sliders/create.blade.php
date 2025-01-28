@extends('layouts.main')
@section('title', @$label . ' Add')
@section('content')
    @php
        $breadcrumb_arr = [['name' => 'Add ' . @$label, 'url' => 'javascript:void(0);', 'class' => '']];
    @endphp

    @push('head')
        <link rel="stylesheet" href="{{ asset('panel/admin/plugins/mohithg-switchery/dist/switchery.min.css') }}">
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5> @lang('admin/ui.add') {{ @$label }}</h5>

                            <span> @lang('admin/ui.list_of') {{ @$sliderType->title ?? '' }} {{ @$label }}

                                @if (request()->get('slidertype'))
                                    of {{ fetchFirst('App\Models\SliderType', request()->get('slidertype'), 'title', '') }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb')
                </div>
            </div>
        </div>
        <form action="{{ route('panel.admin.sliders.store') }}" method="post" enctype="multipart/form-data"
            class="ajaxForm">
            @csrf
            <div class="row">
                <div class="col-2"></div>
                <div class="col-md-8">
                    <!-- start message area-->
                    @include('panel.admin.include.message')
                    <!-- end message area-->
                    <div class="card ">
                        <div class="card-header justify-content-between">
                            <h3>{{ @$sliderType->title ?? '' }} {{ @$label }}</h3>
                        </div>
                        <div class="card-body">
                            <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip=""
                                regex="" validation="" value="create" />
                            <div class="row">
                                <div class="col-md-8 col-12">
                                    <div class="form-group {{ @$errors->has('title') ? 'has-error' : '' }}">
                                        <x-label name="title" validation="common_title"
                                            tooltip="add_category_types_code" />
                                        <x-input name="code" placeholder="Enter Title" type="text"
                                            tooltip="add_category_types_code" regex="title" validation="common_title"
                                            value="{{ old('title') }}" />
                                    </div>
                                    <div class="alert alert-danger"><i class="ik ik-help-circle text-danger ml-1"></i>
                                        {!! 'Content and image upload options appear after creation.' !!}

                                        @lang('admin/ui.slider_create_content') </div>

                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="form-group {{ @$errors->has('Type') ? 'has-error' : '' }}">
                                        <label for="Type" class="control-label"> @lang('admin/ui.type') <span
                                                class="text-danger">*</span> </label>
                                        {!! getHelp('Publicly readable type') !!}
                                        <select name="type" id="remarkType" class="form-control select2" required>
                                            @foreach (\App\Models\Slider::TYPES as $key => $type)
                                                <option value="{{ @$key }}">{{ @$type['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 d-none">
                                    <div class="form-group {{ @$errors->has('image') ? 'has-error' : '' }}">
                                        {{-- <label for="image" class="control-label">  @lang('admin/ui.image') </label>
                                    <input class="form-control" name="image" type="file" id="image"
                                        value="{{ old('image') }}"><small class="text-danger">Recommended Image in
                                        Dimension 1800*600</small> --}}
                                        <x-label name="image" validation="" tooltip="" />

                                        <x-input name="image" id="image" type="file" tooltip="" regex=""
                                            validation="" value="" class="" /><small
                                            class="text-danger">Recommended Image in Dimension 1800*600</small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 d-none">
                                    <div
                                        class="form-group d-flex mt-4 align-items-center {{ @$errors->has('status') ? 'has-error' : '' }}">
                                        @php
                                            $checkbox_arr = ['is_published'];
                                        @endphp
                                        <x-checkbox name="status" class="js-switch switch-input" checked value="2"
                                            type="checkbox" tooltip="" :arr="@$checkbox_arr" />
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @if (request()->get('sliderTypeId'))
                                            <x-input name="slider_type_id" placeholder="Enter Name" type="hidden"
                                                tooltip="" regex="" validation=""
                                                value="{{ request()->get('sliderTypeId') }}" />
                                        @else
                                            <div class="col-md-12 col-12">
                                                <div class="form-group">
                                                    {{-- <label for="slider_type_id">Slider Type <span
                                                        class="text-danger">*</span></label>
                                                {!! getHelp('Publicly readable slider type') !!}
                                                <select required name="slider_type_id" id="slider_type_id"
                                                    class="form-control select2">
                                                    <option value="" readonly>Select Slider Type </option>
                                                    @foreach (App\Models\SliderType::all() as $option)
                                                        <option value="{{ @$option->id }}">
                                                            {{ @$option->title ?? '--' }}</option>
                                                    @endforeach
                                                </select> --}}
                                                    <x-label name="type" validation="template_type"
                                                        tooltip="add_slider_type" />

                                                    <x-select name="slider_type_id" value="{{ old('slider_type_id') }}"
                                                        valueName="" validation="template_type" id="slider_type_id"
                                                        class="select2 type" label="Type" optionName="name"
                                                        :arr="App\Models\SliderType::all()" />
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-2"></div>
            </div>
            <button type="submit" class="btn btn-primary floating-btn"> @lang('admin/ui.create') </button>
        </form>

        <x-input name="" id="sliderType" placeholder="Enter Name" type="hidden" tooltip="" regex=""
            validation="" value="{{ request()->get('sliderTypeId') }}" />
    </div>

@endsection

@push('script')
    <script src="{{ asset('panel/admin/plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>


    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var sliderTypeId = $('#sliderType').val();
            var data = new FormData(this);
            var redirectUrl = "{{ url('/admin/sliders') }}" + '?sliderTypeId=' + sliderTypeId;
            var response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);

        });
    </script>

    {{-- END AJAX FORM INIT --}}
@endpush
