@extends('layouts.user')

@section('meta_data')
@php
$meta_title = __('user/ui.home') . ' | ' . getSetting('app_name');
$meta_description = '' ?? getSetting('seo_meta_description');
$meta_keywords = '' ?? getSetting('seo_meta_keywords');
$meta_motto = '' ?? getSetting('site_motto');
$meta_abstract = '' ?? getSetting('site_motto');
$meta_author_name = '' ?? 'Defenzelite';
$meta_author_email = '' ?? 'support@defenzelite.com';
$meta_reply_to = '' ?? getSetting('app_email');
$meta_img = ' ';
$customer = 1;
@endphp
@endsection

@section('content')
@push('style')
<style>
    .edit-btn {
        font-size: 12px !important;
        line-height: 8px !important;
    }
</style>
@endpush
<div class="shimmer-content">
    @include('panel.user.overview.include.shimmer')
</div>
<div class="user-full-profile">
    <div class="person-background text-center">
        <div class="row">
            <div class="col-lg-8 col-md-6 col-7">
                <div class="d-flex justify-content-start">
                    <img class="rounded-circle me-lg-3 me-md-3 me-1"
                        src="{{ auth()->user()->avatar ? auth()->user()->avatar : asset('panel/admin/default/default-avatar.png') }}"
                        class="rounded-circle" width="150" style="object-fit: cover; width: 80px; height: 80px" />

                    <div class="text-start">
                        <span
                            class="badge  {{ auth()->user()->is_verified == 1 ? 'bg-pale-leaf text-leaf' : 'bg-warning' }}  rounded-pill m-0">{{
                            auth()->user()->is_verified == 1 ? 'Verified' : 'Not Verified' }}
                        </span>

                        <div class="mt-2 lh-10 w-121">
                            <span class="p-0 m-0">
                                {{ getGreetingBasedOnTime() }}
                            </span>
                            <h6 class="mb-0">
                                {{ ucfirst(auth()->user()->first_name) }}
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 col-5 text-end mt-lg-5 mt-1">
                <a href="{{ route('panel.user.profile.index',['active' => 'security']) }}" type="button"
                    class="btn btn-outline-primary rounded-pill"> @lang('user/ui.edit_profile') </a>
            </div>
        </div>
    </div>
</div>


<div class="row dashboard-content d-none">
    @include('.panel.user.overview.include.dashboard')
</div>

@endsection
@push('script')
{{-- START JS HELPERS INIT --}}
<script>
    $(document).ready(function() {
            $('.dashboard-content').removeClass('d-none');
            $('.shimmer-content').addClass('d-none');
    });
</script>
{{-- END JS HELPERS INIT --}}
@endpush