<style>
    .popup-content {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background-color: white;
        border: 1px solid #ddd;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 10;
        width: 200px;
        border-radius: 0.4rem;
    }

    .nav-item:hover .popup-content {
        display: block;
    }

    .popup-content p {
        padding: 7px 15px;
        border-bottom: 1px solid rgb(224, 224, 224);
        cursor: pointer;
        font-weight: 600;
    }

    .popup-content p:hover {
        background-color: rgb(233, 233, 233);
        border-radius: 0.4rem;
    }

    .header-btn {
        background-color: #323130;
        border-radius: 10px !important;
    }

    .mobile-sidebar {
        display: none;
        position: fixed;
        top: 0;
        left: -270px;
        width: 270px;
        height: 100%;
        background-color: #333;
        overflow-x: hidden;
        transition: 0.3s;
        z-index: 9999;
    }

    .mobile-sidebar a {
        padding: 10px 15px;
        text-decoration: none;
        font-size: 18px;
        color: #fff;
        display: block;
        transition: 0.3s;
    }

    .hamburger-icon {
        display: none;
        font-size: 30px;
        cursor: pointer;
        color: #333;
    }

    .signin-btn {}


    @media (max-width: 600px) {
        .navbar-expand-lg .navbar-brand {
            padding: 0 10px;
        }

        .navbar-brand h2 {
            text-align: center;
        }

        .hamburger-icon {
            display: block;
        }

        .mobile-sidebar {
            display: block;
        }

        .navbar-collapse {
            display: none !important;
        }

        .mobile-login-btn {
            position: absolute;
            bottom: 1rem;
            width: 100%;
        }
    }

    .open-sidebar {
        left: 0;
    }
</style>
@php
    $contentsUpcoming = \App\Models\Event::where('date', '>', now())->get();
    $contentsTopFestivals = \App\Models\Event::where('occasion_id', 1)->take(10)->get();
