@extends('layouts.main')
@section('title', @$label)
@section('content')
@php
    $breadcrumb_arr = [['name' => @$label, 'url' => 'javascript:void(0);', 'class' => 'active']];
@endphp

@push('head')
    <style>
        .custom-card {

            border: 1px solid #ced4da;
            transition: box-shadow 0.3s;
            cursor: pointer;
            padding: 10px;
        }

        .custom-card.selected {
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
            border: 2px solid #1373d9 !important;
        }

        .btn i {
            margin-right: 0px !important;
        }

        .footer-margin-l {
            margin-left: -16rem !important;
        }

        .footer-margin-r {
            margin-right: 16rem !important;
        }

        .wrapper .page-wrap .main-content {
            padding-bottom: 0 !important;
        }
    </style>
@endpush

<div class="container-fluid container-fluid-height">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-grid bg-blue"></i>
                    <div class="d-inline">
                        <h5> @lang(@$label) </h5>
                        <span> @lang('admin/ui.list_of')  {{ @$label }}</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                @include('panel.admin.include.breadcrumb')
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div>
                        <h3>{{ @$label }}</h3>

                    </div>

                    <div class="d-flex justify-content-right">
                        <form action="{{ route('panel.admin.category-types.index') }}" class="d-flex" method="GET"
                            id="TableForm">
                            <div class="dropdown">
                                @if ($permissions->contains('add_category'))
                                    <a href="{{ route('panel.admin.category-types.create') }}"
                                        class="btn btn-sm btn-outline-primary mr-2" title="Add New Category"><i
                                            class="fa fa-plus" aria-hidden="true"></i> @lang('admin/ui.add')
                                    </a>
                                @endif
                                    <button class="dropdown-toggle p-0 custom-dopdown bulk-btn btn btn-light "
                                        type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false"><i class="ik ik-more-vertical pl-1"></i></button>
                                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                        @if (getSetting('category_management_bulk_sync', @$setting))
                                        <hr class="m-1">

                                        <a href="#" class="dropdown-item syncCategories text-secondary fw-700"> Sync
                                            Category
                                        </a>
                                        @endif
                                    </ul>
                        </form>
                    </div>

                </div>
            </div>
            <div id="ajax-container">
                @include('panel.admin.category-types.load')
            </div>
        </div>
    </div>
    @include('panel.admin.modal.sitemodal', ['title' => 'How to use','content' =>
            'You need to create a unique code and call the unique code with paragraph content helper.',
        ])
</div>

@endsection

@push('script')
    @include('panel.admin.include.bulk-script')
    @include('panel.admin.include.more-action', ['actionUrl' => 'admin/category-types',
        'routeClass' => 'category-types',
    ])
    {{-- START HTML TO EXCEL INIT --}}
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script>
        function html_table_to_excel(type) {
            var table_core = $("#categoryTypeTable").clone();
            var clonedTable = $("#categoryTypeTable").clone();
            clonedTable.find('[class*="no-export"]').remove();
            clonedTable.find('[class*="d-none"]').remove();
            $("#categoryTypeTable").html(clonedTable.html());
            var data = document.getElementById('categoryTypeTable');

            var file = XLSX.utils.table_to_book(data, {
                sheet: "sheet1"
            });
            XLSX.write(file, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            });
            XLSX.writeFile(file, 'leadFile.' + type);
            $("#categoryTypeTable").html(table_core.html());
        }

        $(document).on('click', '#export_button', function() {
            html_table_to_excel('xlsx');
        });
    </script>
    {{-- END HTML TO EXCEL INIT --}}

    {{-- START RESET BUTTON INIT --}}
    <script>
        $('#reset').click(function() {
            fetchData("{{ route('panel.admin.category-types.index') }}");
            window.history.pushState("", "", "{{ route('panel.admin.category-types.index') }}");
            $('#TableForm').trigger("reset");
            $(document).find('.close.off-canvas').trigger('click');
        });
    </script>
    {{-- END RESET BUTTON INIT --}}

    {{-- START CUSTOME JS INIT --}}
    <script>

    </script>
    <script>
        $(document).ready(function() {
            $('.syncCategories').click(function(event) {
                event.preventDefault();
                // Perform AJAX request to sync categories
                $.ajax({
                    url: '{{ route('panel.admin.category-types.sync-categories') }}',
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        // Handle success response
                        console.log(response); // Log response for debugging
                        alert('Categories synced successfully!');
                        // Optionally, refresh the page or update UI as needed
                        location.reload(); // Example: Reload the page
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                        alert('Error syncing categories: ' + error);
                    }
                });
            });
        });
        </script>

    {{-- END CUSTOME JS INIT --}}

@endpush
