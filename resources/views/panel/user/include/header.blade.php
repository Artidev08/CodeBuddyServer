<!-- Navbar Start -->
<style>
    @media print {
        .printButton {
            display: none;
        }
    }
    .notification-icon {
    min-height: 8px;
    min-width: 8px;
    margin-right: 5px;
    background-color: #e43f52 !important;
    border-radius: 50%;
    display: inline-block;
    position: absolute;
    right: -6px;
    top: 3px;
}

.align-middle {
    font-size: 28px !important;
}

.icon {
    color: grey;
}

.profile-dropdown {
    padding: 2px 4px;
}

.notification-popup {
    position: absolute;
    right: 0; /* Adjust to align the popup with the bell */
    top: 306%; /* Adjust to position below the bell */
    width: 300px; /* Updated width for better fit */
    max-height: 400px; /* Limit height and add scrolling */
    background-color: #fff;
    border: 1px solid #ddd;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    z-index: 1010;
    overflow-y: auto; /* Add scrolling if content overflows */
    border-radius: 4px;
    box-sizing: border-box;
}

.notification-bell {
    position: relative;
}

.notification-check-icon {
    font-size: 1.3rem;
    color: #545454;
}

.media-body {
    margin-left: 15px;
    line-height: 1.4; /* Adjusted line-height for better spacing */
}

.media-body-content {
    color: black !important;
    font-weight: 700;
    font-size: 16px;
    margin-top: 0; /* Adjusted for better alignment */
}

.notification-popup h4 {
    font-size: 18px;
    padding: 10px;
    font-weight: 500;
    margin: 0; /* Remove margin to align properly */
    text-align: center; /* Centered header text */
}

.notifications-wrap {
    padding: 10px 15px;
    background-color: #f9f9f9; /* Slightly lighter background */
    border-top: 1px solid #e3e3e3;
    border-bottom: 1px solid #e3e3e3;
    margin-bottom: 10px;
}

.footer {
    text-align: center;
    padding: 10px 0;
}

.footer a {
    color: #007bff;
    text-decoration: none;
}

.footer a:hover {
    text-decoration: underline;
}

.p-5 {
    padding: 20px;
}

.text-muted {
    color: #6c757d;
}

.text-center {
    text-align: center;
}

.my-2 {
    margin: 10px 0;
}

</style>

@php
    $notifications_count = App\Models\Notification::select('user_id', 'id', 'is_read')
        ->where('user_id', auth()->id())
        ->latest()
        ->where('is_read', 0)
        ->limit(10)
        ->count();
    $notifications = App\Models\Notification::where('user_id', auth()->id())->latest()->where('is_read', 0)->get();
