<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="{{ asset('panel/admin/plugins/select2/dist/js/select2.min.js') }}"></script>

<!-- Main Js -->
<script src="{{ asset('site/assets/js/plugins.js') }}"></script>
<script src="{{ asset('site/assets/js/theme.js') }}"></script>
<script src="{{ asset('panel/admin/plugins/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

{{-- JQUERY CONFIRM CDN --}}
<script src="{{ asset('site/assets/js/jquery-confirm.min.js') }}"></script>

{{-- COUNTRYCODE SELECTOR INIT --}}
<script src="{{ asset('site/assets/js/country-code/intl-tel-input.js') }}"></script>
<script src="{{ asset('site/assets/js/country-code/utils.js') }}"></script>
   {{-- End COUNTRYCODE SELECTOR INIT --}}

{{-- Font Awesome CDN --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"
    integrity="sha512-GWzVrcGlo0TxTRvz9ttioyYJ+Wwk9Ck0G81D+eO63BaqHaJ3YZX9wuqjwgfcV/MrB2PhaVX9DkYVhbFpStnqpQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

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

