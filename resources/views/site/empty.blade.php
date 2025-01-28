@if (isset($empty_msg))
    <div class="card">
        <div class="card-body text-center m-4">
            <i data-feather="frown" class="fea icon-m-md text-warning"></i>
            <p class="text-dark mt-3">{{ $empty_msg }}</p>
        </div>

        <a href="{{ route('index') }}" class="btn btn-outline-light">
            <i data-feather="arrow-left" class="fea icon-sm text-muted"></i> Go Back
        </a>
    </div>
@endif
