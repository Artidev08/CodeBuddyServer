@extends('layouts.main')
@section('title', @$label)
@section('content')
@php
    $breadcrumb_arr = [['name' => $label, 'url' => 'javascript:void(0);', 'class' => 'active']];
@endphp
@push('head')
 {{-- INITIALIZE SHIMMER & INIT LOAD --}}
 <script>
    window.onload = function() {
        $('#ajax-container').show();
        fetchData("{{ route('panel.admin.locations.country') }}");
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
                        <h5> @lang(@$label ?? '--') </h5>
                        <span> @lang('admin/ui.list_of') {{ @$label ?? '--' }}</span>
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
                    <h3> @lang('admin/ui.locations') </h3>
                    @if ($permissions->contains('add_location'))
                        <a href="{{ route('panel.admin.locations.country.create') }}"
                            class="btn btn-sm btn-outline-primary mr-2" title="Add New Country"><i class="fa fa-plus"
                                aria-hidden="true"></i> @lang('admin/ui.add')  </a>
                    @endif
                </div>
                <div id="ajax-container" style="display: none;">
                    @include('panel.admin.locations.loads.country')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script src="{{ asset('panel/admin/js/index-page.js') }}"></script>
      {{-- START CUSTOM JS INIT --}}
      <script>
        $('#reset').click(function() {
            fetchData("{{ route('panel.admin.locations.country') }}");
            window.history.pushState("", "", "{{ route('panel.admin.locations.country') }}");
            $('#TableForm').trigger("reset");
            $(document).find('.close.off-canvas').trigger('click');
        });
    </script>
    {{-- END CUSTOM JS INIT --}}
@endpush
