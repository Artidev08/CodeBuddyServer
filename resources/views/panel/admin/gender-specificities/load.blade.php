<div class="card-body">
    <div class="table-controller mb-2">
        <div class="d-flex justify-content-between">
            <div class="mr-3">
                <label for="">Show
                    <select name="length" class="length-input" id="length">
                        @foreach (tableLimits() as $limit)
                            <option
                                value="{{ $limit }}"{{ $genderSpecificities->perPage() == @$limit ? 'selected' : '' }}>
                                {{ $limit }}</option>
                        @endforeach
                    </select>
                    entries
                </label>
            </div>
            <div class="d-flex">
                {{--
                                <button type="button" id="export_button" class="btn btn-light btn-sm">Excel</button>
                                    --}}
                {{--
                                <a href="javascript:void(0);" id="print"
                    data-url="{{ route('admin.gender-specificities.print') }}"
                    data-rows="{{json_encode($genderSpecificities) }}"
                    class="btn btn-light btn-sm">Print</a>
                                    --}}
                <div id="recent_searches" class="d-flex">

                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <div>
                <input type="text" name="search" class="form-control mr-2 w-unset" placeholder="Search"
                    id="search" value="{{ request()->get('search') }}">
            </div>
            <button type="button" class="off-canvas btn btn-outline-light text-muted btn-icon"><i
                    class="fa fa-filter fa-lg"></i>
            </button>
        </div>
    </div>
    <div class="table-responsive">
        @include('panel.admin.gender-specificities.table')
    </div>
</div>
<div class="card-footer">
    <div class="row">
        <div class="col-lg-8">
            <div class="pagination mobile-justify-center">
                {{ $genderSpecificities->appends(request()->except('page'))->links() }}
            </div>
        </div>
        <div class="col-lg-4 mobile-mt-20">
            @if ($genderSpecificities->lastPage() > 1)
                <label class="d-flex justify-content-end mobile-justify-center" for="">
                    <div class="mr-2 pt-2">
                        Jump To:
                    </div>
                    <input type="number" class="w-25 form-control" id="jumpTo" name="page"
                        value="{{ $genderSpecificities->currentPage() ?? '' }}">
                </label>
            @endif
        </div>
    </div>
</div>
