<style>
    .btn-circle:hover {
        transform: translateY(0) !important;
    }

    .search-bar {
        display: flex;
        justify-content: end;
        width: 22.5rem;
    }

    @media (max-width: 600px) {
        .search-bar {
            display: flex;
            justify-content: start;
            width: 100%;
        }
    }
</style>
@if ($contents->count() != 0)
    <div class="search-bar align-items-start">
        <div>
            <input class="w-100 py-1 px-2 rounded-pill" type="search" id="animated-placeholder-name" name="query"
                placeholder="Search by keywords" required style="min-width: 210px">
        </div>
        <div class="px-1">
            <button class="btn btn-circle btn-light btn-sm border mx-0" id="search">
                <i class="uil uil-search-alt text-black"></i>
            </button>
        </div>
        <div>
            <button class="btn btn-circle btn-light btn-sm border" id="reset">
                <i class="uil uil-redo   text-black"></i>
            </button>
        </div>
    </div>
@endif
