@extends('layouts.main')
@section('title',@$label)
@section('content')
@php
    if (@$level == 1) {
        @$page_title = 'Categories';
        @$arr = null;
    } elseif (@$level == 2) {
        $page_title = 'Sub Categories';
        $arr = ['name' => App\Models\Category::where('parent_id', @$categoryType->category_type_id)->first()->name, 'url' =>
        route('panel.admin.categories.index', @$categoryTypeId), 'class' => ''];
    } elseif (@$level == 3) {
        $page_title = 'Sub Sub Categories';
        $pre = request('parent_id') - 1;
        @$arr = ['name' => $categoryType->name ?? '', 'url' => route('panel.admin.categories.index', [@$categoryTypeId,
        'level' => 2, 'parent_id' => $pre]), 'class' => ''];
        }
    $breadcrumb_arr = [
        ['name' => 'Category Groups', 'url' => route('panel.admin.category-types.index'), 'class' => ''],
    @$arr,
        ['name' => $page_title, 'url' => 'javascript:void(0);', 'class' => 'active'],
        ];
@endphp

@push('head')
 {{-- INITIALIZE SHIMMER & INIT LOAD --}}
 <script>
    window.onload = function() {
        $('#ajax-container').show();
        fetchData("{{ route('panel.admin.categories.index', @$categoryTypeId) }}");
    };
</script>
{{-- END INITIALIZE SHIMMER & INIT LOAD --}}
@endpush

<div class="container-fluid">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-grid bg-blue"></i>
                    <div class="d-inline">
                        <h5>
                            @if(@$level == 1)
                                {{ ucwords(str_replace('_', ' ', @$categoryType->name ?? '')) }}
                            @elseif(@$level == 2)
                                Sub Category
                            @elseif(@$level == 3)
                                Sub Sub Category
                            @endif
                        </h5>
                        <span> @lang('admin/ui.list_of') {{ @$label }} </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @include('panel.admin.include.breadcrumb')
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            @include('panel.admin.include.message')
            <div class="card ">
                <div class="card-header">
                    <h3> @lang('admin/ui.create_category')</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('panel.admin.categories.store') }}" method="post"
                        enctype="multipart/form-data" class="ajaxForm">
                        @csrf

                        <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip="" regex=""
                            validation="" value="create" />
                        <x-input name="" id="encodedCategoryId" placeholder="Enter Name" type="hidden" tooltip=""
                            regex="" validation="" value="{{ secureToken(@$categoryTypeId) }}" />

                        <x-input name="category_type_id" id="categoryTypeId" placeholder="Enter Name" type="hidden"
                            tooltip="" regex="" validation="" value="{{ @$categoryTypeId }}" />

                        <x-input name="level" id="level" placeholder="Enter Name" type="hidden" tooltip="" regex=""
                            validation="" value="{{ @$level }}" />

                        @if(@$level > 1)

                            <x-input name="parent_id" id="parentId" placeholder="Enter Name" type="hidden" tooltip=""
                                regex="" validation="" value="{{ request()->parent_id ?? 0 }}" />
                        @endif
                        <div class="row">
                            <div class="col-md-12 mx-auto">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div
                                            class="form-group {{ @$errors->has('name') ? 'has-error' : '' }}">
                                            <x-label name="name" validation="category_group_remark"
                                                tooltip="edit_sub_category" />
                                            <x-textarea rows="3" regex="short_description"
                                                validation="common_short_description"
                                                value="{{ old('name') }}" name="name" id="name"
                                                placeholder="Enter Name" />
                                            <span class="text-danger fw-400 mb-1">
                                                <i class="fa fa-circle-info"></i>
                                                Use line separation to bulk creation

                                            </span>

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group ">
                                            <x-label name="icon" validation="" tooltip="icons" />
                                            <br>

                                            <div class="">
                                                <div class=" col-xs-12">

                                                    <x-input name="icon" type="file" tooltip="" regex="" validation=""
                                                        value="" class="file-upload-default" />
                                                    <div class="input-group col-xs-12">

                                                        <x-input name="icon" type="text" placeholder="Upload Icon"
                                                            tooltip="icons" regex="" validation="" value=""
                                                            class="file-upload-info" disabled />
                                                        <span class="input-group-append">
                                                            <button class="file-upload-browse btn btn-success"
                                                                style="position:absolute; right:0px" type="button">
                                                                @lang('admin/ui.upload') </button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <button type="submit" class="btn btn-primary float-right ajax-btn">
                                        @lang('admin/ui.create') </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <h3> @lang('admin/ui.manage') </h3>
                        <span><a href="javascript:void(0);" class="btn-link active records-type"
                                data-value="All">All</a> | <a href="javascript:void(0);" class="btn-link records-type"
                                data-value="Trash">Trash</a></span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div class="">
                            <select name="action" class=" form-control select-action ml-2 " id="action">
                                <option value="">Select Action</option>
                                <option value="Restore"
                                    class="trash-option @if (request()->get('trash') != 1) d-none @endif">Restore
                                </option>
                                <option value="Move To Trash"
                                    class="trash-option @if (request()->get('trash') == 1) d-none @endif">Move To Trash
                                </option>
                                <option value="Delete Permanently">Delete Permanently</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div id="ajax-container" style="display: none;">
                    @include('panel.admin.categories.load')
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('script')
    <script
        src="{{ asset('panel/member/plugins/mohithg-switchery/dist/switchery.min.js') }}">
    </script>
    @include('panel.member.include.more-action', [
    'actionUrl' => 'member/categories',
    'routeClass' => 'categories',
    ])

    {{-- START HTML TO EXCEL INIT --}}
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script src="{{ asset('panel/admin/js/index-page.js') }}"></script>
     {{-- START CUSTOM JS INIT --}}
     <script>
        $('#reset').click(function() {
            fetchData("{{ route('panel.admin.categories.index', @$categoryTypeId) }}");
            window.history.pushState("", "", "{{ route('panel.admin.categories.index', @$categoryTypeId) }}");
            $('#TableForm').trigger("reset");
            $(document).find('.close.off-canvas').trigger('click');
        });
    </script>
    {{-- END CUSTOM JS INIT --}}

    <script>
        function html_table_to_excel(type) {
            var table_core = $("#categoryTable").clone();
            var clonedTable = $("#categoryTable").clone();
            clonedTable.find('[class*="no-export"]').remove();
            clonedTable.find('[class*="d-none"]').remove();
            $("#categoryTable").html(clonedTable.html());
            var data = document.getElementById('categoryTable');

            var file = XLSX.utils.table_to_book(data, {
                sheet: "sheet1"
            });
            XLSX.write(file, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            });
            XLSX.writeFile(file, 'leadFile.' + type);
            $("#categoryTable").html(table_core.html());
        }

        $(document).on('click', '#export_button', function () {
            html_table_to_excel('xlsx');
        });

    </script>
    {{-- END HTML TO EXCEL INIT --}}

    {{-- START JS HELPERS INIT --}}
    <script>
        var categoryTypeId = $('#categoryTypeId').val();
        var parentId = $('#parentId').val();
        var level = $('#level').val();

    </script>
    {{-- END JS HELPERS INIT --}}


    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function (e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            var response = postData(method, route, 'json', data, "handleFaqCallback", null);
        });

        function handleFaqCallback(response) {
            var redirectUrl = "{{ url('admin/categories') }}/" + $('#encodedCategoryId').val();
            if (typeof (response) != "undefined" && response !== null && response.status == "success") {
                window.location.href = redirectUrl;
            }
        }

    </script>
    {{-- END AJAX FORM INIT --}}
@endpush
