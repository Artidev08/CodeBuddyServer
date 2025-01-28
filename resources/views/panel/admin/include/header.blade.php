@php
    $notifications_count = App\Models\Notification::select('user_id', 'id', 'is_read')
        ->where('user_id', auth()->id())
        ->latest()
        ->where('is_read', 0)
        ->limit(10)
        ->count();
    $notifications = App\Models\Notification::where('user_id', auth()->id())
        ->latest()
        ->where('is_read', 0)
        ->limit(5)
        ->get();
@endphp
<header class="header-top" header-theme="light">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <div class="top-menu d-flex align-items-center">
                <button type="button" class="btn-icon mobile-nav-toggle d-lg-none"><span></span></button>

                <a href="javascript:void(0)" onclick="window.history.back();" title="Back" type="button" id=""
                    class="nav-link bg-gray mr-1"><i class="ik ik-arrow-left"></i></a>

                <button type="button" id="navbar-fullscreen" title="Full Screen" class="nav-link"><i
                        class="ik ik-maximize"></i></button>
                <a href="{{ url('/') }}" type="button" id="" title="Go to Home"
                    class="nav-link bg-gray ml-1"><i class="ik ik-home"></i></a>
                @if (getSetting('broadcast_activation', @$setting))
                    @if (Route::is('panel.admin.dashboard.index'))
                        <button type="button" class="nav-link bg-gray ml-1" data-toggle="modal"
                            data-target="#addBrodcast">
                            <i class="ik ik ik-radio" title="Broadcast"></i>
                        </button>
                    @endif
                @endif
            </div>
            <div class="top-menu d-flex align-items-center">
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="notiDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ik ik-bell"></i>
                        @if ($notifications_count > 0)
                            <span class="badge bg-danger"
                                style="line-height: 13px;">{{ $notifications_count > 9 ? '9+' : $notifications->count() }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right notification-dropdown" aria-labelledby="notiDropdown">
                        <h4 class="header">Notifications</h4>
                        @if ($notifications->count() > 0)
                            <div class="notifications-wrap">
                                @foreach ($notifications as $item)
                                    <a href="{{ $item->link }}" class="media">
                                        <span class="d-flex">
                                            <i class="ik ik-check"></i>
                                        </span>
                                        <span class="media-body">
                                            <span
                                                class="heading-font-family media-heading">{{ $item->title }}</span><br>
                                            <span class="media-content">{{ $item->notification }}</span>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                            <div class="footer"><a href="{{ route('panel.admin.notifications.index') }}">See all
                                    Notifications</a>
                            </div>
                        @else
                            <div class="p-5 text-muted text-center">
                                <img src="{{ asset('/site/assets/img/no-notification-icon-1-01.png') }}" alt="img"
                                    width="25px" height="25px">
                                <h6 class="my-10">No Notification Yet!</h6>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="dropdown">

                    <a class="dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <img class="avatar"
                            src="{{ auth()->user() && auth()->user()->avatar ? auth()->user()->avatar : asset('backend/default/default-avatar.png') }}"
                            style="object-fit: cover; width: 35px; height: 35px" alt="">
                        <span class="user-name font-weight-bolder"
                            style="top: -0.8rem;position: relative;margin-left: 8px;">{{ auth()->user()->full_name }}
                            <span class="text-muted"
                                style="font-size: 10px;position: absolute;top: 16px;left: 0px;">{{ auth()->user()->role_name }}</span>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="{{ route('panel.admin.profile.index') }}"><i
                                class="ik ik-user dropdown-icon"></i> Profile</a>
                        @if($authRole == 'Admin')
                            <a class="dropdown-item" href="{{ route('panel.admin.cron.system-diagnosis') }}"><i class="ik ik-crosshair dropdown-icon"></i>
                                {{ __('System Diagnosis') }}
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="" onClick="event.preventDefault();this.closest('form').submit();"
                                class="dropdown-item text-danger fw-700"><i
                                    class="ik ik-power dropdown-icon text-danger"></i>
                                @lang('Log out')
                            </a>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</header>
@include('panel.admin.include.modal.broadcast')
