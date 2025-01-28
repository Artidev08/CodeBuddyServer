<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="{{ asset('panel/admin/plugins/select2/dist/js/select2.min.js') }}"></script>

<!-- Main Js -->
<script src="{{ asset('site/assets/js/plugins.js') }}"></script>
<script src="{{ asset('site/assets/js/theme.js') }}"></script>
{{-- <script src="{{asset('panel/admin/plugins/jquery-toast-plugin/dist/jquery.toast.min.js')}}"></script> --}}

{{-- JQUERY CONFIRM CDN --}}
<script src="{{asset('panel/user/assets/plugins/jquery-confirm-3.3.2/jquery-confirm.min.js')}}"></script>

   {{-- COUNTRYCODE SELECTOR INIT --}}
   <script src="{{asset('panel/user/assets/plugins/country-code/intl-tel-input.js')}}"></script>
   <script src="{{ asset('panel/user/assets/plugins/country-code/utils.js') }}"></script>
   <script src="{{ asset('panel/user/assets/plugins/form/ajaxForm.js') }}"></script>
   <script src="{{ asset('panel/user/assets/plugins/form/index-page.js') }}"></script>
   {{-- COUNTRYCODE SELECTOR INIT --}}


{{-- Font Awesome CDN --}}
<script src="{{ asset('panel/user/assets/plugins/fontawesome-6.5.1/all.min.js') }}"></script>

@if (session('success'))
    <script>
        $.toast({
            heading: 'SUCCESS',
            text: "{{ session('success') }}",
            showHideTransition: 'slide',
            icon: 'success',
            loaderBg: '#f96868',
            position: 'top-right'
        });
    </script>
@endif

@if (session('error'))
    <script>
        $.toast({
            heading: 'ERROR',
            text: "{{ session('error') }}",
            showHideTransition: 'slide',
            icon: 'error',
            loaderBg: '#f2a654',
            position: 'top-right'
        });
    </script>
@endif

<script>
    $(document).on('click', '.delete-item', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let msg = $(this).data('msg') ?? "You won't be able to revert back!";
        $.confirm({

            draggable: true,
            title: 'Are You Sure!',
            content: msg,
            type: 'red',
            typeAnimated: true,
            buttons: {
                tryAgain: {
                    text: 'Delete',
                    btnClass: 'btn-red',
                    action: function() {
                        window.location.href = url;
                    }
                },
                close: function() {}
            }
        });
    });

    $('.uil-times').hide();
    let mobnav = 0;
    $('.toggleBtn').on('click', function() {
        $('.toggle-area').toggle(200);
    });
    $('#toggle-submenu').on('click', function() {
        $('#show-submenu').toggle(200);
    });
</script>

@if (getSetting('custom_header_script') != 0)
    <script src="{!! getSetting('custom_header_script') !!}"></script>
@endif

@if (getSetting('custom_footer_script') != 0)
    <script src="{!! getSetting('custom_footer_script') !!}"></script>
@endif
{{-- inspect block --}}
@if(env('APP_ENV') == "production")
    <script>
        // Disabled right click and copy
        document.addEventListener('contextmenu', function (e) {
        e.preventDefault(); // Disable right-click context menu
        });
        document.addEventListener('keydown', function (e) {
        // Disable F12 key (Developer Tools shortcut)
        if (e.key === 'F12') {
            e.preventDefault();
        }
        });

        document.addEventListener('keydown', function (e) {
        // Disable copy shortcuts (Ctrl+C, Command+C)
        if ((e.ctrlKey || e.metaKey) && e.key === 'c') {
            e.preventDefault();
        }
        });
    </script>
@endif

<script>
    $(document).on('click', '.off-canvas', function (e) {
        e.stopPropagation();
        var type = $(this).data('type');
        $('.side-slide').animate({
            right: type == 'close' ? "-100%" : "0px"
        }, 200);
    });
    $(document).on('.close.off-canvas', function () {
        var type = $(this).data('type');
        $('.side-slide').animate({
            right: type == 'close' ? "-100%" : "0px"
        }, 200);
    });
</script>
