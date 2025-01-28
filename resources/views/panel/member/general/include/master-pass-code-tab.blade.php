<div class="form-group row">
    @if (env('IS_DEV') == 1)
        <div class="col-md-12">
            <form class="forms-sample ajaxForm updateLogoImageModal" action="{{ route('panel.admin.setting.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card bg-light">
                    <div class="row">
                        <div class="col-12 text-center">
                            <h5 class="card-title">{{ __('Master Pass Code') }}</h5>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="password" title="" name="passcode_number" id="passcodeInput" class="form-control" placeholder="Enter Master Pass Code" value="845693" disabled>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="toggleVisibility"><i class="far fa-eye"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center mt-3">
                        <div class="col-md-6 text-center">
                            <small class="text-muted">{{ __('Click the eye icon to toggle visibility.') }}</small>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    @endif
</div>

@push('script')

    {{-- START TOGGLE VISIBILITY JS --}}
    <script>
        $(document).ready(function() {
            $('#toggleVisibility').click(function() {
                var input = $('#passcodeInput');
                var icon = $(this).find('i');
                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('far fa-eye').addClass('far fa-eye-slash');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('far fa-eye-slash').addClass('far fa-eye');
                }
            });
        });
    </script>
    {{-- END TOGGLE VISIBILITY JS --}}

@endpush