@endphp
<nav class="navbar navbar-expand-lg center-nav navbar-light navbar-bg-light navbar-shadow">
    <div class="container flex-lg-row flex-nowrap align-items-center header-h ps-lg-3 ps-2">
        <div class="me-lg-0 me-2">
            <span class="nav-item d-lg-none">
                <button class="hamburger offcanvas-nav-btn"><span></span></button>
            </span>
        </div>

        <div class="navbar-brand w-10">
            <a href="{{ route('index') }}">
                <img src="{{ getBackendLogo(getSetting('app_logo')) }}" alt="logo" class="header-logo" /> </a>
        </div>
        <div>
            <a href="javascript:void(0)" onclick="window.history.back();" title="Back" type="button" id=""
            class="nav-link mr-1"><i class="fa-solid fa-arrow-left fa-lg fs-16" style="margin-left:14px; background-color:white !important;"></i></a>

        </div>



        <div class="navbar-collapse offcanvas offcanvas-nav offcanvas-start bg-white">
            <div class="offcanvas-header d-lg-none">
                <a href=""><img src="{{ asset('panel/user/assets/img/brands/letter-z (1).png') }}"
                        srcset="{{ asset('panel/user/assets/img/brands/letter-z (1).png') }}" alt="logo"
                        class="header-logo" /></a>
                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body ms-lg-auto d-flex flex-column h-100 d-lg-none d-md-block d-bloock">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown dropdown-mega">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">MENU</a>
                        <ul class="dropdown-menu mega-menu mega-menu-dark mega-menu-img">
                            <li class="mega-menu-content mega-menu-scroll">
                                <ul class="row row-cols-1 row-cols-lg-6 gx-0 gx-lg-4 gy-lg-2 list-unstyled">
                                    <li class="col">
                                        <a class="dropdown-item" href="{{ route('panel.user.dashboard.index') }}">
                                            <span class="d-lg-none">Overview</span>
                                        </a>
                                    </li>
                                    <li class="col">
                                        <a class="dropdown-item" href="{{ route('panel.user.order.index') }}">
                                            <span class="d-lg-none">Orders & Returns</span>
                                        </a>
                                    </li>
                                    <li class="col">
                                        <a class="dropdown-item" href="{{ route('panel.user.payout.index') }}">
                                            <span class="d-lg-none">Payout</span>
                                        </a>
                                    </li>
                                    <li class="col">
                                        <a class="dropdown-item" href="{{ route('panel.user.wallet.index') }}">
                                            <span class="d-lg-none">Statement Wallet</span>
                                        </a>
                                    </li>
                                    <li class="col">
                                        <a class="dropdown-item" href="{{ route('panel.user.support-ticket.index') }}">
                                            <span class="d-lg-none">Tickets</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">ACCOUNT</a>
                        <ul class="dropdown-menu">
                            <li class="nav-item"><a class="dropdown-item"
                                    href="{{ route('panel.user.profile.index') }}">Profile</a></li>
                            <li class="nav-item"><a class="dropdown-item"
                                    href="{{ route('panel.user.address.index') }}">Addresses</a></li>
                            <li class="nav-item"><a class="dropdown-item"
                                    href="{{ route('panel.user.saved-account.index') }}">Saved Banks</a></li>
                            <li class="nav-item"><a class="dropdown-item"
                                    href="{{ route('panel.user.verify.index') }}">Verifications</a></li>
                            <li class="nav-item"><a class="dropdown-item"
                                    href="{{ route('panel.user.security.index') }}">Security</a></li>
                            <li class="nav-item"><a class="dropdown-item"
                                    href="{{ route('panel.user.delegate-access.index') }}">Delegate Access</a></li>

                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">LEGAL</a>
                        <ul class="dropdown-menu">

                            @php
                                $usefull_links = App\Models\WebsitePage::select('title', 'slug')
                                    ->whereStatus(1)
                                    ->latest()
                                    ->limit(10)
                                    ->get();
                            @endphp
                            @foreach ($usefull_links as $item)
                                <li class="nav-item">
                                    <a class="dropdown-item text-dark" href="{{ route('page.slug', $item->slug) }}"
                                        target="_blank">
                                        {{ $item['title'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
                <!-- /.navbar-nav -->
                <div class="offcanvas-footer d-lg-none">
                    <div>
                        <a href="mailto:first.last@email.com" class="link-inverse text-dark">info@email.com</a>
                        <div>
                            <a href="#">+91 9869685204</a>
                        </div>
                        <hr class="m-2">
                        <nav class="nav social social-dark mt-3">
                            <a href="#"><i class="uil uil-twitter"></i></a>
                            <a href="#"><i class="uil uil-facebook-f"></i></a>
                            <a href="#"><i class="uil uil-dribbble"></i></a>
                            <a href="#"><i class="uil uil-instagram"></i></a>
                            <a href="#"><i class="uil uil-youtube"></i></a>
                        </nav>
                        <!-- /.social -->
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-other w-100 d-flex ms-auto">
            <ul class="navbar-nav flex-row align-items-center ms-auto">
                @php
                    $user = auth()->user();
                @endphp
                {{-- notification --}}
                <li class="nav-item notification-bell">
                    <a href="javascript:void(0);" title="Notification" class="nav-link" onclick="togglePopup(event)">
                        <i class="fa-regular fa-bell fa-lg fs-16"></i>
                        @if ($notifications->count() > 0)
                            <span class="notification-icon ml-2"></span>
                        @endif
                    </a>
                    <!-- Popup -->
                    <div class="notification-popup" id="notificationPopup" style="display: none;">
                        <h4 class="header text-center">Notifications</h4>
                        @if ($notifications->count() > 0)
                            <div class="notifications-wrap">
                                @foreach ($notifications as $item)
                                    <a href="{{ $item->link }}" class="media">
                                        <span class="d-flex">
                                            <i class="ik ik-check"></i>
                                        </span>
                                        <span class="media-body">
                                            <span class="media-body-content fs-12">{{ $item->title }}</span><br>
                                            <span class="text-dark fs-12" style="display: ruby-text; margin-left: 15px;">{{ $item->notification }}</span>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                            <div class="footer text-center mt-2 text-dark">
                                <a href="{{ route('panel.user.notification.index') }}"class="table-link">See all Notifications</a>
                            </div>
                        @else
                            <div class="p-5 text-muted text-center">
                                <img src="{{ asset('/site/assets/img/no-notification-icon-1-01.png') }}" alt="img" width="25px" height="25px">
                                <h6 class="my-2">No Notifications Yet!</h6>
                            </div>
                        @endif
                    </div>
                </li>



                <li class="nav-item dropdown language-select text-uppercase">
                    <a class="nav-link dropdown-item dropdown-toggle" href="#" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded-circle w-10 mb-1 head-avatar"
                            src="{{ $user && $user->avatar ? $user->avatar : asset('panel/admin/default/default-avatar.png') }}" />
                    </a>

                    <ul class="dropdown-menu user-profile-dropdown">
                        @auth

                        <li class="nav-item">
                            <a class="dropdown-item" href="{{ route('panel.user.profile.index',['active' => 'security']) }}"><i
                                    class="uil uil-user-circle"></i>
                                @lang('user/ui.user_profile')</a>
                        </li>
                        @if (auth()->user() && session()->has('admin_user_id') && session()->has('temp_user_id'))
                        <li class="nav-item"><a class="dropdown-item"
                                href="{{ route('panel.user.dashboard.logout-as') }}"><i
                                    class="uil uil-arrow-circle-left fw-700"></i>
                                    @lang('user/ui.re_login_as_admin')</a></li>
                        @endif


                            <hr class="my-0 p-0 px-2">


                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <li class="nav-item">
                                <a class="dropdown-item fw-semibold text-danger" href=""
                                    onClick="event.preventDefault();this.closest('form').submit();">
                                    <i class="uil uil-signout"></i>
                                    @lang('user/ui.logout')
                                </a>
                            </li>
                        </form>

                        @else
                            <li class="nav-item"><a href="{{ route('/login') }}" class="dropdown-item">Login</a></li>
                            @endif
                        </ul>
                    </li>

                </ul>
            </div>

        </div>
    </nav>


    <script>
        function togglePopup(event) {
            event.preventDefault(); // Prevent the default link action

            // Select the popup
            const popup = document.getElementById('notificationPopup');
            if (popup) {
                // Toggle visibility
                if (popup.style.display === 'none' || popup.style.display === '') {
                    popup.style.display = 'block';
                } else {
                    popup.style.display = 'none';
                }
            } else {
                console.error('Popup element not found');
            }
        }
    </script>
