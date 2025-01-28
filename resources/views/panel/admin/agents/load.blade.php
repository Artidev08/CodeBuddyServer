<div class="card-body">
    <div class="table-controller mb-2">
        <div class="d-flex justify-content-between">
            <div class="mr-3">
                <label for="">Show
                    <select name="length" class="length-input" id="length">
                        @foreach (tableLimits() as $limit)
                            <option value="{{ @$limit }}"{{ @$agents->perPage() == @$limit ? 'selected' : '' }}>
                                {{ @$limit }}</option>
                        @endforeach
                    </select>
                    entries
                </label>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <div>
                <input type="text" name="search" class="form-control mr-2 w-unset" placeholder="Search"
                    id="search" value="{{ request()->get('search') }}">
            </div>

            <button type="button" class="off-canvas btn btn-light text-muted btn-icon"><i
                    class="ik ik-filter ik-lg"></i>
            </button>
        </div>

    </div>
    <div class="table-responsive">
        @include('panel.admin.agents.table')
    </div>
</div>
<div class="card-footer">
    <div class="row">
        <div class="col-lg-8">
            <div class="pagination mobile-justify-center">
                {{ $agents->appends(request()->except('page'))->links() }}
            </div>
        </div>
        <div class="col-lg-4 mobile-mt-20">
            @if ($agents->lastPage() > 1)
                <label class="d-flex justify-content-end mobile-justify-center" for="">
                    <div class="mr-2 pt-2 ">
                        Jump To:
                    </div>
                    <input type="number" class="w-25 form-control" id="jumpTo" name="page"
                        value="{{ $agents->currentPage() ?? '' }}">
                </label>
            @endif
        </div>
    </div>
</div>
