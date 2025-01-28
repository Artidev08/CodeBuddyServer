@extends('layouts.main')
@section('title', ' Features Activation')
@push('head')
    <link rel="stylesheet" href="{{ asset('panel/admin/plugins/mohithg-switchery/dist/switchery.min.css') }}">
@endpush

@section('content')

@php
    $breadcrumb_arr = [['name' => 'Features Activation', 'url' => 'javascript:void(0);', 'class' => 'active']];
@endphp

<div class="container-fluid" id="data_container" style="display: none">
    <div class="page-header">
        <div class="row align-items-end">
            <div class="col-lg-8">
                <div class="page-header-title">
                    <i class="ik ik-grid bg-blue"></i>
                    <div class="d-inline">
                        <h5> @lang('admin/ui.features_activation') </h5>
                        <span> @lang('admin/ui.website_page_heading') </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div>
                    @include('panel.admin.include.breadcrumb')
                </div>
                @include('panel.admin.setting.sitemodal', [
                    'title' => 'How to use',
                    'content' => 'You able to add or remove some functionality from this settings.',
                ])
            </div>
        </div>
    </div>
    <div class="row">
        @foreach (@$groups as $key => $group)
            <div class="col-md-12 mt-3">
                <h5>
                    {{ @$key }}
                </h5>
            </div>
            @foreach (@$group['options'] as $option)
                <div class="col-md-3">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <strong class="text-center ">{{ @$option['name'] }} <i class="ik ik-help-circle text-muted"
                                        data-toggle="tooltip" title="{{ @$option['tooltip'] }}"></i></strong>
                                <div class="text-center">
                                    <input type="checkbox" class="js-switch save" data-key="{{ @$option['key'] }}"
                                        value="1" @if (getSetting(@$option['key']) == 1) checked @endif
                                        data-switchery="true" />
                                </div>
                            </div>

                            @if(isset($option['sub_options']) && count($option['sub_options']))
                            <hr>
                            <div class="mb-3">
                                <strong class="text-muted">
                                    Sub Options:
                                </strong>
                            </div>
                                <ul class="list-unstyled">
                                    @foreach ($option['sub_options'] as $subOption)
                                        <li class="d-flex justify-content-between">
                                            <p class="text-left fw-600">
                                                {{ @$subOption['name'] }}
                                            </p>
                                            <div>
                                                <input type="checkbox" class="js-switch save" data-key="{{ @$subOption['key'] }}"
                                                value="1" @if (getSetting(@$subOption['key']) == 1) checked @endif
                                                data-switchery="true" />
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
</div>
@include('panel.admin.features-activation.include.pass-code')

@endsection

@push('script')
    <script src="{{ asset('panel/admin/plugins/mohithg-switchery/dist/switchery.min.js') }}"></script>
    {{-- START AJAX FORM INIT --}}
    <script>
        $('.save').change(function() {
            var key = $(this).data('key');
            var val = 0;
            if ($(this).prop('checked')) {
                val = 1;
            }
            $.ajax({
                url: "{{ route('panel.admin.setting.features-activation.store') }}",
                dataType: "json",
                method: "post",
                data: {
                    key: key,
                    val: val,
                },
                success: function(json) {
                    callback(json);
                }
            });
        })
    </script>
    {{-- START AJAX FORM INIT --}}

    {{-- START JS HELPERS INIT --}}
    <script>
        var isVerified = false;
        $(document).ready(function(){
            checkEligibility(isVerified);
        });
        function checkEligibility(isVerified){
            if(isVerified == false){
                $('#data_container').hide();
                $('#DelegateAccessModel').modal().show();
            }else{
                $('.close').trigger('click');
                    $('#data_container').show();
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            var accessCodeForm = document.getElementById('accessCodeForm');
            var errorMessage = document.getElementById('errorMessage');
    
            accessCodeForm.addEventListener('submit', function(event) {
                event.preventDefault();
                var accessCodeInput = document.getElementById('accessCodeInput').value;
                if (accessCodeInput === '845693') {
                    isVerified = true;
                    checkEligibility(isVerified);
                    accessCodeInput.value = '';
                    errorMessage.style.display = 'none';
                } else {
                    errorMessage.style.display = 'block';
                }
            });
        });
    </script>    
    {{-- END JS HELPERS INIT --}}
@endpush