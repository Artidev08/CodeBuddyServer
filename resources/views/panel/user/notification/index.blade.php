@extends('layouts.user')

@section('meta_data')
@php
$meta_title = 'Notification | ' . getSetting('app_name');
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
<section class="section mt-60">
    <div class="container mt-lg-3">
        <div class="row">
            <div class="col-lg-12 col-12">
                <div class="rounded shadow customer_card card-notification bg-white">
                    <div class="p-4 border-bottom">
                        <h5 class="mb-0">@lang('user/ui.notification')</h5>
                    </div>
                    <div class="p-4 row">
                        @forelse ($notifications as $notification)
                        <div class="d-flex justify-content-between pb-4">
                            <div>
                                <h6 class="mb-0 d-inline">
                                    @if ($notification->is_read == 0)
                                    <i class="new-update"></i>
                                    <a href="{{ $notification->link }}">
                                        <span class="text-dark">
                                            {{ $loop->iteration }} {{ $notification->title }}
                                        </span>
                                    </a>
                                    <div class="text-dark" style="margin-left: 20px;">
                                        {{ $notification->notification }}
                                    </div>
                                    @else
                                    <i class="new-update"></i>
                                    <a href="{{ $notification->link }}">
                                        <span class="text-muted">
                                            {{ $loop->iteration }} {{ $notification->title }}
                                        </span>
                                    </a>
                                    <div class="text-muted" style="margin-left: 20px;">
                                        {{ $notification->notification }}
                                    </div>

                                    @endif
                                </h6>

                            </div>
                            <div>
                                <a href="{{ route('panel.user.notification.mark-read', $notification->id) }}"
                                    title="Mark as read"><i style="font-size:20px;" class="uil uil-eye"></i> </a>
                            </div>
                        </div>
                        @empty
                        @php
                        $empty_msg = 'No Notifications yet!';
                        @endphp
                        @include('panel.user.include.empty-record', [
                        'title' => 'No Records',
                        'width' => 15,
                        ])
                        @endforelse
                    </div>
                </div>
                <div class="text-center mt-4">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
