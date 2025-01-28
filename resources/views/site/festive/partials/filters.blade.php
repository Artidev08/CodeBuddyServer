<style>
    @media (max-width: 600px) {
        .filters {
            display: none !important;
        }
    }
</style>

<div class="filters">
    @php
        $filter_loop = 1;
    @endphp
    @foreach ($filterOptions as $mainKey => $filterOption)
        @if ($filterOption->count() > 1)
            <div class="filter-options @if ($filter_loop <= 4) mb-1 @endif">
                <select class="w-18 custom-select fw-400 border-0 bg-transparent select2 filter-select">
                    <option value="" data-key1="{{ $mainKey }}" selected>
                        All {{ formatName($mainKey) }}</option>
                    @foreach ($filterOption as $item)
                        <option value="{{ $item->id }}" data-key1="{{ $mainKey }}"
                            @if (@$mainKey == @$key && @$item->id == @$value) selected @endif>
                            {{ @$item->emoji }} {{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        @php
            ++$filter_loop;
        @endphp
    @endforeach
</div>
