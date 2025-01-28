@extends('layouts.main')
@section('title', $slider->getPrefix().' Slider Edit')
@section('content')
@php
    $breadcrumb_arr = [['name' => 'Slider', 'url' => 'javascript:void(0);', 'class' => ''],
['name' => @$slider->getPrefix(), 'url' => 'javascript:void(0);', 'class' => ''],
    ['name' => 'Edit', 'url' => 'javascript:void(0);', 'class' => 'active']];
@endphp
@push('head')
    <link rel="stylesheet" href="{{ asset('panel/admin/plugins/mohithg-switchery/dist/switchery.min.css') }}">
    <style>
        .delete-item {
            color: #ffffff !important;
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
                        <h5>  @lang('admin/ui.edit') {{ @$slider->getPrefix() }}</h5>

                        <span> @lang('admin/ui.update_record')  {{ @$sliderType->title ?? '--' }}</span>

                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @include('panel.admin.include.breadcrumb')
            </div>
        </div>
    </div>
    <form action="{{ route('panel.admin.sliders.update', $slider->id) }}" method="post"
            enctype="multipart/form-data"
            class="ajaxForm">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <!-- start message area-->
            @include('panel.admin.include.message')
            <!-- end message area-->
                <div class="card ">
                    <div class="card-header">
                        <h3> @lang('admin/ui.update')   @lang('admin/ui.slider') </h3>
                    </div>
                    <div class="card-body">

                        <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip=""
                        regex="" validation="" value="update" />

                        <x-input name="type" placeholder="Enter Name" type="hidden" tooltip=""
                        regex="" validation="" value="{{ @$slider->type }}" />
                        <div class="row">
                            <div class="col-md-12 col-12">
                                <div class="form-group {{ @$errors->has('title') ? 'has-error' : '' }}">
                                    <x-label name="title" validation="common_title" tooltip="add_category_types_code" />
                                    <x-input name="title" placeholder="Enter Title" type="text"
                                        tooltip="add_category_types_code" regex="title" validation="common_title"
                                        value="{{ @$slider->title  }}" />
                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <div class="form-group">
                                    <label for="description"
                                            class="control-label"> @lang('admin/ui.description') </label>
                                    {!! getHelp('Description about the slider group') !!}
                                    <div id="toolbar-container"></div>
                                    @if (@$slider->type == 2)
                                        <div id="txt_area">
                                            {!! @$slider->description ?? '--' !!}
                                        </div>
                                    @else
                                        <div id="content">
                                            <textarea name="description" class="form-control ck-editor description"
                                                        rows="5">{!! @$slider->description ?? '--' !!}</textarea>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between pb-1">
                        <h3>  @lang('admin/ui.image') </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if (request()->get('sliderTypeId'))
                            <x-input name="slider_type_id" placeholder="Enter Name" type="hidden" tooltip=""
                            regex="" validation="" value="{{ request()->get('sliderTypeId') }}" />
                            @else
                                <div class="col-md-12 col-12">
                                    <div class="form-group d-none">
                                        <label for="slider_type_id"> @lang('admin/ui.slider')
                                                @lang('admin/ui.type')  <span
                                                class="text-danger">*</span></label>
                                        {!! getHelp('Publicly readable name') !!}
                                        <select required name="slider_type_id" id="slider_type_id"
                                                class="form-control select2">
                                            <option value="" readonly>Select Slider Type</option>
                                            @foreach (App\Models\SliderType::all() as $option)
                                                <option value="{{ @$option->id }}"
                                                        @if (@$option->id == @$slider->slider_type_id) selected @endif>
                                                    {{ @$option->title ?? '--' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-12 col-12">
                                <div class="form-group {{ @$errors->has('image') ? 'has-error' : '' }}">
                                    {{-- <label for="image" class="control-label"> @lang('admin/ui.image') </label> --}}
                                    <x-label name="image" validation="" tooltip="" />
                                    
                                        <x-input name="image" id="image" type="file" tooltip="" regex="" validation=""
                                        value="" class="" /><small
                                        class="text-danger">Recommended Image in Dimension 1800*600</small>
                                    @if (@$slider->getMedia('image')->count() > 0)
                                        <div class="mt-2">
                                            <img id="image_img" src="{{ @$slider->getFirstMediaUrl('image') }}"
                                                    class="mt-2" alt="{{ @$slider->title }}"
                                                    style="border-radius: 10px;width:100px;height:80px;"/>
                                            <a href="{{ route('panel.admin.sliders.destroy-media', $slider->id) . '?media=image' }}"
                                                style="position: absolute;" class="btn btn-danger delete-item"><i
                                                    class="fa fa-trash"></i></a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12 col-12">
                                <div
                                    class="form-group d-flex  mt-4 align-items-center {{ @$errors->has('status') ? 'has-error' : '' }}">
                                        @php
                                        $checkbox_arr = ["is_published"];
                                    @endphp
                                    <x-checkbox name="status"  class="js-switch switch-input" checked value="1" type="checkbox" tooltip=""  :arr="@$checkbox_arr"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary floating-btn"> @lang('admin/ui.update') </button>
    </form>
     <x-input name="" id="sliderType" placeholder="Enter" type="hidden" tooltip=""
    regex="" validation="" value="{{ request()->get('sliderTypeId')  }}" />
</div>

@endsection

@push('script')
    <script src="{{ asset('panel/admin/plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>
    {{-- START DECOUPLEDEDITOR INIT --}}
    <script src="{{ asset('panel/admin/plugins/ckeditor5/ckeditor.js') }}"></script>

    <script>
        let editor;
        $(window).on('load', function () {
            var type = '{{ $slider->type }}';
            if (type == 2) {
                $('#txt_area').addClass('ck-editor');
                DecoupledEditor
                    .create(document.querySelector('.ck-editor'), {
                        ckfinder: {
                            uploadUrl: "{{ route('panel.admin.media.ckeditor.upload') . '?_token=' . csrf_token() }}",
                        }
                    })
                    .then(newEditor => {
                        editor = newEditor;
                        const toolbarContainer = document.querySelector('#toolbar-container');

                        toolbarContainer.appendChild(editor.ui.view.toolbar.element);
                    })
                    .catch(error => {
                        console.error(error);
                    });
            } else {
                var content = $('#description').val();
                $('#content').html(
                    '<textarea name="description" class="form-control ck-editor description" rows="5">{{ $slider->description }}</textarea>'
                );
            }
        });
    </script>
    {{-- END DECOUPLEDEDITOR INIT --}}

    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function (e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var sliderTypeId = $('#sliderType').val();
            var data = new FormData(this);
            if (editor != undefined) {
                const description = editor.getData();
                data.append('description', description);
            }
            var redirectUrl = "{{ url('/admin/sliders') }}" + '?sliderTypeId=' + sliderTypeId;
            {{-- var redirectUrl = "{{url('/admin/sliders')}}"; --}}
            var response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);

        });
    </script>
    {{-- END AJAX FORM INIT --}}

    {{-- START JS HELPERS INIT --}}
    <script>
        document.getElementById('image_img').onchange = function () {
            var src = URL.createObjectURL(this.files[0])
            $('#image_img').removeClass('d-none');
            document.getElementById('image_img').src = src
        }
    </script>
    {{-- END JS HELPERS INIT --}}
@endpush