@endphp
<header class="wrapper bg-light">
    <nav class="navbar navbar-expand-lg classic transparent position-absolute navbar-light shadow p-0">
        <div class="container flex-lg-row flex-nowrap align-items-center px-1 py-0">
            <div class="navbar-brand w-100">
                <a href="{{ url('/') }}">
                    <h2 class="text-dark fw-bold px-1 py-2 rounded m-0"> GoodGreets<span class="text-muted">.com</span>
                    </h2>

                    {{-- <img src="{{ getSetting('app_logo') }}" srcset="{{ getBackendLogo(getSetting('app_logo')) }}"
                        alt="GOOD GREETS" height="50px" width="50px" /> --}}
                </a>
            </div>

            <div class="hamburger-icon position-absolute px-2" onclick="toggleSidebar()">&#9776;</div>
            <!-- Offcanvas Slider from the Right -->
            <div class="navbar-collapse offcanvas offcanvas-nav offcanvas-end" id="offcanvas-nav">
                <div class="offcanvas-header d-lg-none" style="padding: 0px 1.5rem;">
                    <div class="navbar-brand w-100">
                        <a href="{{ url('/') }}">
                            <img src="{{ getSetting('app_logo') }}"
                                srcset="{{ getBackendLogo(getSetting('app_logo')) }}" alt="" height="50px"
                                width="50px" />
                        </a>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body ms-lg-auto d-flex flex-column h-100">
                    <ul class="navbar-nav">
                        {{-- dropdowns --}}
                        {{-- <li class="nav-item position-relative">
                            <a class="nav-link" href="#">Upcoming Events
                                <span><i class="uil uil-angle-down"></i></span>
                            </a>
                            <!-- Popup -->
                            <div class="popup-content">
                                @if (@$contentsUpcoming->count() > 0)
                                    @foreach (@$contentsUpcoming as $contentUpcoming)
                                        @if (@$contentUpcoming->getContent->count() > 0)
                                            <a
                                                href="{{ route('upcomingEvent', ['event' => convertUpperToLower(@$contentUpcoming->name), 'event_id' => @encrypt($contentUpcoming->id)]) }}">
                                                <p class="mb-0 text-black">{{ @$contentUpcoming->name }}</p>
                                            </a>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </li>
                        <li class="nav-item position-relative">
                            <a class="nav-link" href="#">Top Festivals
                                <span><i class="uil uil-angle-down"></i></span>
                            </a>
                            <!-- Popup -->
                            <div class="popup-content">
                                @if (@$contentsTopFestivals->count() > 0)
                                    @foreach (@$contentsTopFestivals as $contentsTopFestival)
                                        @if (@$contentsTopFestival->getContent->count() > 0)
                                            <a
                                                href="{{ route('topFestival', [convertUpperToLower(@$contentsTopFestival->name), @encrypt($contentsTopFestival->id)]) }}">
                                                <p class="mb-0 text-black">{{ $contentsTopFestival->name }}</p>
                                            </a>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                        </li> --}}
                        @php
                            if (auth()->check()) {
                                if (auth()->user()->hasRole('admin')) {
                                    $route = route('panel.admin.dashboard.index');
                                } elseif (auth()->user()->hasRole('super_admin')) {
                                    $route = route('panel.admin.dashboard.index');
                                } elseif (auth()->user()->hasRole('member')) {
                                    $route = route('panel.member.dashboard.index');
                                } else {
                                    $route = route('panel.user.dashboard.index');
                                }
                            }
                        @endphp
                        @auth
                            <li class="nav-item pt-3 pb-2">
                                <a href="{{ $route }}"
                                    class="header-btn btn btn-sm btn-primary rounded-pill py-1">Dashboard </a>
                            </li>
                        @else
                            <li class="nav-item pt-3 pb-2">
                                <a href="{{ route('login', 'user') }}"
                                    class="header-btn btn btn-sm btn-primary rounded-pill py-1">Sign
                                    In</a>
                            </li>
                        @endauth
                    </ul>
                    <!-- /.navbar-nav -->
                    <div class="offcanvas-footer d-lg-none">
                        <div>
                            <a href="mailto:{{ $app_settings['app_email'] ?? '' }}"
                                class="link-inverse">{{ $app_settings['app_email'] ?? '' }}</a>
                            <br /> <a
                                href="tel:{{ $app_settings['app_contact'] ?? '' }}">{{ $app_settings['app_contact'] ?? '' }}</a><br />
                            <nav class="nav social social-white mt-4">
                                <a href="{{ $app_settings['facebook_link'] ?? '' }}"><i
                                        class="uil uil-facebook-f"></i></a>
                                <a href="{{ $app_settings['linkedin_link'] ?? '' }}"><i
                                        class="uil uil-linkedin"></i></a>
                                <a href="{{ $app_settings['instagram_link'] ?? '' }}"><i
                                        class="uil uil-instagram"></i></a>
                                <a href="{{ $app_settings['youtube_link'] ?? '' }}"><i class="uil uil-youtube"></i></a>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile sidebar -->
    <div id="mobileSidebar" class="mobile-sidebar">
        <div class="d-flex justify-content-between py-0">
            <a href="{{ url('/') }}">
                <h2 class="fw-bold fs-20 rounded text-muted m-0"> GoodGreets<span class="text-muted">.com</span>
                </h2>
            </a>
            <a href="javascript:void(0)" class="closebtn fs-34 py-0" onclick="toggleSidebar()">&times;</a>
        </div>
        {{-- upcoming events --}}
        {{-- <div class="mt-3">
            <a href="#" id="toggle-upcoming" class="d-flex justify-content-between">
                <span class="fs-20">Upcoming Events</span>
                <span id="icon-toggle"><i class="uil uil-angle-down"></i></span>
            </a>
            <div id="upcoming-events-content" style="display: none;">
                @if (@$contentsUpcoming->count() > 0)
                    @foreach (@$contentsUpcoming as $contentUpcoming)
                        @if (@$contentUpcoming->getContent->count() > 0)
                            <a
                                href="{{ route('upcomingEvent', ['event' => convertUpperToLower(@$contentUpcoming->name), 'event_id' => @encrypt($contentUpcoming->id)]) }}">
                                <p class="mb-0">{{ @$contentUpcoming->name }}</p>
                            </a>
                        @endif
                    @endforeach
                @endif
            </div>
        </div> --}}
        {{-- top festivals --}}
        {{-- <div>
            <a href="#" id="toggle-top-festivals" class="d-flex justify-content-between">
                <span class="fs-20">Top Festivals</span>
                <span id="icon-toggle-festivals"><i class="uil uil-angle-down"></i></span>
            </a>
            <div id="top-festivals-content" style="display: none;">
                @if (@$contentsTopFestivals->count() > 0)
                    @foreach (@$contentsTopFestivals as $contentsTopFestival)
                        @if (@$contentsTopFestival->getContent->count() > 0)
                            <a
                                href="{{ route('topFestival', [convertUpperToLower(@$contentsTopFestival->name), @encrypt($contentsTopFestival->id)]) }}">
                                <p class="mb-0">{{ $contentsTopFestival->name }}</p>
                            </a>
                        @endif
                    @endforeach
                @endif
            </div>
        </div> --}}
        <div class="mt-3 mobile-login-btn">
            @auth
                <a href="{{ $route }}" class="text-center">
                    <span class="border rounded-1 py-1 px-5 fs-22">Dashboard</span>
                </a>
            @else
                <a href="{{ route('login', 'user') }}" class="text-center">
                    <span class="border rounded-1 py-1 px-5 fs-22">Sign In</span>
                </a>
            @endauth
        </div>
        <a href="mailto:{{ $app_settings['app_email'] ?? '' }}">{{ $app_settings['app_email'] ?? '' }}</a>
        <a href="tel:{{ $app_settings['app_contact'] ?? '' }}">{{ $app_settings['app_contact'] ?? '' }}</a>
    </div>
    <!-- /.navbar -->
    <div class="offcanvas offcanvas-end text-inverse" id="offcanvas-info" data-bs-scroll="true">
        <div class="offcanvas-header">
            <h3 class="text-white fs-30 mb-0">{{ $app_settings['app_name'] ?? '' }}</h3>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body pb-6">
            <div class="widget mb-8">
                <p>
                    {{ $app_settings['site_motto'] ?? '' }}
                </p>
            </div>
            <!-- /.widget -->
            <div class="widget mb-8">
                <h4 class="widget-title text-white mb-3">Contact Info</h4>
                <address style="font-size: 15px">{{ $app_settings['app_address'] ?? '' }}</address>
                <a href="mailto:hq@defenzelite.com">{{ getSetting('app_email') }}</a><br />
                <a href="tel:{{ $app_settings['app_contact'] ?? '' }}">{{ $app_settings['app_contact'] ?? '' }}</a>
            </div>
            <!-- /.widget -->
            <div class="widget mb-8">
                <h4 class="widget-title text-white mb-3">Learn More</h4>
                <ul class="list-unstyled" style="font-size: 15px">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ route('about') }}">About</a></li>
                    {{-- <li><a href="{{ route('product') }}">Products</a></li> --}}
                    {{-- <li><a href="{{ route('blogs') }}">Blog</a></li> --}}
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>
            <!-- /.widget -->
            <div class="widget">
                <h4 class="widget-title text-white mb-3">Follow Us</h4>
                <nav class="nav social social-white">
                    <a href="{{ $app_settings['twitter_link'] ?? '' }}"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="{{ $app_settings['facebook_link'] ?? '' }}"><i class="uil uil-facebook-f"></i></a>
                    <a href="{{ $app_settings['linkedin_link'] ?? '' }}"><i class="uil uil-linkedin"></i></a>
                    <a href="{{ $app_settings['instagram_link'] ?? '' }}"><i class="uil uil-instagram"></i></a>
                    <a href="{{ $app_settings['youtube_link'] ?? '' }}"><i class="uil uil-youtube"></i></a>
                </nav>
                <!-- /.social -->
            </div>
            <!-- /.widget -->
        </div>
        <!-- /.offcanvas-body -->
    </div>
    <!-- /.offcanvas -->
    <div class="offcanvas offcanvas-top bg-light" id="offcanvas-search" data-bs-scroll="true">
        <div class="container d-flex flex-row py-6">
            <form action="#" class="search-form w-100">
                <input id="search-form" name="search" type="text" class="form-control"
                    placeholder="Type keyword and hit enter">
            </form>
            <!-- /.search-form -->
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <!-- /.container -->
    </div>
    <!-- /.offcanvas -->
</header>

<script>
    function toggleSidebar() {
        document.getElementById('mobileSidebar').classList.toggle('open-sidebar');
    }
</script>


<script>
    function toggleSection(toggleId, contentId, iconId) {
        document.getElementById(toggleId).addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default link behavior

            const content = document.getElementById(contentId);
            const icon = document.getElementById(iconId).children[0]; // Get the icon element

            if (content.style.display === "none" || content.style.display === "") {
                content.style.display = "block"; // Show the content
                icon.classList.remove('uil-angle-down'); // Remove the down icon class
                icon.classList.add('uil-angle-up'); // Add the up icon class
            } else {
                content.style.display = "none"; // Hide the content
                icon.classList.remove('uil-angle-up'); // Remove the up icon class
                icon.classList.add('uil-angle-down'); // Add the down icon class
            }
        });
    }

    // Initialize toggles
    toggleSection('toggle-upcoming', 'upcoming-events-content', 'icon-toggle');
    toggleSection('toggle-top-festivals', 'top-festivals-content', 'icon-toggle-festivals');
</script>
