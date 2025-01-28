<div class="card-body">
    <div class="table-controller mb-2">
        <div class="d-flex justify-content-between">
            <div class="mr-3">
                <label for="">Show
                    <select name="length" class="length-input" id="length">
                        @foreach(tableLimits() as $limit)
                        <option value="{{ $limit}}"{{ $contentLengths->
                            perPage() == @$limit ? 'selected' : '' }}>{{ $limit }}</option>
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
                    data-url="{{ route('admin.content-lengths.print') }}"
                    data-rows="{{json_encode($contentLengths) }}"
                    class="btn btn-light btn-sm">Print</a>
                                    --}}
                                <div id="recent_searches" class="d-flex">
                    {{-- @if(!empty(request()->all()))
                        @foreach (request()->all() as $key => $value)
                            @if($key != '_')
                                <a href="javascript:void(0)" id="recent_{{$key}}" onclick="getTableContent('{{$key}}')" class="badge mt-1 ml-1 custom-badge">{{ucwords(str_replace('_', ' ', $key))}} <i class="fa fa-times text-danger" aria-hidden="true"></i></a>
                            @endif
                        @endforeach
                    @endif --}}
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <div>
                <input type="text" name="search" class="form-control mr-2 w-unset" placeholder="Search"
                    id="search" value="{{request()->get('search') }}">
            </div>
            <button type="button" class="off-canvas btn btn-outline-light text-muted btn-icon"><i
                    class="fa fa-filter fa-lg"></i>
            </button>
        </div>
    </div>
    <div class="table-responsive">
        @include('panel.member.content-lengths.table')
    </div>
</div>
<div class="card-footer">
    <div class="row">
        <div class="col-lg-8">
            <div class="pagination mobile-justify-center">
                {{ $contentLengths->appends(request()->except('page'))->links() }}
            </div>
        </div>
        <div class="col-lg-4 mobile-mt-20">
            @if($contentLengths->lastPage() > 1)
            <label class="d-flex justify-content-end mobile-justify-center" for="">
                <div class="mr-2 pt-2">
                    Jump To:
                </div>
                <input type="number" class="w-25 form-control" id="jumpTo" name="page"
                    value="{{ $contentLengths->currentPage() ?? ''}}">
            </label>
            @endif
        </div>
    </div>
</div>
