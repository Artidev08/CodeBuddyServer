<div class="card-body">
    <div class="table-controller mb-2">
        <div class="d-flex justify-content-between">
            <div class="mr-3">
                <label for="">Show
                    <select name="length" class="length-input" id="length">
                        <option value="10"{{ $notifications->perPage() == 10 ? 'selected' : '' }}>10</option>
                        <option value="25"{{ $notifications->perPage() == 25 ? 'selected' : '' }}>25</option>
                        <option value="50"{{ $notifications->perPage() == 50 ? 'selected' : '' }}>50</option>
                        <option value="100"{{ $notifications->perPage() == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    entries
                </label>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="notification_table" class="table">
            <thead>
                <tr>
                    <th>     @lang('admin/ui.sNo') <div class="table-div"><i class="ik ik-arrow-up  asc" data-val="id"></i><i
                        class="ik ik ik-arrow-down desc" data-val="id"></i></div></th>
                    <th>Notification</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="no-data">
                @foreach ($notifications as $notification)
                    <tr>
                        <td >{{ $loop->iteration }}</td>
                        <td>
                            @if ($notification->is_read == 0)
                                <span class="new-update"></span>
                            @endif
                            <p>  {{ $notification->title }}</p> {{ $notification->notification }}
                        </td>
                        <td><a href="{{ route('panel.admin.notifications.update', $notification->id) }}"
                                class="btn btn-icon btn-sm btn-outline-info"><i class="ik ik-eye text-color-white"></i></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="card-footer d-flex justify-content-between">
    <div class="pagination">
        {{ $notifications->appends(request()->except('page'))->links() }}
    </div>
    <div>
        @if ($notifications->lastPage() > 1)
            <label class="d-flex justify-content-end" for="">
                <div class="mr-2 pt-2">
                    Jump To:
                </div>
                <div class="input-group w-50">
                    <input type="number" class="w-25 form-control" id="jumpTo" name="page"
                        value="{{ $notifications->currentPage() ?? '' }}">
                    <div class="w-25 bg-gray py-2 pl-2 fw-700">/ {{ $notifications->lastPage() }}</div>
                </div>
            </label>
        @endif
    </div>
</div>
