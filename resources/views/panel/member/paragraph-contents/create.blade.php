@extends('layouts.main')
@section('title', @$label . ' Add')
@section('content')
    @php
        $breadcrumb_arr = [
            ['name' => 'Paragraphs Cont.', 'url' => 'javascript:void(0);', 'class' => ''],
            ['name' => 'Add ', 'url' => 'javascript:void(0);', 'class' => 'active'],
        ];
    @endphp


    <div class="container-fluid container-fluid-height">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5> @lang('admin/ui.add') {{ @$label ?? '' }} </h5>
                            <span> @lang('admin/ui.create_record') {{ @$label ?? '' }} </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb')
                </div>
            </div>
        </div>
        <form action="{{ route('panel.admin.paragraph-contents.store') }}" method="post" enctype="multipart/form-data"
            class="ajaxForm">
            @csrf

            <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip="" regex=""
                validation="" value="create" />
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h3> @lang('admin/ui.paragraph_details') </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <x-label name="group" validation=""
                                            tooltip="add_site_content_managements_group" />
                                        <x-select name="group" validation="" id="group" class="course-filter"
                                            value="{{ old('group') }}" label="Group" optionName="label" valueName="label"
                                            :arr="\App\Models\ParagraphContent::GROUPS" />

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <x-label name="code" validation="paragraph_code"
                                                tooltip="add_site_content_managements_code" />

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <x-input name="code" placeholder="Enter code" type="text"
                                            tooltip="add_site_content_managements_code" regex="slider_code"
                                            validation="paragraph_code" value="{{ old('code') }}" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <x-label name="type" validation=""
                                            tooltip="add_site_content_managements_type" />
                                        <x-select name="type" validation="paragraph_type" id="remarkType" class=""
                                            value="{{ old('type') }}" label="Type" optionName="label" valueName=""
                                            :arr="\App\Models\ParagraphContent::TYPES" />

                                    </div>
                                </div>
                                <div class="col-md-6 mt-1">
                                    <div class="form-group {{ @$errors->has('is_permanent') ? 'has-error' : '' }}">
                                        @php
                                            $isPermanent_arr = ['Yes', 'No'];
                                        @endphp
                                        <x-label name="is_permanent" validation="paragraph_type"
                                            tooltip="add_site_content_managements_permanent" />
                                        <x-radio name="is_permanent" type="radio"   value="{{ old('is_permanent') ?? 1 }}" :arr="@$isPermanent_arr" />

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-2"></div>
            </div>
            <button type="submit" class="btn btn-primary floating-btn ajax-btn"> @lang('admin/ui.create') </button>
        </form>
    </div>
@endsection

@push('script')
    {{-- START CODE GENERATOR --}}
    <script>
        function generateRandomCode(length) {
            length = length || 10;
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var randomString = '';

            for (var i = 0; i < length; i++) {
                randomString += characters.charAt(Math.floor(Math.random() * characters.length));
            }
            return randomString;
        }

        $('.autofill-code').on('click', function(e) {
            e.preventDefault();
            var selected_val = $('#group').val();
            var group_name = $('#pcg-' + selected_val).html();
            var randomCode = generateRandomCode();
            if (group_name == 'New Paragraph category') {
                $('#name').val('NPC-PT-' + randomCode);
            } else if (group_name == 'General') {
                $('#name').val('GEN-PT-' + randomCode);
            } else {
                $('.autofill-code').attr('disable');
            }

        })
    </script>
    {{-- END CODE GENERATOR --}}


    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            if (editor != undefined) {
                const description = editor.getData();
                data.append('value', description);
            }
            var redirectUrl = "{{ url('/admin/paragraph-contents') }}" + "/" + response.paragraphContent - > id;
            var response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
        })
    </script>
    {{-- END AJAX FORM INIT --}}
@endpush
