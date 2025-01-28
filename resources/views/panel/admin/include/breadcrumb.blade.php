<style>
@media (max-width: 767.98px) {
    .breadcrumb-container {
        width: 100%;
        display: flex;
        background-color: rgb(245, 245, 245);
        padding: 0;
        margin-top: 5px;

    }
}
</style>

<nav class="breadcrumb-container" aria-label="breadcrumb">
    <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('panel.admin.dashboard.index') }}"><i class="ik ik-home"></i></a>
            </li>
        @foreach ($breadcrumb_arr as $item)
            @if ($item != null)
                <li class="breadcrumb-item {{ $item['class'] }}"><a href="{{ $item['url'] }}"
                        class="item">{{ $item['name'] }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>
