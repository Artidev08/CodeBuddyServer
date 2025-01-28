@extends('layouts.main')
@section('title', $title)
@section('content')
    @php
        /**
         * Scenario Agent
         *
         * @category Hq.ai
         *
         * @ref zCURD
         * @author Defenzelite <hq@defenzelite.com>
         * @license https://www.defenzelite.com Defenzelite Private Limited
         * @version <Hq.ai: 1.1.0>
         * @link https://www.defenzelite.com
         */
        $breadcrumb_arr = [['name' => 'Sync ' . $title, 'url' => 'javascript:void(0);', 'class' => '']];
    @endphp
    <!-- push external head elements to head -->
    @push('head')
        <link rel="stylesheet" href="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.css') }}">
        <!-- themekit admin template asstes -->

        <style>
            .error {
                color: red;
            }

            .bootstrap-tagsinput {
                width: 100%;
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-scenarion-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>Sync {{ $title }}</h5>
                            <span>Sync a record for {{ $title }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb')
                </div>
            </div>
        </div>
        <form class="row ajaxForm"action="{{ route($route . '.sync-files', $item->id) }}" method="post"
            enctype="multipart/form-data" id="ItemForm">
            @csrf
            <input type="hidden" name="sync_type" id="sync_type" value="{{ $sync_type }}">
            <div class="col-md-12">
                <!-- start message area-->
                @include('panel.admin.include.message')
                <!-- end message area-->
            </div>
            <div class="col-md-12 mx-auto">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3>{{ $title }} Details</h3>
                        <div>
                            <a href="{{ route($route . '.sync', secureToken($item->id)) }}?sync_type=views" title="Sync Views"
                                class="btn btn-primary @if($sync_type == 'views') active @endif">
                                <i class="ik ik-settings mr-2"></i> Sync Views
                            </a>
                            <a href="{{ route($route . '.sync', secureToken($item->id)) }}" title="Sync"
                                class="btn btn-primary @if($sync_type == 'controllers') active @endif">
                                <i class="ik ik-settings mr-2"></i> Sync Controllers
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>
                                        <input type="checkbox" id="select_all">
                                        <strong>Select All ({{count($files)}})</strong>
                                    </label>
                                </div>
                            </div>

                            @foreach ($files as $file)
                                @php
                                    $currentFileKey = $file['file_name'] . '|' . $file['path_name'];
                                    $isChecked = in_array($currentFileKey, $existingFiles);
                                @endphp
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" class="file-checkbox" name="files[]" {{ $isChecked ? 'checked' : '' }} value="{{ $currentFileKey }}" data-path="{{ $file['path_name'] }}">
                                            <strong>{{ $file['file_name'] }}</strong>
                                        </label>
                                        <span class="text-muted">({{ $file['path_name'] }})</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary floating-btn ajax-btn">Save & Update</button>
        </form>
    </div>
    </div>
    <!-- push external js -->
    @push('script')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.js"></script>
        <script src="{{ asset('admin/plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>

        {{-- START AJAX FORM INIT --}}

        <script>
            // STORE DATA USING AJAX
            $('.ajaxForm').on('submit', function(e) {
                e.preventDefault();
                var route = $(this).attr('action');
                var method = $(this).attr('method');
                var data = new FormData(this);
                var sync_type = $('#sync_type').val();
                if(sync_type == 'controllers'){
                    var redirectUrl = "{{ url('/api/local-code-optimization/') }}/"+"{{ $item->id }}/progress";
                }else{
                    var redirectUrl = "{{ url('/api/views-code-optimization/') }}/"+"{{ $item->id }}/progress";
                }
                var response = postData(method, route, 'json', data, null, null, toast = 1, async = true, redirectUrl);
            })
        </script>
        <script>
            $(document).ready(function () {
                // Handle "Select All" checkbox functionality
                $('#select_all').on('change', function () {
                    $('.file-checkbox').prop('checked', $(this).prop('checked'));
                });

                // If any individual checkbox is unchecked, uncheck the "Select All" checkbox
                $('.file-checkbox').on('change', function () {
                    if (!$(this).prop('checked')) {
                        $('#select_all').prop('checked', false);
                    }
                    // Check "Select All" if all checkboxes are checked
                    if ($('.file-checkbox:checked').length === $('.file-checkbox').length) {
                        $('#select_all').prop('checked', true);
                    }
                });
            });
        </script>
        {{-- END AJAX FORM INIT --}}
    @endpush
@endsection
