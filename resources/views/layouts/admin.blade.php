@php
    $role = 'admin';
    $root_directory = "panel/$role/";
    $root_directory_path = "panel.$role";
    
@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>
        @yield('title', '') | {{ getSetting('app_name') }}
    </title>
    <!-- initiate head with meta tags, css and script -->
    @include($root_directory_path . '.include.head')
</head>

<body id="app" class=" @if(@isset($mini_sidebar) && $mini_sidebar) sidebar-mini @endif">
    <div class="wrapper">
        @include($root_directory_path . '.include.header')

        <div class="page-wrap">
            <!-- initiate sidebar-->
            @if (!@isset($noSidebar))
                @include($root_directory_path . '.include.sidebar')
            @endif

            <div class="main-content">
                @include($root_directory_path . '.include.logged-in-as')

                <!-- yeild contents here -->
                @yield('content')
            </div>

            <!-- initiate footer section-->
            @include($root_directory_path . '.include.footer')
        </div>
    </div>

    <!-- initiate modal menu section-->
    @include($root_directory_path . '.include.modalmenu')

    <!-- initiate script-->
    @include($root_directory_path . '.include.script')

        <!-- JavaScript to handle the keyboard shortcut -->
        <script>
            document.addEventListener('keydown', function(event) {
                if (event.altKey && event.shiftKey && event.key.toLowerCase() === 'f') {
                    // console.log('ALT+SHIFT+F pressed');
                    window.location.href = "{{ route('panel.admin.setting.features-activation') }}";
                }
            });
        </script>
</body>

</html>
